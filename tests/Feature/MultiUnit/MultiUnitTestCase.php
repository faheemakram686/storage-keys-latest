<?php

namespace Tests\Feature\MultiUnit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\CreatesApplication;

/**
 * QA base: uses DatabaseTransactions against the real app DB
 * (no RefreshDatabase — this project has heavy legacy migrations).
 */
abstract class MultiUnitTestCase extends BaseTestCase
{
    use CreatesApplication;
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
    }
}
