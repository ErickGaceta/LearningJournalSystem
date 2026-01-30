<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    /**
     * Determine whether the user can view any documents.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the document.
     */
    public function view(User $user, Document $document): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create documents.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the document.
     */
    public function update(User $user, Document $document): bool
    {
        // Only the owner can update
        // Adjust this based on your Document model structure
        // return $user->id === $document->user_id;
        
        // Or if you have roles:
        // return $user->id === $document->user_id || $user->isAdmin();
        
        // For now, allow all authenticated users
        return true;
    }

    /**
     * Determine whether the user can delete the document.
     */
    public function delete(User $user, Document $document): bool
    {
        // Only the owner can delete
        // Adjust this based on your Document model structure
        // return $user->id === $document->user_id;
        
        // Or if you have roles:
        // return $user->id === $document->user_id || $user->isAdmin();
        
        // For now, allow all authenticated users
        return true;
    }

    /**
     * Determine whether the user can restore the document.
     */
    public function restore(User $user, Document $document): bool
    {
        return $user->id === $document->user_id;
    }

    /**
     * Determine whether the user can permanently delete the document.
     */
    public function forceDelete(User $user, Document $document): bool
    {
        return $user->id === $document->user_id;
    }
}
