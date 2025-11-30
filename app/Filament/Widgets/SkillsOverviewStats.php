<?php

namespace App\Filament\Widgets;

use App\Models\Skill;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SkillsOverviewStats extends BaseWidget
{
    protected function getStats(): array
    {
        $total = Skill::count();
        $active = Skill::where('is_active', true)->count();
        $avgProficiency = number_format((float) Skill::avg('proficiency_level'), 2);

        return [
            Stat::make('Total Skills', $total)
                ->description('All skills in the system')
                ->color('primary'),

            Stat::make('Active Skills', $active)
                ->description('Currently active')
                ->color('success'),

            Stat::make('Avg. Proficiency', $avgProficiency)
                ->description('Average level 1–5')
                ->color('warning'),
        ];
    }
}
