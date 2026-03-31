<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            ->where('campus', auth()->user()->campus)
            ->when($request->role, function ($query, $role) {
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

        return view(
            'users.index',
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
        return view(
            'users.create',
            [
                'roles' => UserRole::cases(),
                'faculties' => Faculty::where('campus', auth()->user()->campus)->get(),
                'departments' => Department::all(),
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', User::class);
        $role = $request->input('role');

        // Build validation rules based on role
        $validationRules = [
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required'],
        ];

        // Add role-specific validation rules
        switch ($role) {
            case 'NISIT': // นิสิต
                $validationRules['student_id'] = ['required', 'string', 'max:50', 'unique:users,student_id'];
                $validationRules['faculty'] = ['required', 'exists:faculties,id'];
                $validationRules['department'] = ['required', 'exists:departments,id'];
                break;
            case 'DEPT_HEAD': // หัวหน้าภาค
                $validationRules['faculty'] = ['required', 'exists:faculties,id'];
                $validationRules['department'] = ['required', 'exists:departments,id'];
                break;
            case 'ASSO_DEAN': // รองคณบดี
            case 'DEAN': // คณบดี
                $validationRules['faculty'] = ['required', 'exists:faculties,id'];
                break;
            case 'BOARD': // คณะกรรมการ
            case 'NISIT_DEV': // กองพัฒนานิสิต
                // No faculty or department required
                break;
        }

        $request->validate($validationRules, [
            'firstName.required' => 'กรอกชื่อจริง',
            'lastName.required' => 'กรอกนามสกุล',
            'email.required' => 'กรอกอีเมล',
            'username.required' => 'กรอกชื่อผู้ใช้',
            'password.required' => 'กรอกรหัสผ่าน',
            'student_id.required' => 'กรอกรหัสนิสิต',
            'faculty.required' => 'เลือกคณะ',
            'department.required' => 'เลือกภาควิชา',
            'student_id.unique' => 'รหัสนิสิตนี้ถูกใช้แล้ว',
        ]);

        $firstName = $request->input('firstName');
        $lastName = $request->input('lastName');
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');
        $faculty_id = $request->input('faculty');
        $department_id = $request->input('department');
        $student_id = $request->input('student_id');
        $campus = auth()->user()->campus;

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
        $user->faculty_id = $faculty_id ?: null;
        $user->department_id = $department_id ?: null;
        $user->student_id = $student_id ?: null;
        $user->campus = $campus ?: null;
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
            'faculties' => Faculty::where('campus', auth()->user()->campus)->get(),
            'departments' => Department::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        Gate::authorize('update', $user);
        $role = $request->input('role');

        // Build validation rules based on role
        $validationRules = [
            'firstName' => ['required', 'string', 'max:50'],
            'lastName' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'username' => ['required', 'string', 'unique:users,username,' . $user->id],
            'password' => ['nullable', 'min:8', 'confirmed'],
            'role' => ['required'],
        ];

        // Add role-specific validation rules
        switch ($role) {
            case 'NISIT': // นิสิต
                $validationRules['student_id'] = ['required', 'string', 'max:50', 'unique:users,student_id,' . $user->id];
                $validationRules['faculty'] = ['required', 'exists:faculties,id'];
                $validationRules['department'] = ['required', 'exists:departments,id'];
                break;
            case 'DEPT_HEAD': // หัวหน้าภาค
                $validationRules['faculty'] = ['required', 'exists:faculties,id'];
                $validationRules['department'] = ['required', 'exists:departments,id'];
                break;
            case 'ASSO_DEAN': // รองคณบดี
            case 'DEAN': // คณบดี
                $validationRules['faculty'] = ['required', 'exists:faculties,id'];
                break;
            case 'BOARD': // คณะกรรมการ
            case 'NISIT_DEV': // กองพัฒนานิสิต
                // No faculty or department required
                break;
        }

        $validated = $request->validate($validationRules, [
            'firstName.required' => 'กรอกชื่อจริง',
            'lastName.required' => 'กรอกนามสกุล',
            'email.required' => 'กรอกอีเมล',
            'username.required' => 'กรอกชื่อผู้ใช้',
            'password.required' => 'กรอกรหัสผ่าน',
            'student_id.required' => 'กรอกรหัสนิสิต',
            'faculty.required' => 'เลือกคณะ',
            'department.required' => 'เลือกภาควิชา',
            'student_id.unique' => 'รหัสนิสิตนี้ถูกใช้แล้ว',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
        }

        $user->firstName = $validated['firstName'];
        $user->lastName = $validated['lastName'];
        $user->email = $validated['email'];
        $user->username = $validated['username'];
        $user->role = $validated['role'];
        $user->faculty_id = $request->input('faculty') ?: null;
        $user->department_id = $request->input('department') ?: null;
        $user->student_id = $request->input('student_id') ?: null;
        $user->save();

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
