<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StoryPart;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;

class StoryPartPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\StoryPart  $storyPart
     * @return mixed
     */
    public function view(User $user, StoryPart $storyPart)
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\StoryPart  $storyPart
     * @return mixed
     */
    public function update(User $user, StoryPart $storyPart)
    {
        return $user->id == $storyPart->created_by
            ? Response::allow()
            : Response::deny('You do not own this story part.', HTTPResponse::HTTP_FORBIDDEN);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\StoryPart  $storyPart
     * @return mixed
     */
    public function delete(User $user, StoryPart $storyPart)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\StoryPart  $storyPart
     * @return mixed
     */
    public function restore(User $user, StoryPart $storyPart)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\StoryPart  $storyPart
     * @return mixed
     */
    public function forceDelete(User $user, StoryPart $storyPart)
    {
        return false;
    }
}
