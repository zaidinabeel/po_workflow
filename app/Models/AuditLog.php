<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'model_type', 'model_id',
        'description', 'meta', 'ip_address',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Convenience static logger
     */
    public static function record(
        string $action,
        string $description,
        ?Model $model = null,
        array $meta = []
    ): void {
        static::create([
            'user_id'    => Auth::id(),
            'action'     => $action,
            'model_type' => $model ? class_basename($model) : null,
            'model_id'   => $model?->id,
            'description'=> $description,
            'meta'       => !empty($meta) ? $meta : null,
            'ip_address' => Request::ip(),
        ]);
    }
}
