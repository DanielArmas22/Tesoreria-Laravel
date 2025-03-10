<?php

namespace App\Policies;

use App\Models\Estudiante;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EstudiantePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Estudiante $estudiante): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Estudiante $estudiante): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Estudiante $estudiante): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Estudiante $estudiante): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Estudiante $estudiante): bool
    {
        //
    }
    public function edit(User $user, Estudiante $estudiante)
    {
        // dd($user->findEstudiante(43));
        if ($user->hasRole('admin') || $user->hasRole('director') || $user->hasRole('secretario')) {
            return Response::allow();
        }

        if ($user->hasRole('padre') && $user->findEstudiante($estudiante->idEstudiante)) {
            return Response::allow();
        }

        return Response::deny('No tienes permiso para editar este estudiante.');
    }
}
