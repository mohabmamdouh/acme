<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillFactory extends Factory
{
    protected $model = Skill::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word(),
            'category' => $this->faker->randomElement(['Technical', 'Soft Skill', 'Management', 'Design']),
            'proficiency_level' => $this->faker->numberBetween(1, 5),
            'is_active' => true,
            'description' => $this->faker->paragraph(),
            'tags' => $this->faker->words(3),
            'notes' => $this->faker->sentence(),
        ];
    }
}
