<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreferredSchedule extends Model
{
    use HasFactory;

    // 複数代入を許可するカラムを指定
    protected $fillable = [
        'user_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
