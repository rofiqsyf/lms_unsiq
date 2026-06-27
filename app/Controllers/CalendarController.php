<?php
namespace App\Controllers;

use App\Models\AcademicEvent;
use App\Core\Logger;

class CalendarController extends BaseController
{
    private AcademicEvent $eventModel;

    public function __construct()
    {
        $this->eventModel = new AcademicEvent();
    }

    /** GET /calendar */
    public function index(): void
    {
        $this->setTitle('Kalender Akademik');
        $this->setBreadcrumbs([['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Kalender Akademik']]);
        
        $this->render('pages/calendar', [
            'pageTitle' => 'Kalender Akademik'
        ]);
    }

    /** GET /calendar/events */
    public function events(): void
    {
        $events = $this->eventModel->getForCalendar();
        
        $role = \App\Core\Session::user('role');
        if (in_array($role, ['mahasiswa', 'dosen'])) {
            $assignmentModel = new \App\Models\Assignment();
            $assignments = $assignmentModel->getForCalendar(\App\Core\Session::userId(), $role);
            
            foreach ($assignments as $a) {
                $events[] = [
                    'id' => 'tugas_' . $a['id'],
                    'title' => 'Tenggat: ' . $a['title'] . ' (' . $a['course_name'] . ')',
                    'start_date' => $a['start_date'],
                    'end_date' => $a['end_date'],
                    'event_type' => 'tugas',
                    'color' => '#f59e0b', // Amber for assignments
                    'className' => 'event-tugas'
                ];
            }
        }

        header('Content-Type: application/json');
        echo json_encode($events);
        exit;
    }

    /** POST /calendar/events */
    public function store(): void
    {
        $this->validateCSRF();
        if (!has_role('admin')) { $this->back(); return; }

        $data = $this->allInput();
        $this->validate($data, [
            'title'      => 'required',
            'start_date' => 'required',
            'end_date'   => 'required',
            'event_type' => 'required'
        ]);

        $this->eventModel->create([
            'title'       => $data['title'],
            'start_date'  => $data['start_date'],
            'end_date'    => $data['end_date'],
            'event_type'  => $data['event_type'],
            'description' => $data['description'] ?? ''
        ]);

        Logger::log('create_event', 'calendar', null, 'Membuat agenda: ' . $data['title']);
        flash_success('Agenda berhasil ditambahkan.');
        $this->redirect(url('/calendar'));
    }

    /** POST /calendar/events/{id}/update */
    public function update(int $id): void
    {
        $this->validateCSRF();
        if (!has_role('admin')) { $this->back(); return; }

        $data = $this->allInput();
        $this->validate($data, [
            'title'      => 'required',
            'start_date' => 'required',
            'end_date'   => 'required',
            'event_type' => 'required'
        ]);

        $this->eventModel->update($id, [
            'title'       => $data['title'],
            'start_date'  => $data['start_date'],
            'end_date'    => $data['end_date'],
            'event_type'  => $data['event_type'],
            'description' => $data['description'] ?? ''
        ]);

        Logger::log('update_event', 'calendar', $id, 'Mengubah agenda: ' . $data['title']);
        flash_success('Agenda berhasil diubah.');
        $this->redirect(url('/calendar'));
    }

    /** POST /calendar/events/{id}/delete */
    public function destroy(int $id): void
    {
        $this->validateCSRF();
        if (!has_role('admin')) { $this->back(); return; }

        $this->eventModel->delete($id);
        Logger::log('delete_event', 'calendar', $id, 'Menghapus agenda kalender');
        
        flash_success('Agenda berhasil dihapus.');
        $this->redirect(url('/calendar'));
    }
}
