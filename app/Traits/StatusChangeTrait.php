<?php

namespace App\Traits;

trait StatusChangeTrait
{
    public function changeStatus($status)
    {
        $this->status = $status;
        $this->save();
    }
}
