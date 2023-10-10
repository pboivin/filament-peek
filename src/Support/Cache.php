<?php

namespace Pboivin\FilamentPeek\Support;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class Cache
{
    public function createPreviewToken(): string
    {
        return md5('preview-'.Auth::user()->getAuthIdentifier().Config::get('app.key', ''));
    }
}
