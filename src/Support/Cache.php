<?php

namespace Pboivin\FilamentPeek\Support;

use Illuminate\Support\Facades\Auth;

class Cache
{
    public function createPreviewToken(): string
    {
        return md5('preview-'.Auth::user()->getAuthIdentifier().(Auth::user()->password ?? ''));
    }
}
