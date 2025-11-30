<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillActionLog extends Model
{
    protected $fillable = [
        'skill_id',
        'action',
        'meta',
        'user_id'
    ];

    protected $casts = [
        'meta' => 'array'
    ];

    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
