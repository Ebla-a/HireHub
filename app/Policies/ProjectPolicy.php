<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;


class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return true;
    }


    /**
     * only client can create new project
     */
    public function create(User $user): bool
    {
        return $user->role === 'client';
    }

    /**
     *  only the user who have the project can update it
     */
    public function update(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    /**
     * only the user who have the project can delete it
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    /**
     * 
     */
    public function acceptOffer(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }
}
    

