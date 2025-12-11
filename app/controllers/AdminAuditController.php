<?php

require_once __DIR__ . '/../models/AuditLog.php';
require_once __DIR__ . '/../core/permissions.php';

class AdminAuditController extends Controller
{
    public function index()
    {
        requirePermission('view_audit_log');

        $logs = AuditLog::all();

        return $this->view('admin/audit/index', [
            'logs' => $logs
        ]);
    }
}
