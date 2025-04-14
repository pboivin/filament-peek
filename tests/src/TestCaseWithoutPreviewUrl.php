<?php

namespace Pboivin\FilamentPeek\Tests;

use Illuminate\Support\Facades\Config;

class TestCaseWithoutPreviewUrl extends TestCase
{
    protected function configurePackageProviders($app)
    {
        Config::set('filament-peek.internalPreviewUrl.enabled', false);

        TestPanelProvider::$should_load_plugin_assets = false;
    }
}
