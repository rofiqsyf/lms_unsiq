<?php
namespace App\Core;

/**
 * ===========================================
 * Mailer - Simple Email System
 * ===========================================
 * Mendukung pengiriman email sederhana.
 * Jika MAIL_DRIVER=log, email hanya dicatat ke storage/logs/emails.log
 */
class Mailer
{
    private string $logFile;

    public function __construct()
    {
        $this->logFile = STORAGE_PATH . '/logs/emails.log';
        
        // Ensure log directory exists
        if (!is_dir(dirname($this->logFile))) {
            mkdir(dirname($this->logFile), 0777, true);
        }
    }

    /**
     * Send email
     * 
     * @param string $to Email penerima
     * @param string $subject Subjek email
     * @param string $body Isi email
     * @return bool
     */
    public function send(string $to, string $subject, string $body): bool
    {
        $driver = env('MAIL_DRIVER', 'log');

        if ($driver === 'log') {
            return $this->logEmail($to, $subject, $body);
        }

        if ($driver === 'mail') {
            // PHP native mail function
            $headers = "From: " . env('MAIL_FROM_ADDRESS', 'noreply@lms.unsiq.ac.id') . "\r\n";
            $headers .= "Reply-To: " . env('MAIL_FROM_ADDRESS', 'noreply@lms.unsiq.ac.id') . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            
            return mail($to, $subject, nl2br($body), $headers);
        }

        return false; // Unsupported driver
    }

    /**
     * Log email to file (for development)
     */
    private function logEmail(string $to, string $subject, string $body): bool
    {
        $date = date('Y-m-d H:i:s');
        $divider = str_repeat('-', 50);
        
        $logContent = <<<LOG
{$divider}
Date: {$date}
To: {$to}
Subject: {$subject}
Body:
{$body}
{$divider}

LOG;

        return file_put_contents($this->logFile, $logContent, FILE_APPEND) !== false;
    }
}
