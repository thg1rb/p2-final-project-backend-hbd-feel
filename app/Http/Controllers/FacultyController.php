<?php

namespace App\Http\Controllers;

use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FacultyController extends Controller
{
    public function index(Request $request)
    {
        // เริ่มต้น Query
        $query = Faculty::query();

        // 1. กรองเฉพาะวิทยาเขตที่ตรงกับ User ที่ Login อยู่
        // สมมติว่า $user->campus เก็บค่าเป็น string หรือ enum ที่ตรงกับ faculty->campus
        $userCampus = Auth::user()->campus;
        $query->where('campus', $userCampus);

        // 2. Search logic (ต้องใช้ Grouping เพื่อไม่ให้ OrWhere ไปดึงวิทยาเขตอื่นมา)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('campus', 'like', "%{$search}%");
            });
        }

        // 3. Sorting logic
        $sorts = $request->input('sorts', []);
        if (!empty($sorts)) {
            foreach ($sorts as $column => $direction) {
                $query->orderBy($column, $direction);
            }
        } else {
            $query->latest();
        }

        $faculties = $query->paginate(10)->withQueryString();

        return view('faculties.index', compact('faculties'));
    }

    public function create()
    {
        return view('faculties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Faculty::create([
            'name' => $validated['name'],
            'campus' => Auth::user()->campus,
        ]);

        return redirect()->route('faculties.index')->with('success', 'เพิ่มคณะเรียบร้อยแล้ว');
    }

    public function show(Faculty $faculty)
    {
        return view('faculties.show', compact('faculty'));
    }

    public function edit(Faculty $faculty)
    {
        return view('faculties.edit', compact('faculty'));
    }

    public function update(Request $request, Faculty $faculty)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('faculties')->where(function ($query) {
                    return $query->where('campus', Auth::user()->campus);
                })->ignore($faculty->id),
            ],
        ]);

        $faculty->update($validated);
        return redirect()->route('faculties.index')->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
    }

    public function destroy(Faculty $faculty)
    {
        $faculty->delete();
        return redirect()->route('faculties.index')->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
}
