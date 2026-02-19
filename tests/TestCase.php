<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Concerns\TestHelpers;

abstract class TestCase extends BaseTestCase
{
    use TestHelpers;
}
