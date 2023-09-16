<?php

use Pboivin\FilamentPeek\Tests\TestCase;
use Pboivin\FilamentPeek\Tests\TestCaseAssetsDisabled;

uses(TestCase::class)->in('src/Unit');

uses(TestCase::class)->in('src/Feature/IntegrationTest.php');

uses(TestCaseAssetsDisabled::class)->in('src/Feature/AssetsDisabledTest.php');
