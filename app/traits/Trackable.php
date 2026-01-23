<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Trackable
{
    /**
     * Regista uma ação de auditoria a 100% conforme o enunciado.
     */
    public function logAudit(string $module, $objectId, string $message): void
    {
        AuditLog::create([
            'user_id'    => Auth::id(),
            'module'     => $module,
            'object_id'  => $objectId,
            'action'     => $message, // Grava a "Alteração" pedida
            'ip_address' => request()->ip(), // Rastreia o IP
            'browser'    => request()->header('User-Agent'), // Rastreia o Browser
        ]);
    }
}