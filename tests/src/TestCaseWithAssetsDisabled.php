<?php

namespace Pboivin\FilamentPeek\Tests;

class TestCaseWithAssetsDisabled extends TestCase
{
    protected function configurePackageProviders($app)
    {
        TestPanelProvider::$should_load_plugin_assets = false;
    }
}
