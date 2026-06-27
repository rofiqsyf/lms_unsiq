<?php
namespace App\Controllers;

use App\Models\ForumThread;
use App\Models\ForumReply;
use App\Models\Course;
use App\Models\Enrollment;
use App\Core\Session;

class ForumController extends BaseController
{
    private ForumThread $threadModel;
    private ForumReply $replyModel;
    private Course $courseModel;

    public function __construct()
    {
        $this->threadModel = new ForumThread();
        $this->replyModel = new ForumReply();
        $this->courseModel = new Course();
    }

    /**
     * Helper to check access
     */
    private function checkAccess(int $courseId): ?array
    {
        $course = $this->courseModel->findById($courseId);
        if (!$course) return null;

        if (has_role('admin')) return $course;
        if (has_role('dosen') && $course['dosen_id'] == Session::userId()) return $course;
        
        if (has_role('mahasiswa')) {
            $enrollmentModel = new Enrollment();
            if ($enrollmentModel->isEnrolled(Session::userId(), $courseId)) {
                return $course;
            }
        }
        return null;
    }

    /** GET /courses/{courseId}/forum */
    public function index(int $courseId): void
    {
        $course = $this->checkAccess($courseId);
        if (!$course) { $this->redirect(url('/courses')); return; }

        $page = $this->getPage();
        $result = $this->threadModel->getByCourse($courseId, $page, 15, url("/courses/{$courseId}/forum"));

        $this->setTitle('Forum Diskusi - ' . $course['name']);
        $this->setBreadcrumbs([
            ['label' => 'Mata Kuliah', 'url' => '/courses'],
            ['label' => $course['name'], 'url' => "/courses/{$courseId}"],
            ['label' => 'Forum Diskusi']
        ]);

        $this->render('forum/index', [
            'pageTitle'  => 'Forum Diskusi',
            'course'     => $course,
            'threads'    => $result['data'],
            'pagination' => $result['pagination'],
        ]);
    }

    /** GET /courses/{courseId}/forum/create */
    public function create(int $courseId): void
    {
        $course = $this->checkAccess($courseId);
        if (!$course) { $this->redirect(url('/courses')); return; }

        $this->setTitle('Buat Topik Baru');
        $this->setBreadcrumbs([
            ['label' => 'Mata Kuliah', 'url' => '/courses'],
            ['label' => $course['name'], 'url' => "/courses/{$courseId}"],
            ['label' => 'Forum Diskusi', 'url' => "/courses/{$courseId}/forum"],
            ['label' => 'Buat Topik']
        ]);

        $this->render('forum/create', [
            'pageTitle' => 'Buat Topik Baru',
            'course'    => $course,
        ]);
    }

    /** POST /courses/{courseId}/forum */
    public function store(int $courseId): void
    {
        $course = $this->checkAccess($courseId);
        if (!$course) { $this->redirect(url('/courses')); return; }

        $this->validateCSRF();
        $data = $this->allInput();
        $this->validate($data, ['title' => 'required|min:3', 'body' => 'required']);

        $threadId = $this->threadModel->create([
            'course_id' => $courseId,
            'user_id'   => Session::userId(),
            'title'     => $data['title'],
            'body'      => $data['body'],
            'is_pinned' => (has_role('admin', 'dosen') && isset($data['is_pinned'])) ? 1 : 0,
            'is_locked' => (has_role('admin', 'dosen') && isset($data['is_locked'])) ? 1 : 0,
        ]);

        flash_success('Topik berhasil dibuat.');
        $this->redirect(url("/forum/thread/{$threadId}"));
    }

    /** GET /forum/thread/{id} */
    public function show(int $id): void
    {
        $thread = $this->threadModel->findWithUser($id);
        if (!$thread) { $this->redirect(url('/courses')); return; }

        $course = $this->checkAccess($thread['course_id']);
        if (!$course) { $this->redirect(url('/courses')); return; }

        $replies = $this->replyModel->getByThread($id);

        $this->setTitle($thread['title']);
        $this->setBreadcrumbs([
            ['label' => 'Mata Kuliah', 'url' => '/courses'],
            ['label' => $course['name'], 'url' => "/courses/{$course['id']}"],
            ['label' => 'Forum', 'url' => "/courses/{$course['id']}/forum"],
            ['label' => $thread['title']]
        ]);

        $this->render('forum/show', [
            'pageTitle' => $thread['title'],
            'course'    => $course,
            'thread'    => $thread,
            'replies'   => $replies,
        ]);
    }

    /** POST /forum/thread/{id}/reply */
    public function reply(int $id): void
    {
        $this->validateCSRF();
        $thread = $this->threadModel->findById($id);
        if (!$thread) { $this->redirect(url('/courses')); return; }

        if ($thread['is_locked'] && !has_role('admin', 'dosen')) {
            flash_error('Topik ini sudah dikunci.');
            $this->redirect(url("/forum/thread/{$id}"));
            return;
        }

        $course = $this->checkAccess($thread['course_id']);
        if (!$course) { $this->redirect(url('/courses')); return; }

        $data = $this->allInput();
        $this->validate($data, ['body' => 'required']);

        $this->replyModel->create([
            'thread_id' => $id,
            'user_id'   => Session::userId(),
            'body'      => $data['body'],
        ]);

        $this->threadModel->incrementReplyCount($id);

        flash_success('Balasan berhasil dikirim.');
        $this->redirect(url("/forum/thread/{$id}"));
    }

    /** POST /forum/thread/{id}/delete */
    public function destroy(int $id): void
    {
        $this->validateCSRF();
        $thread = $this->threadModel->findById($id);
        if (!$thread) { $this->back(); return; }

        $this->threadModel->delete($id);
        flash_success('Topik berhasil dihapus.');
        $this->redirect(url("/courses/{$thread['course_id']}/forum"));
    }

    /** POST /forum/reply/{id}/delete */
    public function deleteReply(int $id): void
    {
        $this->validateCSRF();
        $reply = $this->replyModel->findById($id);
        if (!$reply) { $this->back(); return; }

        $threadId = $reply['thread_id'];
        $this->replyModel->delete($id);
        $this->threadModel->decrementReplyCount($threadId);

        flash_success('Balasan berhasil dihapus.');
        $this->redirect(url("/forum/thread/{$threadId}"));
    }
}
