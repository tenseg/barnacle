<?php

namespace Tenseg\Barnacle\Tests;

use Tenseg\Barnacle\ServiceProvider;
use Statamic\Testing\AddonTestCase;

abstract class TestCase extends AddonTestCase
{
    protected string $addonServiceProvider = ServiceProvider::class;
}
