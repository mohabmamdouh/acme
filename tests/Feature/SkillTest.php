<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SkillTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_scope_returns_only_active_skills()
    {
        Skill::factory()->create(['is_active' => true]);
        Skill::factory()->create(['is_active' => false]);

        $activeSkills = Skill::active()->get();
        $this->assertCount(1, $activeSkills);
        $this->assertTrue($activeSkills->first()->is_active);
    }
}
