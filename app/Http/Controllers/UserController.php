<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $users = User::query()
             ->when($request->role, function($query, $role) {
                 return $query->where('role', $role);
             } )
             ->where('role', '!=', UserRole::ADMIN)
             ->paginate(15)
             ->withQueryString();

         return view('users.index',
             [
                'users' => $users,
                 'roles' => UserRole::cases(),
             ]
         );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', User::class);
        return view('users.create',
            [
                'roles' => UserRole::cases(),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', User::class);
        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $email = $request->input('email');
        $password = $request->input('password');
        $role = $request->input('role');

        $user = new User();
        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->role = $role;
        $user->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
