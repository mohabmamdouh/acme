<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillAttachment extends Model
{
    protected $fillable = [
        'skill_id',
        'filename',
        'path',
        'mime',
        'size'
    ];

    public function skill() { 
        return $this->belongsTo(Skill::class); 
    }

    public function getUrlAttribute()
    {
        return filled($this->path) ? \Storage::url($this->path) : null;
    }
}
