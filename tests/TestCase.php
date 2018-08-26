<?php

namespace Tests;

use App\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function loginAdmin()
    {
        $user = factory(User::class)->create();

        Config::push('custom.administrators', $user->email);

        $this->actingAs($user);
    }
}
