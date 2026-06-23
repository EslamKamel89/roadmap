<?php

namespace App\Models;

use App\Enums\Feature\FeatureStatus;
use App\Enums\Feature\FeatureType;
use Database\Factories\FeatureFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'status', 'type', 'description', 'effort_in_days', 'priority', 'cost', 'target_delivery_date', 'delivered_at'])]
class Feature extends Model
{
    /** @use HasFactory<FeatureFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => FeatureStatus::class,
            'type' => FeatureType::class,
            'target_delivery_date' => 'date',
            'delivered_at' => 'date',
        ];
    }
}
