<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

use App\Repositories\SysRoleRepository;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_roleRepository = new SysRoleRepository;
    }

    /**
     * Determine whether the user can view the role.
     *
     * @param  \App\User  $user
     * @param  \App\Model\System\Role  $role
     * @return mixed
     */
    public function index(User $user, $module)
    {
        $permission = "index";

        $role       = $this->_roleRepository->getByModuleTask($module, $permission, $user->group_id);

        if ($role) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the role.
     *
     * @param  \App\User  $user
     * @param  \App\Model\System\Role  $role
     * @return mixed
     */
    public function show(User $user, $module)
    {
        $permission = "view";

        $role       = $this->_roleRepository->getByModuleTask($module, $permission, $user->group_id);

        if ($role) {
            return true;
        }
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, $module)
    {
        $permission = "create";

        $role       = $this->_roleRepository->getByModuleTask($module, $permission, $user->group_id);

        if ($role) {
            return true;
        }
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function store(User $user, $module)
    {
        $permission = "create";

        $role       = $this->_roleRepository->getByModuleTask($module, $permission, $user->group_id);

        if ($role) {
            return true;
        }
    }

    /**
     * Determine whether the user can edit roles.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function edit(User $user, $module)
    {
        $permission = "edit";

        $role       = $this->_roleRepository->getByModuleTask($module, $permission, $user->group_id);

        if ($role) {
            return true;
        }
    }

    /**
     * Determine whether the user can update the role.
     *
     * @param  \App\User  $user
     * @param  \App\Model\System\Role  $role
     * @return mixed
     */
    public function update(User $user, $module)
    {
        $permission = "edit";

        $role       = $this->_roleRepository->getByModuleTask($module, $permission, $user->group_id);

        if ($role) {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param  \App\User  $user
     * @param  \App\Model\System\Role  $role
     * @return mixed
     */
    public function destroy(User $user, $module)
    {
        $permission = "delete";

        $role       = $this->_roleRepository->getByModuleTask($module, $permission, $user->group_id);

        if ($role) {
            return true;
        }
    }
}
