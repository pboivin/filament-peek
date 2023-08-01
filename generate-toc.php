<?php

namespace Pboivin\FilamentPeek;

use Illuminate\Support\Str;

require_once './vendor/autoload.php';

define('BASE_URL', 'https://github.com/pboivin/filament-peek/blob/2.x/');

define('DOC_FILES', [
    'docs/configuration.md',
    'docs/page-previews.md',
    'docs/builder-previews.md',
    'docs/javascript-hooks.md',
    'docs/upgrade-guide.md',
]);

function generateToc(string $prefix): string
{
    $toc = [];

    foreach (DOC_FILES as $file) {
        foreach (file($file) as $line) {
            if (preg_match('/^# /', $line)) {
                $title = preg_replace('/^# /', '', trim($line));
                $slug = Str::slug($title);
                $toc[] = "- [$title]({$prefix}{$file})";
            } elseif (preg_match('/^## /', $line)) {
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

function generateFooter(string $prefix): string
{
    $toc = [];

    foreach (DOC_FILES as $file) {
        foreach (file($file) as $line) {
            $file = basename($file);

            if (preg_match('/^# /', $line)) {
                $title = preg_replace('/^# /', '', trim($line));
                $toc[] = "- [$title]({$prefix}{$file})";
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

function updateMarkdown(string $file, string $toc): string
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
file_put_contents('./README.md.new', updateMarkdown('./README.md', generateToc(BASE_URL)));
unlink('./README.md');
rename('./README.md.new', './README.md');

// Docs index
file_put_contents('./docs/README.md.new', updateMarkdown('./docs/README.md', generateToc(BASE_URL)));
unlink('./docs/README.md');
rename('./docs/README.md.new', './docs/README.md');

// Page footers
$footer = generateFooter('./');
foreach (DOC_FILES as $file) {
    file_put_contents("./{$file}.new", updateMarkdown("./{$file}", $footer));
    unlink("./{$file}");
    rename("./{$file}.new", "./{$file}");
}

echo "\nDONE!\n\n";

exit(0);
