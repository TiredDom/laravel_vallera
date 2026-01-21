<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEditRequest extends Model
{
    protected $fillable = [
        'requested_by',
        'target_user_id',
        'field_name',
        'old_value',
        'new_value',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_note',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
