<?php

namespace App\Policies;

use App\Models\User;

class FeaturePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update(?User $user)
    {
        return $user->is_admin;
    }

    public function viewAny()
    {
        return true;
    }

    public function delete(?User $user)
    {
        return $user->is_admin;
    }

    public function deleteAny(?User $user)
    {
        return $user->is_admin;
    }

    public function view()
    {
        return true;
    }

    public function create(?User $user)
    {
        return $user->is_admin;
    }
}
