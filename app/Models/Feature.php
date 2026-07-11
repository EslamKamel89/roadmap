<?php

namespace App\Models;

use App\Enums\Feature\FeatureStatus;
use App\Enums\Feature\FeatureType;
use Database\Factories\FeatureFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'status', 'type', 'description', 'milestones', 'effort_in_days', 'priority', 'cost', 'target_delivery_date', 'delivered_at'])]
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
            'milestones' => 'array',
        ];
    }

    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function approvedComments(): HasMany
    {
        return $this->comments()->where('is_approved', true);
    }

    public function pendingComments(): HasMany
    {
        return $this->comments()->where('is_approved', false);
    }

    public function voters(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'votes')->withTimestamps();
    }
}
