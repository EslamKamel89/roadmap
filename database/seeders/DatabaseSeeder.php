<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Feature;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'is_admin' => true,
        ]);
        $users = User::factory(25)->create();
        $features = Feature::factory(50)->create();
        foreach ($features as $feature) {
            $comment = Comment::factory()->create([
                'feature_id' => $feature->id,
                'user_id' => $users->random(1)->first()->id,
            ]);
            foreach ($users as $user) {
                Vote::factory()->create([
                    'user_id' => $user->id,
                    'feature_id' => $feature->id,
                ]);
            }
        }
    }
}
