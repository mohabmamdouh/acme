<?php

namespace App\Filament\Widgets;

use App\Models\Skill;
use Filament\Widgets\ChartWidget;

class SkillsByCategoryChart extends ChartWidget
{
    protected int | string | array $columnSpan = 'full';

    protected ?string $heading = 'Skills by Category';

    protected function getData(): array
    {
        $categories = Skill::query()
            ->select('category')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Skills',
                    'data' => array_values($categories),
                ],
            ],
            'labels' => array_keys($categories),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
