<?php

namespace App\Filament\Resources\Features\Schemas;

use App\Enums\Feature\FeatureStatus;
use App\Enums\Feature\FeatureType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\Slider\Enums\PipsMode;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class FeatureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Feature Tab')
                    ->vertical()
                    ->tabs([
                        Tab::make('Feature Details')
                            ->schema(self::getFeatureDetailsSchema()),
                        Tab::make('Effort and Cost')
                            ->schema(self::getEffortAndCostSchema()),
                        Tab::make('Milestones')->schema(self::getMilestonesSchema()),
                        Tab::make('Stages')->schema(self::getStagesSchema()),
                    ])
                    ->columnSpanFull()->columns(2),

            ]);
    }

    protected static function getFeatureDetailsSchema(): array
    {
        return [
            TextInput::make('name')
                ->required()
                ->columnSpanFull(),
            Select::make('status')
                ->required()
                ->enum(FeatureStatus::class)
                ->options(FeatureStatus::class)
                ->searchable()
                ->default(FeatureStatus::Proposed->value),
            ToggleButtons::make('type')
                ->hiddenLabel()
                ->required()
                ->enum(FeatureType::class)
                ->default(FeatureType::Feature->value)
                ->inline()
                ->extraFieldWrapperAttributes([
                    'class' => 'pt-7',
                ]),

            DatePicker::make('target_delivery_date')
                ->rules([
                    function (Get $get) {
                        return Rule::requiredIf(
                            $get('status') === FeatureStatus::Planned || $get('status') === FeatureStatus::InProgress
                        );
                    },
                ])
                ->visibleJs(<<<'JS'
                        $get('status') === 'Planned' || $get('status') === 'In Progress'
                    JS),

            DatePicker::make('delivered_at')
                ->visibleJs(<<<'JS'
                            $get('status') === 'Completed'
                        JS),
            Slider::make('priority')
                ->live(debounce: '900')
                ->label(fn (Get $get) => 'Priority ('.$get('priority').')')
                ->required()
                ->step(1)
                ->pips(PipsMode::Steps)
                ->minValue(1)
                ->maxValue(10)
                ->fillTrack()
                ->default(1)
                ->columnSpanFull(),
            RichEditor::make('description')
                ->extraInputAttributes([
                    'class' => 'min-h-[200px]',
                ]),
        ];
    }

    protected static function getEffortAndCostSchema(): array
    {
        return [
            TextInput::make('effort_in_days')
                ->required()
                ->afterStateUpdatedJs(<<<'JS'
                        const isHighCost = $get('is_high_cost');
                        if(isHighCost){
                            $set('cost' , $state * 1500);
                        } else {
                            $set('cost' , $state * 1000);
                        }
                    JS)
                ->numeric()
                ->default(0),
            Toggle::make('is_high_cost')
                ->dehydrated(false)
                ->afterStateUpdatedJs(<<<'JS'
                    const isHighCost = $state ;
                    const effort = $get('effort_in_days');
                    if(isHighCost){
                        $set('cost' , effort * 1500);
                    }else {
                        $set('cost' , effort * 1000);
                    }
                    JS)
                ->extraFieldWrapperAttributes([
                    'class' => 'p-7',
                ])
                ->default(false),
            TextInput::make('cost')
                ->required()
                ->numeric()
                ->default(0)
                ->prefix('$'),
        ];
    }

    protected static function getMilestonesSchema(): array
    {
        return [
            Repeater::make('milestones')
                ->label('Milestones')
                ->table([
                    TableColumn::make('Title'),
                    // ->hiddenHeaderLabel(),
                    TableColumn::make('Due Date'),
                    // ->hiddenHeaderLabel(),
                    TableColumn::make('Completed'),
                    // ->hiddenHeaderLabel(),
                ])
                ->schema([
                    TextInput::make('title')
                        ->required(),
                    DatePicker::make('due_date')
                        ->required(),
                    Toggle::make('is_completed')
                        ->label('Completed')
                        ->required()
                        ->default(false),
                ])
                ->columnSpanFull()
                ->minItems(1)
                ->compact(),
        ];
    }

    protected static function getStagesSchema(): array
    {
        return [
            Repeater::make('stages')
                ->label('Stages')
                ->relationship('stages')
                ->table([
                    TableColumn::make('Title'),
                    TableColumn::make('Due Date'),
                    TableColumn::make('Completed'),
                ])
                ->schema([
                    TextInput::make('title')
                        ->required(),
                    DatePicker::make('due_date')
                        ->required(),
                    Toggle::make('is_completed')
                        ->label('Completed')
                        ->required()
                        ->default(false),
                ])
                ->columnSpanFull()
                ->minItems(1)
                ->compact(),
        ];
    }
}
