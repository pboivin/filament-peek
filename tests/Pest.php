<?php

use Pboivin\FilamentPeek\Tests\TestCase;
use Pboivin\FilamentPeek\Tests\TestCaseAssetsDisabled;

uses(TestCase::class)->in('src/Unit');

uses(TestCase::class)->in('src/Integration/Base*.php');

uses(TestCaseAssetsDisabled::class)->in('src/Integration/AssetsDisabledTest.php');
