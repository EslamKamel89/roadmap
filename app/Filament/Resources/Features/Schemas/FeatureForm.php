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
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class FeatureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('status')
                    ->required()
                    ->enum(FeatureStatus::class)
                    ->searchable()
                    ->default(FeatureStatus::Proposed->value),

                ToggleButtons::make('type')
                    ->required()
                    ->enum(FeatureType::class)
                    ->default(FeatureType::Feature->value)
                    ->hiddenLabel()
                    ->inline(),

                RichEditor::make('description'),
                TextInput::make('effort_in_days')
                    ->required()
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
                DatePicker::make('target_delivery_date'),
                DatePicker::make('delivered_at'),
            ]);
    }
}
