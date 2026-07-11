<?php

namespace App\Filament\Resources\Features\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FeatureInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Feature Information')
                    ->columns(4)
                    ->columnSpanFull()
                    ->schema(self::getFeatureInformationSchema()),

                Section::make('Dates')
                    ->columns(4)
                    ->columnSpanFull()
                    ->schema(self::getDatesSchema()),
                Section::make('Description')
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('description')
                            ->hiddenLabel()
                            ->html()
                            ->prose()
                            ->placeholder('-')
                            ->columnSpanFull(),
                    ]),

                Section::make('Milestones')
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema(self::getMilestoneSchema()),
                Section::make('Stage')
                    ->columns(1)
                    ->columnSpanFull()
                    ->schema(self::getStagesSchema()),

            ]);
    }

    public static function getFeatureInformationSchema()
    {
        return [
            TextEntry::make('name'),
            TextEntry::make('status')->badge(),
            TextEntry::make('type')->badge(),
            TextEntry::make('effort_in_days')
                ->numeric(),
            TextEntry::make('priority')
                ->numeric(),
            TextEntry::make('cost')
                ->money(),

        ];
    }

    public static function getDatesSchema()
    {
        return [
            TextEntry::make('target_delivery_date')
                ->date()
                ->placeholder('-'),
            TextEntry::make('delivered_at')
                ->date()
                ->placeholder('-'),
            TextEntry::make('created_at')
                ->dateTime()
                ->placeholder('-'),
            TextEntry::make('updated_at')
                ->dateTime()
                ->placeholder('-'),
        ];
    }

    public static function getMilestoneSchema()
    {
        return [
            RepeatableEntry::make('milestones')
                ->hiddenLabel()
                ->table([
                    TableColumn::make('Title'),
                    TableColumn::make('Due Date'),
                    TableColumn::make('Completed'),
                ])
                ->schema([
                    TextEntry::make('title'),
                    TextEntry::make('due_date'),
                    IconEntry::make('is_completed')->boolean(),
                ])->columnSpanFull(),
        ];
    }

    public static function getStagesSchema()
    {
        return [
            RepeatableEntry::make('stages')
                ->hiddenLabel()
                ->table([
                    TableColumn::make('Title'),
                    TableColumn::make('Due Date'),
                    TableColumn::make('Completed'),
                ])
                ->schema([
                    TextEntry::make('title'),
                    TextEntry::make('due_date'),
                    IconEntry::make('is_completed')->boolean(),
                ])->columnSpanFull(),
        ];
    }
}
