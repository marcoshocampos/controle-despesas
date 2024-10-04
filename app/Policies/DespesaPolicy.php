<?php

namespace App\Policies;

use App\Models\Despesa;
use App\Models\User;

class DespesaPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Despesa $despesa): bool
    {       
        return $user->id === $despesa->user_id;
    }
    
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return !is_null($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Despesa $despesa): bool
    {
        return $user->id === $despesa->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Despesa $despesa): bool
    {
        return $user->id === $despesa->user_id;
    }
}
