<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\UserLogin;

abstract class TestCase extends BaseTestCase
{
    use UserLogin;

    protected function setUp():void
    {
        parent:setUp();

        $this->withoutVite();
    }
}
