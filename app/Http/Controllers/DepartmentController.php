<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        // 1. ดึงเฉพาะภาควิชาที่คณะอยู่ในวิทยาเขตเดียวกับ User (เงื่อนไขหลัก)
        $query = Department::whereHas('faculty', function ($q) {
            $q->where('campus', Auth::user()->campus);
        })->with('faculty');

        // 2. Search Logic (ครอบด้วย Group Function เพื่อไม่ให้หลุดข้ามวิทยาเขต)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('faculty', function ($f) use ($search) {
                        $f->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // 3. Sorting Logic (รองรับ Multi-column sort จาก Blade)
        $sorts = $request->input('sorts', []);
        if (is_array($sorts) && !empty($sorts)) {
            foreach ($sorts as $column => $direction) {
                // เช็คชื่อคอลัมน์ที่อนุญาตให้เรียง (White-list)
                if (in_array($column, ['name', 'faculty_id'])) {
                    $query->orderBy($column, $direction);
                }
            }
        } else {
            // ถ้าไม่มีการ sort ให้เอาอันล่าสุดขึ้นก่อน
            $query->latest();
        }

        $departments = $query->paginate(10)->withQueryString();

        return view('departments.index', compact('departments'));
    }

    public function show(Department $department)
    {
        // เช็คสิทธิ์: ถ้าภาควิชานี้ไม่ได้อยู่ในวิทยาเขตเดียวกับ User ให้บล็อก (403)
        // ใช้ load('faculty') เพื่อดึงข้อมูลคณะมาพร้อมกันเลย (Eager Loading)
        $department->load('faculty');

        if ($department->faculty->campus !== Auth::user()->campus) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงข้อมูลของวิทยาเขตอื่น');
        }

        return view('departments.show', compact('department'));
    }

    public function create()
    {
        // เลือกเฉพาะคณะที่อยู่ในวิทยาเขตของ User มาแสดงใน Dropdown
        $faculties = Faculty::where('campus', Auth::user()->campus)->get();
        return view('departments.create', compact('faculties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id'
        ]);

        Department::create($validated);
        return redirect()->route('departments.index')->with('success', 'เพิ่มภาควิชาเรียบร้อย');
    }

    public function edit(Department $department)
    {
        // Security check
        if ($department->faculty->campus !== Auth::user()->campus) abort(403);

        $faculties = Faculty::where('campus', Auth::user()->campus)->get();
        return view('departments.edit', compact('department', 'faculties'));
    }

    public function update(Request $request, Department $department)
    {
        if ($department->faculty->campus !== Auth::user()->campus) abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'faculty_id' => 'required|exists:faculties,id'
        ]);

        $department->update($validated);
        return redirect()->route('departments.index')->with('success', 'แก้ไขข้อมูลเรียบร้อย');
    }

    public function destroy(Department $department)
    {
        if ($department->faculty->campus !== Auth::user()->campus) abort(403);
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'ลบภาควิชาเรียบร้อย');
    }
}
