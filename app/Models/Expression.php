<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Expression extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id',
        'user_id',
        'is_anonymous',
        'display_name',
        'real_name',
        'origin',
        'content',
        'status',
        'moderation_note',
        'catatan_moderasi',
        'moderated_by',
        'moderated_at',
        'is_featured',
        'is_risky',
        'risk_keywords',
        'ip_address',
        'user_agent',
        'consent_agreed_at',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_featured' => 'boolean',
        'is_risky' => 'boolean',
        'risk_keywords' => 'array',
        'consent_agreed_at' => 'datetime',
        'moderated_at' => 'datetime',
    ];

    protected $hidden = ['real_name', 'ip_address', 'user_agent'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFlagged($query)
    {
        return $query->where('status', 'flagged');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function setRealNameAttribute($value)
    {
        $this->attributes['real_name'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getRealNameDecryptedAttribute()
    {
        if (! $this->attributes['real_name']) {
            return null;
        }

        try {
            return Crypt::decryptString($this->attributes['real_name']);
        } catch (\Exception $e) {
            return '[data rusak]';
        }
    }
}
