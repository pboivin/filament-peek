<?php

namespace Pboivin\FilamentPeek\Tests;

use Illuminate\Support\Facades\Config;

class TestCaseWithPreviewUrl extends TestCase
{
    protected function configurePackageProviders($app)
    {
        Config::set('filament-peek.useInternalPreviewUrl', true);

        TestPanelProvider::$should_load_plugin_assets = false;
    }
}
