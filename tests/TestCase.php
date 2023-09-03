<?php

namespace Tests;

use Tests\Support\ExceptionHandling;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations, ExceptionHandling;

    public function setUp() : void
    {
        parent::setUp();

        $this->disableExceptionHandling();
    }
}
