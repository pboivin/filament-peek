<?php

namespace Pboivin\FilamentPeek;

use Illuminate\Support\Str;

require_once './vendor/autoload.php';

define('BASE_URL', 'https://github.com/pboivin/filament-peek/blob/3.x/');

class DocFile
{
    public function __construct(
        public string $path,
        public string $prefix = '',
        public int $levels = 1,
    ) {}

    public function headings(): array
    {
        return collect(file($this->path))
            ->map(function ($line) {
                if ($this->levels >= 1 && (preg_match('/^# /', $line))) {
                    $title = $this->title($line);

                    return sprintf('- [%s](%s%s)', $title, $this->prefix, $this->path);
                }
                if ($this->levels >= 2 && (preg_match('/^## /', $line))) {
                    $title = $this->title($line);

                    return sprintf('    - [%s](%s%s#%s)', $title, $this->prefix, $this->path, Str::slug($title));
                }

                return false;
            })
            ->filter()
            ->all();
    }

    private function title(string $line): string
    {
        return preg_replace('/^#+ /', '', trim($line));
    }
}

function tocFiles(): array
{
    return [
        new DocFile('docs/configuration.md', prefix: BASE_URL, levels: 2),
        new DocFile('docs/page-previews.md', prefix: BASE_URL, levels: 2),
        new DocFile('docs/builder-previews.md', prefix: BASE_URL),
        new DocFile('docs/javascript-hooks.md', prefix: BASE_URL),
        new DocFile('docs/upgrade-guide.md', prefix: BASE_URL),
    ];
}

function footerFiles(): array
{
    return [
        new DocFile('docs/configuration.md', prefix: './'),
        new DocFile('docs/page-previews.md', prefix: './'),
        new DocFile('docs/builder-previews.md', prefix: './'),
        new DocFile('docs/javascript-hooks.md', prefix: './'),
        new DocFile('docs/upgrade-guide.md', prefix: './'),
    ];
}

function makeToc(): string
{
    $toc = collect(tocFiles())
        ->flatMap(fn ($f) => $f->headings());

    return implode("\n", ['<!-- BEGIN_TOC -->', '', ...$toc, '', '<!-- END_TOC -->']);
}

function makeFooter(): string
{
    $toc = collect(footerFiles())
        ->flatMap(fn ($f) => $f->headings())
        ->map(fn ($line) => preg_replace('#docs/#', '', $line));

    return implode("\n", ['<!-- BEGIN_TOC -->', '', ...$toc, '', '<!-- END_TOC -->']);
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

    return implode("\n", [...$readme, '']);
}

// Main README
file_put_contents('./README.md.new', updateMarkdown('./README.md', makeToc()));
unlink('./README.md');
rename('./README.md.new', './README.md');

// Docs index
file_put_contents('./docs/README.md.new', updateMarkdown('./docs/README.md', makeToc()));
unlink('./docs/README.md');
rename('./docs/README.md.new', './docs/README.md');

// Page footers
$footer = makeFooter();
foreach (footerFiles() as $file) {
    file_put_contents("./{$file->path}.new", updateMarkdown("./{$file->path}", $footer));
    unlink("./{$file->path}");
    rename("./{$file->path}.new", "./{$file->path}");
}

echo "\nDONE!\n\n";

exit(0);
