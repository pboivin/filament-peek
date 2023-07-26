<?php

use Illuminate\Support\Str;

require_once './vendor/autoload.php';

function generateToc(): string
{
    $toc = [];

    $files = [
        './docs/configuration.md',
        './docs/page-previews.md',
        './docs/builder-previews.md',
        './docs/javascript-hooks.md',
    ];

    foreach ($files as $file) {
        foreach (file($file) as $line) {
            if (preg_match('/^# /', $line)) {
                $title = preg_replace('/^# /', '', trim($line));
                $slug = Str::slug($title);
                $toc[] = "- [$title]($file)";
            } elseif (preg_match('/^## /', $line)) {
                $title = preg_replace('/^## /', '', trim($line));
                $slug = Str::slug($title);
                $toc[] = "    - [$title]($file#$slug)";
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

function updateReadme($toc): string
{
    $readme = [];
    $in_toc = false;

    foreach (file('./README.md') as $line) {
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

file_put_contents('./README.new.md', updateReadme(generateToc()));
unlink('./README.md');
rename('./README.new.md', './README.md');

echo "\nDONE!\n\n";

exit(0);
