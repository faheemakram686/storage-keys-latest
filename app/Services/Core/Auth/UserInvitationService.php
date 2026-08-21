<?php


namespace App\Services\Core\Auth;


use App\Models\Core\Auth\User;
use App\Models\Core\Status;
use App\Services\Core\BaseService;

class UserInvitationService extends BaseService
{
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function invite($email, $roles = [])
    {
        $roles = count($roles) ? $roles : request()->get('roles');

        $this->create($email)->assignRoles($roles);

        $this->model->invite();

        return $this->model;
    }

    public function create($email, array $attributes = [])
    {
        $status = Status::findByNameAndType('status_invited')->id;

        $invitation_token = base64_encode($email.'-invitation-from-us');

        $this->model->fill(array_merge([
                'email' => $email,
                'status_id' => $status,
                'invitation_token' => $invitation_token
            ], $attributes))->save();

        return $this;
    }

    public function assignRoles($roles)
    {
        $roles = $this->normalizeInviteRoles($roles);

        if (count($roles)) {
            resolve(UserRoleSyncService::class)->syncStaffRoles($this->model, $roles);
        }

        return $this;
    }

    /**
     * Accept a list of role IDs, or the wrapped shape from getAttributes('roles').
     *
     * @param  mixed  $roles
     * @return array<int, mixed>
     */
    protected function normalizeInviteRoles($roles): array
    {
        if ($roles instanceof \Illuminate\Support\Collection) {
            $roles = $roles->all();
        }

        if (!is_array($roles)) {
            return $roles ? [$roles] : [];
        }

        // HasAttrs::getAttributes('roles') returns ['roles' => [1, 2]] rather than [1, 2].
        if (array_key_exists('roles', $roles) && !isset($roles[0])) {
            $roles = $roles['roles'];

            if (!is_array($roles)) {
                return $roles ? [$roles] : [];
            }
        }

        return $roles;
    }

    public function detachRoles()
    {
        $sync = resolve(UserRoleSyncService::class);
        $sync->detachStaffRoles($this->model);
        $sync->clearUserRoleCache($this->model);

        return $this;
    }

    public function delete()
    {
        $this->model->forceDelete();

        return true;
    }
}
