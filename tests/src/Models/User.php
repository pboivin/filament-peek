<?php

namespace Pboivin\FilamentPeek\Tests\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Pboivin\FilamentPeek\Tests\Database\Factories\UserFactory;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
