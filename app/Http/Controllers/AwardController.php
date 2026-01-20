<?php

namespace App\Http\Controllers;

use App\Models\Award;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AwardController extends Controller
{
    public function index(Request $request) {
        Gate::authorize('view-any', Award::class);
        $query = Award::query();
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $awards = $query->paginate(5)->withQueryString();
        return view('awards.index', ['awards' => $awards]);
    }

    public function create() {
        Gate::authorize('create', Award::class);
        return view('awards.create', [
            'award' => new Award(),
        ]);
    }

    public function store(Request $request) {
        Gate::authorize('create', Award::class);
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'reward' => ['required', 'numeric', 'min:0', 'max:1000000', 'regex:/^\d+(\.\d{1,2})?$/'],
        ],
            [
                'name.required' => "โปรดกรอกชื่อหมวดรางวัล",
                'name.min' => "โปรดใส่ชื่อหมวดรางวัลอย่างน้อย 3 ตัวอักษร",
                'reward.required' => "โปรดกรอกจำนวนรางวัล (บาท)",
                'reward.min' => 'จำนวนเงินรางวัลต้องไม่เป็นเลขติดลบ',
                'reward' => 'โปรดกรอกจำนวนเงินรางวัลให้ถูกต้อง'
            ]
        );
        $award = new Award();
        $award->name = $request->input('name');
        $award->reward = $request->input('reward');
        $award->save();
        return redirect()->route('awards.index');
    }

    public function edit(Award $award) {
        Gate::authorize('update', $award);
        return view('awards.edit', ['award' => $award]);
    }

    public function update(Request $request, Award $award) {
        Gate::authorize('update', $award);
        $changes = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:3'],
            'reward' => ['required', 'numeric', 'min:0', 'max:1000000', 'regex:/^\d+(\.\d{1,2})?$/'],
        ],
            [
                'name.required' => "โปรดกรอกชื่อหมวดรางวัล",
                'name.min' => "โปรดใส่ชื่อหมวดรางวัลอย่างน้อย 3 ตัวอักษร",
                'reward.required' => "โปรดกรอกจำนวนรางวัล (บาท)",
                'reward.min' => 'จำนวนเงินรางวัลต้องไม่เป็นเลขติดลบ',
                'reward' => 'โปรดกรอกจำนวนเงินรางวัลให้ถูกต้อง'
            ]
        );

        $award->update($changes);
        return redirect()->route('awards.index')->with('success');
    }

    public function destroy(Award $award) {
        Gate::authorize('delete', $award);
        $award->delete();
        return redirect()->route('awards.index');
    }


}
