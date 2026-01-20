<?php

namespace App\Http\Controllers;

use App\Models\Award;
use Illuminate\Http\Request;

class AwardController extends Controller
{
    public function index() {
        $awards = Award::paginate(5);
        return view('awards.index', ['awards' => $awards]);
    }

    public function create() {
        return view('awards.create', [
            'award' => new Award(),
        ]);
    }

    public function store(Request $request) {
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
}
