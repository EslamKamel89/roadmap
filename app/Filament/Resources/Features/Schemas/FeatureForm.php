<?php

namespace App\Filament\Resources\Features\Schemas;

use App\Enums\Feature\FeatureStatus;
use App\Enums\Feature\FeatureType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\Slider\Enums\PipsMode;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class FeatureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                ToggleButtons::make('type')
                    ->required()
                    ->enum(FeatureType::class)
                    ->default(FeatureType::Feature->value)
                    ->inline(),

                Select::make('status')
                    ->required()
                    // ->live()
                    ->enum(FeatureStatus::class)
                    ->options(FeatureStatus::class)
                    ->searchable()
                    ->default(FeatureStatus::Proposed->value),

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

                RichEditor::make('description'),
                TextInput::make('effort_in_days')
                    ->required()
                    // ->live(debounce: 500)
                    // ->afterStateUpdated(function ($state, Get $get, Set $set) {
                    //     $effort = (int) $state;
                    //     $isHightCost = $get('is_high_cost');
                    //     if ($isHightCost) {
                    //         $set('cost', $effort * 1500);
                    //     } else {
                    //         $set('cost', $effort * 1000);
                    //     }
                    // })
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
                Slider::make('priority')
                    ->live(debounce: '900')
                    ->label(fn (Get $get) => 'Priority ('.$get('priority').')')
                    ->required()
                    ->step(1)
                    ->pips(PipsMode::Steps)
                    ->minValue(1)
                    ->maxValue(10)
                    ->fillTrack()
                    ->default(1),
                TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),
                Toggle::make('is_high_cost')
                    // ->live()
                    // ->afterStateUpdated(function ($state, Get $get, Set $set) {
                    //     $effort = (int) $get('effort_in_days');
                    //     if ($state) {
                    //         $set('cost', $effort * 1500);
                    //     } else {
                    //         $set('cost', $effort * 1000);
                    //     }
                    // })
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
                    ->default(false),
                DatePicker::make('delivered_at'),
            ]);
    }
}
