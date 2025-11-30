<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'proficiency_level',
        'is_active',
        'description',
        'tags',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tags' => 'array',
        'proficiency_level' => 'integer',
    ];

    protected static function booted()
    {
        static::created(function (Skill $skill) {
            $skill->logs()->create([
                'action' => 'created',
                'details' => "Skill '{$skill->name}' was created.",
            ]);
        });

        static::updated(function (Skill $skill) {
            if ($skill->isDirty('is_active') && $skill->is_active === false) {
                $skill->logs()->create([
                    'action' => 'archived',
                    'details' => "Skill '{$skill->name}' was archived.",
                    'user_id' => auth()->id(),
                ]);
                return;
            }

            $skill->logs()->create([
                'action' => 'updated',
                'details' => "Skill '{$skill->name}' was updated.",
                'user_id' => auth()->id(),
            ]);
        });
    }
    
    public function attachments()
    {
        return $this->hasMany(SkillAttachment::class);
    }

    public function logs()
    {
        return $this->hasMany(SkillActionLog::class);
    }

    public function scopeActive($query) { 
        return $query->where('is_active', true); 
    }
    public function scopeCategory($query, $category) { 
        return $query->where('category', $category); 
    }
}
