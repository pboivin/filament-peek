<?php

use Pboivin\FilamentPeek\Tests\TestCase;
use Pboivin\FilamentPeek\Tests\TestCaseAssetsDisabled;
use Pboivin\FilamentPeek\Tests\TestCaseWithPreviewUrl;

uses(TestCase::class)->in('src/Unit');

uses(TestCase::class)->in('src/Feature');

uses(TestCase::class)->in('src/Integration/Base*.php');

uses(TestCaseAssetsDisabled::class)->in('src/Integration/AssetsDisabledTest.php');

uses(TestCaseWithPreviewUrl::class)->in('src/Integration/WithPreviewUrlTest.php');
