<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;

class MetaController extends Controller
{
    public function roles()
    {
        return Cache::remember('meta_roles', now()->addHours(24), function () {
            return Role::select('id', 'name')->orderBy('name')->get();
        });
    }
}

