<?php

namespace App\Enums\Feature;

use App\Traits\UseValueAsLabel;
use BackedEnum;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Illuminate\Contracts\Support\Htmlable;

enum FeatureType: string implements HasColor, HasIcon, HasLabel
{
    use UseValueAsLabel;

    case Feature = 'Feature';
    case BugFix = 'Bug';

    public function getIcon(): string|BackedEnum|Htmlable|null
    {
        return match ($this) {
            self::Feature => 'heroicon-o-sparkles',
            self::BugFix => 'heroicon-o-bug-ant',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Feature => 'success',
            self::BugFix => 'danger',
        };
    }
}
