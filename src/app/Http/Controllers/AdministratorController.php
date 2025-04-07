<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class AdministratorController extends Controller
{
    public function create()
    {
        $roles = Role::all();
        return view('administrator.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $user = new User($request->only(['name', 'email', 'password']));
        $user->role_id = $request->role_id;
        $user->save();

        return redirect()->route('administrator.index');
    }

    public function index()
    {
        $users = User::all();
        return view('administrator.index', compact('users'));
    }
}
