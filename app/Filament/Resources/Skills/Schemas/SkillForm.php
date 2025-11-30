<?php

namespace App\Filament\Resources\Skills\Schemas;

use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Storage;
class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(1)
                    ->schema([
                        Wizard::make([
                            Wizard\Step::make('Basic Info')
                                ->icon('heroicon-o-information-circle')
                                ->schema([
                                    TextInput::make('name')
                                        ->label('Skill Name')
                                        ->required()
                                        ->unique(ignoreRecord: true)
                                        ->maxLength(100),

                                    Select::make('category')
                                        ->label('Category')
                                        ->searchable()
                                        ->required()
                                        ->options([
                                            'Technical' => 'Technical',
                                            'Soft Skill' => 'Soft Skill',
                                            'Management' => 'Management',
                                            'Design' => 'Design',
                                        ])
                                        ->reactive(),

                                    Select::make('proficiency_level')
                                        ->label('Proficiency Level')
                                        ->options([
                                            1 => 'Beginner',
                                            2 => 'Basic',
                                            3 => 'Intermediate',
                                            4 => 'Advanced',
                                            5 => 'Expert',
                                        ])
                                        ->visible(fn(callable $get) => $get('category') === 'Technical')
                                        ->required(fn(callable $get) => $get('category') === 'Technical'),

                                    Toggle::make('is_active')
                                        ->label('Mark as Active')
                                        ->default(true),
                                ]),
                            Wizard\Step::make('Content')
                                ->icon('heroicon-o-document-text')
                                ->schema([
                                    MarkdownEditor::make('description')
                                        ->label('Description')
                                        ->required()
                                        ->fileAttachmentsDirectory('skill-descriptions'),

                                    Textarea::make('notes')
                                        ->label('Internal Notes')
                                        ->maxLength(500)
                                        ->reactive()
                                        ->extraInputAttributes([
                                            'x-data' => '{ count: $el.value.length }',
                                            'x-on:input' => 'count = $el.value.length',
                                        ])
                                        ->hint(fn() => new HtmlString('
                                            <div x-data="{ get count() { return document.querySelector(\'[wire\\\\:model=\\\"data.notes\\\"]\')?.value.length || 0 } }">
                                                <span x-text="count">0</span> / 500 characters
                                            </div>
                                        '))
                                        ->rule('max:500')
                                        ->validationAttribute('internal notes'),
                                ]),
                            Wizard\Step::make('Metadata')
                                ->icon('heroicon-o-tag')
                                ->schema([
                                    Repeater::make('tags')
                                        ->label('Tags')
                                        ->schema([
                                            TextInput::make('value')
                                                ->label('Tag')
                                                ->maxLength(50)
                                        ])
                                        ->default([]),

                                    FileUpload::make('attachments')
                                        ->label('Attachments')
                                        ->multiple()
                                        ->maxFiles(5)
                                        ->directory('skills/attachments')
                                        ->visibility('public')
                                        ->preserveFilenames()
                                        ->saveRelationshipsUsing(function ($component, $state, $record) {
                                            if ($record) {
                                                $existingPaths = $record->attachments->pluck('path')->toArray();
                                                $newPaths = $state ?? [];
                                                $toDelete = array_diff($existingPaths, $newPaths);

                                                foreach ($toDelete as $path) {
                                                    $attachment = $record->attachments()->where('path', $path)->first();
                                                    if ($attachment) {
                                                        Storage::disk('public')->delete($path);
                                                        $attachment->delete();
                                                    }
                                                }
                                            }
                                            if ($state && $record) {
                                                foreach ($state as $filePath) {
                                                    if (!$record->attachments()->where('path', $filePath)->exists()) {
                                                        $record->attachments()->create([
                                                            'filename' => basename($filePath),
                                                            'path' => $filePath,
                                                            'mime' => Storage::disk('public')->mimeType($filePath),
                                                            'size' => Storage::disk('public')->size($filePath),
                                                        ]);
                                                    }
                                                }
                                            }
                                        })
                                        ->dehydrated(false)
                                        ->afterStateHydrated(function ($component, $state, $record) {
                                            if ($record && $record->attachments) {
                                                $component->state($record->attachments->pluck('path')->toArray());
                                            }
                                        }),
                                ]),
                        ])
                            ->skippable(),
                    ])
            ]);
    }
}
