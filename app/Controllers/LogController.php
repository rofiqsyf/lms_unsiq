<?php
namespace App\Controllers;

use App\Models\ActivityLog;

class LogController extends BaseController
{
    /** GET /admin/logs */
    public function index(): void
    {
        $this->setTitle('Audit Trail');
        $this->setBreadcrumbs([
            ['label' => 'Dashboard', 'url' => '/dashboard'],
            ['label' => 'Audit Trail']
        ]);

        $logModel = new ActivityLog();
        
        $page = $this->getPage();
        $limit = 50;
        $offset = ($page - 1) * $limit;
        
        $logs = $logModel->getRecentLogs($limit, $offset);
        $totalRows = $logModel->countLogs();
        $totalPages = ceil($totalRows / $limit);

        $this->render('logs/index', [
            'pageTitle'  => 'Log Aktivitas Sistem',
            'logs'       => $logs,
            'pagination' => [
                'current' => $page,
                'total'   => $totalPages,
                'url'     => url('/admin/logs')
            ]
        ]);
    }
}
