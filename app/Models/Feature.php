<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(["name", "status", "type", "description", "effort_in_days", "priority", "cost", "target_delivery_date", "delivered_at"])]
class Feature extends Model {
    /** @use HasFactory<\Database\Factories\FeatureFactory> */
    use HasFactory;
}
