<?php

use Pboivin\FilamentPeek\Tests\TestCase;
use Pboivin\FilamentPeek\Tests\TestCaseWithAssetsDisabled;
use Pboivin\FilamentPeek\Tests\TestCaseWithoutPreviewUrl;

uses(TestCase::class)->in('src/Unit');

uses(TestCase::class)->in('src/Feature');

uses(TestCase::class)->in('src/Integration/Base*.php');

uses(TestCaseWithAssetsDisabled::class)->in('src/Integration/WithAssetsDisabledTest.php');

uses(TestCaseWithoutPreviewUrl::class)->in('src/Integration/WithoutPreviewUrlTest.php');
