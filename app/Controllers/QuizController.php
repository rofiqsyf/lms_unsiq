<?php
namespace App\Controllers;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\Answer;
use App\Models\Course;
use App\Core\Session;

class QuizController extends BaseController
{
    private Quiz $quizModel;

    public function __construct() { $this->quizModel = new Quiz(); }

    /** GET /courses/{courseId}/quizzes/create */
    public function create(int $courseId): void
    {
        $course = (new Course())->findById($courseId);
        if (!$course) { $this->redirect(url('/courses')); return; }
        $this->setTitle('Tambah Kuis');
        $this->render('quizzes/create', ['pageTitle' => 'Tambah Kuis', 'course' => $course]);
    }

    /** POST /courses/{courseId}/quizzes */
    public function store(int $courseId): void
    {
        $this->validateCSRF();
        $data = $this->allInput();
        $this->validate($data, ['title' => 'required|min:3', 'duration_minutes' => 'required|numeric']);

        $this->quizModel->create([
            'course_id'         => $courseId,
            'title'             => $data['title'],
            'description'       => $data['description'] ?? '',
            'duration_minutes'  => (int) $data['duration_minutes'],
            'max_attempts'      => (int) ($data['max_attempts'] ?? 1),
            'shuffle_questions' => isset($data['shuffle_questions']) ? 1 : 0,
            'show_result'       => isset($data['show_result']) ? 1 : 0,
            'passing_score'     => (int) ($data['passing_score'] ?? 60),
            'is_published'      => isset($data['is_published']) ? 1 : 0,
            'start_time'        => $data['start_time'] ?: null,
            'end_time'          => $data['end_time'] ?: null,
        ]);

        flash_success('Kuis berhasil ditambahkan.');
        $this->redirect(url('/courses/' . $courseId));
    }

    /** GET /quizzes/{id} */
    public function show(int $id): void
    {
        $quiz = $this->quizModel->findWithCourse($id);
        if (!$quiz) { $this->redirect(url('/courses')); return; }

        $questionModel = new Question();
        $questions = $questionModel->getByQuiz($id);
        $attemptModel = new QuizAttempt();
        $attempts = has_role('mahasiswa') ? $attemptModel->getAttemptsByQuizAndUser($id, Session::userId()) : [];

        $this->setTitle($quiz['title']);
        $this->render('quizzes/index', [
            'pageTitle' => $quiz['title'],
            'quiz'      => $quiz,
            'questions' => $questions,
            'attempts'  => $attempts,
        ]);
    }

    /** POST /quizzes/{id}/start */
    public function start(int $id): void
    {
        $this->validateCSRF();
        $quiz = $this->quizModel->findById($id);
        if (!$quiz) { $this->redirect(url('/courses')); return; }

        $attemptModel = new QuizAttempt();
        $userId = Session::userId();

        // Check max attempts
        $attemptCount = $attemptModel->countAttempts($id, $userId);
        if ($attemptCount >= $quiz['max_attempts']) {
            flash_error('Anda sudah mencapai batas percobaan.');
            $this->redirect(url('/quizzes/' . $id));
            return;
        }

        // Check if already has active attempt
        $active = $attemptModel->getActiveAttempt($id, $userId);
        if ($active) {
            $this->redirect(url('/quizzes/' . $id . '/attempt/' . $active['id']));
            return;
        }

        $questionModel = new Question();
        $totalPoints = $questionModel->getTotalPoints($id);

        $attemptId = $attemptModel->create([
            'quiz_id'      => $id,
            'user_id'      => $userId,
            'total_points' => $totalPoints,
            'started_at'   => date('Y-m-d H:i:s'),
            'status'       => 'in_progress',
        ]);

        $this->redirect(url('/quizzes/' . $id . '/attempt/' . $attemptId));
    }

    /** GET /quizzes/{quizId}/attempt/{attemptId} */
    public function attempt(int $quizId, int $attemptId): void
    {
        $quiz = $this->quizModel->findById($quizId);
        $attemptModel = new QuizAttempt();
        $attempt = $attemptModel->findById($attemptId);

        if (!$quiz || !$attempt || $attempt['user_id'] != Session::userId()) {
            $this->redirect(url('/courses'));
            return;
        }

        if ($attempt['status'] !== 'in_progress') {
            $this->redirect(url('/quizzes/' . $quizId . '/result/' . $attemptId));
            return;
        }

        $questionModel = new Question();
        $questions = $questionModel->getByQuiz($quizId, $quiz['shuffle_questions']);

        $this->setTitle('Mengerjakan: ' . $quiz['title']);
        $this->render('quizzes/attempt', [
            'pageTitle'  => 'Mengerjakan Kuis',
            'quiz'       => $quiz,
            'attempt'    => $attempt,
            'questions'  => $questions,
        ]);
    }

    /** POST /quizzes/{quizId}/attempt/{attemptId}/submit */
    public function submitAttempt(int $quizId, int $attemptId): void
    {
        $this->validateCSRF();
        $data = $this->allInput();

        $questionModel = new Question();
        $answerModel = new Answer();
        $attemptModel = new QuizAttempt();

        $questions = $questionModel->getByQuiz($quizId);
        $totalScore = 0;

        foreach ($questions as $q) {
            $answer = $data['answer_' . $q['id']] ?? '';
            $isCorrect = null;
            $pointsEarned = 0;

            if ($q['type'] === 'multiple_choice') {
                $isCorrect = (strtoupper(trim($answer)) === strtoupper(trim($q['correct_answer']))) ? 1 : 0;
                $pointsEarned = $isCorrect ? $q['points'] : 0;
                $totalScore += $pointsEarned;
            }
            // Essay graded manually

            $answerModel->create([
                'attempt_id'    => $attemptId,
                'question_id'   => $q['id'],
                'answer_text'   => $answer,
                'is_correct'    => $isCorrect,
                'points_earned' => $pointsEarned,
            ]);
        }

        $attemptModel->update($attemptId, [
            'score'        => $totalScore,
            'completed_at' => date('Y-m-d H:i:s'),
            'status'       => 'completed',
        ]);

        flash_success('Kuis berhasil dikumpulkan!');
        $this->redirect(url('/quizzes/' . $quizId . '/result/' . $attemptId));
    }

    /** GET /quizzes/{quizId}/result/{attemptId} */
    public function result(int $quizId, int $attemptId): void
    {
        $quiz = $this->quizModel->findWithCourse($quizId);
        $attemptModel = new QuizAttempt();
        $attempt = $attemptModel->getAttemptWithAnswers($attemptId);

        if (!$quiz || !$attempt) { $this->redirect(url('/courses')); return; }

        $this->setTitle('Hasil Kuis');
        $this->render('quizzes/result', [
            'pageTitle' => 'Hasil Kuis',
            'quiz'      => $quiz,
            'attempt'   => $attempt,
        ]);
    }

    /** GET /quizzes/{id}/edit */
    public function edit(int $id): void
    {
        $quiz = $this->quizModel->findWithCourse($id);
        if (!$quiz) { $this->redirect(url('/courses')); return; }

        // Authorization: only owner dosen or admin
        if (!has_role('admin') && $quiz['dosen_id'] != Session::userId()) {
            flash_error('Anda tidak memiliki akses.');
            $this->redirect(url('/quizzes/' . $id));
            return;
        }

        $questionModel = new Question();
        $questions = $questionModel->getByQuiz($id);

        $this->setTitle('Edit Kuis');
        $this->setBreadcrumbs([
            ['label' => 'Mata Kuliah', 'url' => '/courses'],
            ['label' => $quiz['course_name'], 'url' => '/courses/' . $quiz['course_id']],
            ['label' => $quiz['title'], 'url' => '/quizzes/' . $id],
            ['label' => 'Edit']
        ]);
        $this->render('quizzes/edit', [
            'pageTitle' => 'Edit Kuis',
            'quiz'      => $quiz,
            'questions' => $questions,
        ]);
    }

    /** POST /quizzes/{id}/update */
    public function update(int $id): void
    {
        $this->validateCSRF();
        $data = $this->allInput();
        $quiz = $this->quizModel->findWithCourse($id);
        if (!$quiz) { $this->redirect(url('/courses')); return; }

        if (!has_role('admin') && $quiz['dosen_id'] != Session::userId()) {
            flash_error('Anda tidak memiliki akses.');
            $this->redirect(url('/quizzes/' . $id));
            return;
        }

        $this->validate($data, ['title' => 'required|min:3', 'duration_minutes' => 'required|numeric']);

        $this->quizModel->update($id, [
            'title'             => $data['title'],
            'description'       => $data['description'] ?? '',
            'duration_minutes'  => (int) $data['duration_minutes'],
            'max_attempts'      => (int) ($data['max_attempts'] ?? 1),
            'shuffle_questions' => isset($data['shuffle_questions']) ? 1 : 0,
            'show_result'       => isset($data['show_result']) ? 1 : 0,
            'passing_score'     => (int) ($data['passing_score'] ?? 60),
            'is_published'      => isset($data['is_published']) ? 1 : 0,
            'start_time'        => !empty($data['start_time']) ? $data['start_time'] : null,
            'end_time'          => !empty($data['end_time']) ? $data['end_time'] : null,
        ]);

        flash_success('Kuis berhasil diperbarui.');
        $this->redirect(url('/quizzes/' . $id . '/edit'));
    }

    /** POST /quizzes/{quizId}/questions */
    public function storeQuestion(int $quizId): void
    {
        $this->validateCSRF();
        $quiz = $this->quizModel->findWithCourse($quizId);
        if (!$quiz) { $this->back(); return; }

        if (!has_role('admin') && $quiz['dosen_id'] != Session::userId()) {
            flash_error('Anda tidak memiliki akses.');
            $this->back();
            return;
        }

        $data = $this->allInput();
        $this->validate($data, ['question_text' => 'required|min:3', 'points' => 'required|numeric']);

        $questionModel = new Question();

        $questionData = [
            'quiz_id'        => $quizId,
            'question_text'  => $data['question_text'],
            'type'           => $data['type'] ?? 'multiple_choice',
            'points'         => (int) $data['points'],
            'sort_order'     => (int) ($data['sort_order'] ?? 0),
            'explanation'    => $data['explanation'] ?? '',
            'correct_answer' => $data['correct_answer'] ?? '',
        ];

        // Handle options for multiple choice
        if ($questionData['type'] === 'multiple_choice' && !empty($data['options'])) {
            $options = array_filter($data['options'], fn($o) => trim($o) !== '');
            $questionData['options'] = json_encode(array_values($options));
        }

        $questionModel->create($questionData);
        flash_success('Pertanyaan berhasil ditambahkan.');
        $this->redirect(url('/quizzes/' . $quizId . '/edit'));
    }

    /** POST /questions/{id}/update */
    public function updateQuestion(int $id): void
    {
        $this->validateCSRF();
        $data = $this->allInput();

        $questionModel = new Question();
        $question = $questionModel->findById($id);
        if (!$question) { $this->back(); return; }

        $quiz = $this->quizModel->findWithCourse($question['quiz_id']);
        if (!has_role('admin') && $quiz['dosen_id'] != Session::userId()) {
            flash_error('Anda tidak memiliki akses.');
            $this->back();
            return;
        }

        $this->validate($data, ['question_text' => 'required|min:3', 'points' => 'required|numeric']);

        $updateData = [
            'question_text'  => $data['question_text'],
            'type'           => $data['type'] ?? 'multiple_choice',
            'points'         => (int) $data['points'],
            'sort_order'     => (int) ($data['sort_order'] ?? 0),
            'explanation'    => $data['explanation'] ?? '',
            'correct_answer' => $data['correct_answer'] ?? '',
        ];

        if ($updateData['type'] === 'multiple_choice' && !empty($data['options'])) {
            $options = array_filter($data['options'], fn($o) => trim($o) !== '');
            $updateData['options'] = json_encode(array_values($options));
        }

        $questionModel->update($id, $updateData);
        flash_success('Pertanyaan berhasil diperbarui.');
        $this->redirect(url('/quizzes/' . $question['quiz_id'] . '/edit'));
    }

    /** POST /questions/{id}/delete */
    public function deleteQuestion(int $id): void
    {
        $this->validateCSRF();
        $questionModel = new Question();
        $question = $questionModel->findById($id);
        if (!$question) { $this->back(); return; }

        $quiz = $this->quizModel->findWithCourse($question['quiz_id']);
        if (!has_role('admin') && $quiz['dosen_id'] != Session::userId()) {
            flash_error('Anda tidak memiliki akses.');
            $this->back();
            return;
        }

        $quizId = $question['quiz_id'];
        $questionModel->delete($id);
        flash_success('Pertanyaan berhasil dihapus.');
        $this->redirect(url('/quizzes/' . $quizId . '/edit'));
    }

    /** POST /quizzes/{id}/delete */
    public function destroy(int $id): void
    {
        $this->validateCSRF();
        $quiz = $this->quizModel->findWithCourse($id);
        if ($quiz) {
            if (!has_role('admin') && $quiz['dosen_id'] != Session::userId()) {
                flash_error('Anda tidak memiliki akses.');
                $this->redirect(url('/courses/' . $quiz['course_id']));
                return;
            }

            $this->quizModel->delete($id);
            flash_success('Kuis berhasil dihapus.');
            $this->redirect(url('/courses/' . $quiz['course_id']));
        }
    }
}
