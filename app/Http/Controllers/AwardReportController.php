<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AwardReportController extends Controller
{
    public function index(Request $request)
    {
        $users = User::whereHas('awards')
            ->with('awards')
            ->paginate(5)
            ->appends(request()->query());
        return view("report.award-report", [
            'users' => $users
        ]);
    }
}
