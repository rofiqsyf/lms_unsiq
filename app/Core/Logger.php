<?php
namespace App\Core;

use App\Models\ActivityLog;

class Logger
{
    /**
     * Catat aktivitas ke dalam database
     *
     * @param string $action Nama aksi (misal: 'login', 'create_course')
     * @param string|null $entityType Tipe entitas (misal: 'course', 'user')
     * @param int|null $entityId ID entitas yang terkait
     * @param string|null $details Keterangan tambahan
     */
    public static function log(string $action, ?string $entityType = null, ?int $entityId = null, ?string $details = null): void
    {
        try {
            $userId = Session::userId() ?: null;
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

            $logModel = new ActivityLog();
            $logModel->create([
                'user_id'     => $userId,
                'action'      => $action,
                'entity_type' => $entityType,
                'entity_id'   => $entityId,
                'details'     => $details,
                'ip_address'  => $ipAddress
            ]);
        } catch (\Throwable $e) {
            // Silently ignore logging errors in production
            if (env('APP_DEBUG', 'true') === 'true') {
                error_log("Logger Error: " . $e->getMessage());
            }
        }
    }
}
