<?php

namespace App\Policies;

class FeaturePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function update()
    {
        return true;
    }

    public function viewAny()
    {
        return true;
    }

    public function deleteAny()
    {
        return true;
    }

    public function view()
    {
        return true;
    }

    public function create()
    {
        return true;
    }
}
