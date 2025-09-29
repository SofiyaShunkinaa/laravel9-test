<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->paginate(10);
        return view('dashboard.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('dashboard.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update($data);

        return redirect()->route('dashboard.users.index')->with('success', 'Role updated.');
    }
}
