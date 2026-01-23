<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    /**
     * Display a listing of the audit logs.
     */
    public function index()
    {
        // 1. Procuramos os logs com o relacionamento 'user' carregado (Eager Loading)
        // 2. Ordenamos pelos mais recentes (latest)
        // 3. Pagina em 15 registos para não sobrecarregar a página
        $logs = AuditLog::with('user')
            ->latest()
            ->paginate(15);

        // 4. Retornamos a view de alto contraste que criámos
        return view('logs.index', compact('logs'));
    }
}