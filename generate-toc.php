<?php

namespace Pboivin\FilamentPeek;

use Illuminate\Support\Str;

require_once './vendor/autoload.php';

define('BASE_URL', 'https://github.com/pboivin/filament-peek/blob/2.x/');

function generateToc(string $prefix, int $level = 2): string
{
    $toc = [];

    $files = [
        'docs/configuration.md',
        'docs/page-previews.md',
        'docs/builder-previews.md',
        'docs/javascript-hooks.md',
    ];

    foreach ($files as $file) {
        foreach (file($file) as $line) {
            if (preg_match('/^# /', $line)) {
                $title = preg_replace('/^# /', '', trim($line));
                $slug = Str::slug($title);
                $toc[] = "- [$title]({$prefix}{$file})";
            } elseif (preg_match('/^## /', $line)) {
                if ($level < 2) continue;
                $title = preg_replace('/^## /', '', trim($line));
                $slug = Str::slug($title);
                $toc[] = "    - [$title]({$prefix}{$file}#{$slug})";
            }
        }
    }

    return implode("\n", [
        '<!-- BEGIN_TOC -->',
        '',
        ...$toc,
        '',
        '<!-- END_TOC -->',
    ]);
}

function updateReadme(string $file, string $toc): string
{
    $readme = [];
    $in_toc = false;

    foreach (file($file) as $line) {
        if (preg_match('/BEGIN_TOC/', $line)) {
            $in_toc = true;

            continue;
        }

        if (preg_match('/END_TOC/', $line)) {
            $in_toc = false;
            $readme[] = $toc;

            continue;
        }

        if ($in_toc) {
            continue;
        }

        $readme[] = rtrim($line);
    }

    return implode("\n", [
        ...$readme,
        '',
    ]);
}

// Main README
file_put_contents('./README.new.md', updateReadme('./README.md', generateToc(BASE_URL)));
unlink('./README.md');
rename('./README.new.md', './README.md');

// Docs index
file_put_contents('./docs/README.new.md', updateReadme('./docs/README.md', generateToc(BASE_URL)));
unlink('./docs/README.md');
rename('./docs/README.new.md', './docs/README.md');

echo "\nDONE!\n\n";

exit(0);
