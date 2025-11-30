<?php

namespace App\Filament\Resources\Skills\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Tables\Columns\Summaries\Avg;
use Filament\Tables\Filters\Filter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Range;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Notifications\Notification;
class SkillsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->summarize(Count::make())
                    ->label('Skill'),

                TextColumn::make('category')
                    ->sortable()
                    ->badge()
                    ->searchable(),

                TextColumn::make('proficiency_level')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn($record) => match ($record->proficiency_level) {
                        1 => 'secondary',
                        2 => 'secondary',
                        3 => 'warning',
                        4 => 'success',
                        5 => 'success',
                        default => 'secondary',
                    })
                    ->summarize([Average::make()->label('Avg Level')])
                    ->label('Level'),

                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable()
                    ->label('Active'),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'Technical' => 'Technical',
                        'Soft Skill' => 'Soft Skill',
                        'Management' => 'Management',
                        'Design' => 'Design',
                    ])
                    ->label('Filter by Category'),

                SelectFilter::make('proficiency_level')
                    ->label('Min Proficiency')
                    ->options([
                        1 => 'Beginner',
                        2 => 'Basic',
                        3 => 'Intermediate',
                        4 => 'Advanced',
                        5 => 'Expert',
                    ])
                    ->query(function ($query, $value) {
                        if ($value)
                            return $query->where('proficiency_level', '<=', $value);
                    }),

                Filter::make('is_active')
                    ->label('Active Only')
                    ->toggle()
                    ->query(fn($query) => $query->where('is_active', true)),
            ])
            ->groups([
                'category',
                'proficiency_level',
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),

                Action::make('toggle_active')
                    ->label('Toggle Active')
                    ->icon('heroicon-o-power')
                    ->color('warning')
                    ->action(function ($record) {
                        $record->is_active = !$record->is_active;
                        $record->save();

                        Notification::make()
                            ->title('Skill Toggled')
                            ->body('The skill has been toggled to ' . ($record->is_active ? 'active' : 'inactive'))
                            ->color($record->is_active ? 'success' : 'warning')
                            ->send();
                    }),

                Action::make('archive')
                    ->label('Archive')
                    ->icon('heroicon-o-archive-box')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->update(['is_active' => false]);

                        Notification::make()
                            ->title('Skill Archived')
                            ->body('The skill has been archived')
                            ->color('success')
                            ->send();
                    }),

                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->delete();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    Action::make('bulk_archive')
                        ->label('Archive Selected')
                        ->icon('heroicon-o-archive-box')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_active' => false]);
                            }
                        }),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
