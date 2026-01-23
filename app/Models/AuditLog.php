<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    protected $fillable = ['user_id', 'module', 'object_id', 'action', 'ip_address', 'browser'];

    // Relacionamento para o Controller funcionar com 'with(user)'
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    // Nome do método ajustado para logAction conforme usado no Livewire
    public static function logAction($module, $objectId, $action) {
        self::create([
            'user_id'    => auth()->id(),
            'module'     => $module,
            'object_id'  => $objectId,
            'action'     => $action, 
            'ip_address' => request()->ip(),
            'browser'    => request()->header('User-Agent'),
        ]);
    }
}