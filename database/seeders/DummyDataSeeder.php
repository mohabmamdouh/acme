<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skill;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            [
                'name' => 'Laravel Development',
                'category' => 'Technical',
                'proficiency_level' => 5,
                'is_active' => true,
                'description' => "# Laravel Mastery\nThis skill involves advanced Laravel development.",
                'tags' => ['php', 'framework', 'backend'],
                'notes' => 'Core backend competency.',
            ],
            [
                'name' => 'Team Communication',
                'category' => 'Soft Skill',
                'proficiency_level' => null,
                'is_active' => true,
                'description' => "Ability to communicate effectively within teams.",
                'tags' => ['communication', 'teamwork'],
                'notes' => 'Important for collaboration.',
            ],
            [
                'name' => 'UI/UX Understanding',
                'category' => 'Design',
                'proficiency_level' => null,
                'is_active' => true,
                'description' => "Basic understanding of user interface and user experience principles.",
                'tags' => ['design', 'ux'],
                'notes' => 'Useful in project planning.',
            ],
            [
                'name' => 'Project Leadership',
                'category' => 'Management',
                'proficiency_level' => null,
                'is_active' => false,
                'description' => "Experience leading small to mid-size teams.",
                'tags' => ['leadership'],
                'notes' => 'Archived example.',
            ],
        ];

        foreach ($skills as $data) {
            Skill::create([
                'name' => $data['name'],
                'category' => $data['category'],
                'proficiency_level' => $data['category'] === 'Technical'
                    ? ($data['proficiency_level'] ?? 3)
                    : null,
                'is_active' => $data['is_active'],
                'description' => $data['description'],
                'notes' => $data['notes'],
                'tags' => $data['tags'],
            ]);
        }
    }
}
