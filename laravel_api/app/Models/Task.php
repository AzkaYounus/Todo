<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User;


class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TasksFactory> */
    use HasFactory,HasApiTokens;
    protected $fillable = ['text', 'date', 'is_complete'];

    //  relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
