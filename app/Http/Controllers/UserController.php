<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', User::class);
         $users = User::query()
             ->when($request->role, function($query, $role) {
                 return $query->where('role', $role);
             })
             ->when($request->search, function ($query, $search) {
                 $query->where(function ($q) use ($search) {
                     $q->where('firstName', 'like', "%{$search}%")
                         ->orWhere('lastName', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('username', 'like', "%{$search}%");
                 });
             })
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
        $request->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => [Rule::notIn([UserRole::ADMIN->value])],
        ], [
            'firstName.required' => 'กรอกชื่อจริง',
            'lastName.required' => 'กรอกนามสกุล',
            'email.required' => 'กรอกอีเมล',
            'username.required' => 'กรอกชื่อผู้ใช้',
            'password.required' => 'กรอกรหัสผ่าน'
        ]);

        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');
        $role = $request->input('role');

        if (empty($role) || empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            abort(400);
        }

        $user = new User();
        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->username = $username;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->role = $role;
        $user->save();
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', [
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        Gate::authorize('update', $user);
        return view('users.edit', [
            'user' => $user,
            'roles' => UserRole::cases(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);
        $validated = $request->validate([
            'firstName' => 'required|string|max:50',
            'lastName' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'username' => 'required|string|unique:users,username,' . $user->id,
            'role' => 'required',
            'password' => 'nullable|min:8|confirmed',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')->with('success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success');
    }
}
