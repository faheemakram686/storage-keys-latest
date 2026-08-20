<?php


namespace App\Models\Core\Auth\Traits\Method;


use App\Exceptions\GeneralException;
use App\Models\Core\Status;
use App\Repositories\Core\Status\StatusRepository;

trait UserStatus
{
    public function isActive()
    {
        return $this->userStatusName() == 'status_active';
    }

    public function isInvited()
    {
        return $this->userStatusName() == 'status_invited';
    }

    public function isInactive()
    {
        return $this->userStatusName() == 'status_inactive';
    }

    /**
     * Resolve the statuses.name value via the status() relation.
     *
     * users.status is a separate boolean column with a getStatusAttribute
     * accessor, so $this->status never returns the Status model.
     */
    protected function userStatusName(): ?string
    {
        $status = $this->relationLoaded('status')
            ? $this->getRelation('status')
            : $this->status()->first();

        return optional($status)->name;
    }

    public function markAs($status)
    {
        throw_if(
            is_array($status),
            new GeneralException('Status can\'t be an array')
        );

        if ($status instanceof Status) {
            $status = $status->id;
        }elseif (is_string($status)) {
            $methodName = 'user'.ucfirst($status);
            $status = resolve(StatusRepository::class)->$methodName();
        }

        $this->fill([
            'status_id' => $status
        ]);

        $this->save();


    }
}