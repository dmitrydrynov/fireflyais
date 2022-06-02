<?php

declare(strict_types=1);

namespace FireflyIII\Models;

class Permission extends \Spatie\Permission\Models\Permission
{
    protected $guard_name = '*';

    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = $this->guard_name;
        parent::__construct($attributes);
    }
}