<?php

namespace App\Filament\Resources\Skills\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Schemas\Components\Grid;
use Filament\Infolists\Components\ImageEntry;
class SkillInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General Information')
                    ->icon('heroicon-o-information-circle')
                    ->columns(4)
                    ->schema([
                        TextEntry::make('name')
                            ->label('Skill Name')
                            ->afterStateUpdated(fn($record) => $record->name),

                        TextEntry::make('category')
                            ->label('Category')
                            ->badge()
                            ->afterStateUpdated(fn($record) => $record->category),

                        TextEntry::make('proficiency_level')
                            ->label('Proficiency Level')
                            ->badge()
                            ->color(fn($record) => match ($record->proficiency_level) {
                                1 => 'secondary',
                                2 => 'secondary',
                                3 => 'warning',
                                4 => 'success',
                                5 => 'success',
                                default => 'secondary',
                            })
                            ->afterStateUpdated(fn($record) => $record->proficiency_level ?? '-'),

                        TextEntry::make('is_active')
                            ->label('Status')
                            ->badge()
                            ->color(fn($record) => $record->is_active ? 'success' : 'danger')
                            ->afterStateUpdated(fn($record) => $record->is_active ? 'Active' : 'Inactive'),

                        RepeatableEntry::make('tags')
                            ->columnSpanFull()
                            ->schema([
                                TextEntry::make('value')
                                    ->label('Tag')
                                    ->afterStateUpdated(fn($record) => $record->value),
                            ])
                            ->label('Tags'),

                        Grid::make()
                            ->columnSpanFull()
                            ->columns(2)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label('Created At')
                                    ->afterStateUpdated(fn($record) => $record->created_at->format('Y-m-d H:i')),

                                TextEntry::make('updated_at')
                                    ->label('Last Updated')
                                    ->afterStateUpdated(fn($record) => $record->updated_at->format('Y-m-d H:i')),
                            ]),
                    ]),

                Section::make('Description')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Description')
                            ->markdown(),
                    ]),

                Section::make('Attachments')
                    ->icon('heroicon-o-paper-clip')
                    ->schema([
                        RepeatableEntry::make('attachments')
                            ->label('')
                            ->schema([
                                ImageEntry::make('path')
                                    ->label('File')
                                    ->url(fn($record) => $record->url, shouldOpenInNewTab: true),
                            ])
                            ->contained(false)
                            ->columnSpanFull(),
                    ])
                    ->hidden(fn($record) => $record->attachments->isEmpty()),
            ])
            ->columns(1);

    }
}
