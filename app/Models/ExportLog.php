<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'filter_params',
        'file_path',
        'row_count',
        'requested_at',
        'completed_at',
        'expires_at',
        'error_message',
    ];

    protected $casts = [
        'filter_params' => 'array',
        'requested_at' => 'datetime',
        'completed_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
