<?php
namespace App\Controllers;

use App\Models\Notification;
use App\Core\Session;

class NotificationController extends BaseController
{
    /** GET /notifications */
    public function index(): void
    {
        $model = new Notification();
        $notifications = $model->getByUser(Session::userId(), 50);
        $this->setTitle('Notifikasi');
        $this->render('announcements/index', ['pageTitle' => 'Notifikasi', 'notifications' => $notifications]);
    }

    /** GET /notifications/read-all */
    public function readAll(): void
    {
        $model = new Notification();
        $model->markAllAsRead(Session::userId());
        flash_success('Semua notifikasi telah ditandai dibaca.');
        $this->back();
    }

    /** GET /notifications/{id}/read */
    public function read(int $id): void
    {
        $model = new Notification();
        $notif = $model->findById($id);
        if ($notif && $notif['user_id'] == Session::userId()) {
            $model->markAsRead($id);
            if (!empty($notif['link'])) {
                $this->redirect(url($notif['link']));
                return;
            }
        }
        $this->back();
    }

    /** GET /notifications/json (AJAX) */
    public function getJson(): void
    {
        $model = new Notification();
        $notifications = $model->getByUser(Session::userId(), 10);
        $this->json(['data' => $notifications, 'unread' => $model->countUnread(Session::userId())]);
    }
}
