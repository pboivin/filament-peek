<?php

use Pboivin\FilamentPeek\Tests\TestCase;
use Pboivin\FilamentPeek\Tests\TestCaseWithAssetsDisabled;
use Pboivin\FilamentPeek\Tests\TestCaseWithPreviewUrl;

uses(TestCase::class)->in('src/Unit');

uses(TestCase::class)->in('src/Feature');

uses(TestCase::class)->in('src/Integration/Base*.php');

uses(TestCaseWithAssetsDisabled::class)->in('src/Integration/WithAssetsDisabledTest.php');

uses(TestCaseWithPreviewUrl::class)->in('src/Integration/WithPreviewUrlTest.php');
