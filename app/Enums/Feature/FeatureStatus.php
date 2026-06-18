<?php

namespace App\Enums\Feature;

use App\Traits\UseValueAsLabel;
use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum FeatureStatus: string implements HasLabel, HasColor, HasIcon {
    use UseValueAsLabel;

    case Proposed = 'Proposed';
    case Planned = 'Planned';
    case InProgress = 'In Progress';
    case Completed = 'Completed';

    public function getIcon(): string|BackedEnum|Htmlable|null {
        return match ($this) {
            self::Proposed => 'heroicon-o-light-bulb',
            self::Planned => 'heroicon-o-calendar',
            self::InProgress => 'heroicon-o-arrow-path',
            self::Completed => 'heroicon-o-check-circle',
        };
    }

    public function getColor(): string|array|null {
        return match ($this) {
            self::Proposed => 'info',
            self::Planned => 'warning',
            self::InProgress => 'primary',
            self::Completed => 'success',
        };
    }
}
