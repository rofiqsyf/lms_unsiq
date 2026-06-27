<?php
namespace App\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Core\Session;

class MessageController extends BaseController
{
    private Message $messageModel;
    private User $userModel;

    public function __construct()
    {
        $this->messageModel = new Message();
        $this->userModel = new User();
    }

    /** GET /messages */
    public function index(): void
    {
        $this->setTitle('Pesan Pribadi');
        $this->setBreadcrumbs([['label' => 'Dashboard', 'url' => '/dashboard'], ['label' => 'Pesan']]);

        $conversations = $this->messageModel->getConversations(Session::userId());

        $this->render('messages/index', [
            'pageTitle'     => 'Pesan Pribadi',
            'conversations' => $conversations
        ]);
    }

    /** GET /messages/{userId} */
    public function show(int $otherUserId): void
    {
        // Don't allow messaging self
        if ($otherUserId == Session::userId()) {
            $this->redirect(url('/messages'));
            return;
        }

        $otherUser = $this->userModel->findById($otherUserId);
        if (!$otherUser) {
            $this->redirect(url('/messages'));
            return;
        }

        // Mark as read
        $this->messageModel->markAsRead(Session::userId(), $otherUserId);

        $conversations = $this->messageModel->getConversations(Session::userId());
        $history = $this->messageModel->getHistoryWithUser(Session::userId(), $otherUserId);

        $this->setTitle('Chat dengan ' . $otherUser['name']);
        
        $this->render('messages/show', [
            'pageTitle'     => 'Pesan',
            'conversations' => $conversations,
            'otherUser'     => $otherUser,
            'history'       => $history
        ]);
    }

    /** POST /messages/{userId} */
    public function store(int $otherUserId): void
    {
        $this->validateCSRF();
        
        $body = $this->input('body', '');
        if (trim($body) === '') {
            $this->back();
            return;
        }

        $this->messageModel->create([
            'sender_id'   => Session::userId(),
            'receiver_id' => $otherUserId,
            'body'        => $body,
            'is_read'     => 0
        ]);

        $this->redirect(url('/messages/' . $otherUserId));
    }
}
