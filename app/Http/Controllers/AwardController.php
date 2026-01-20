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

    public function show(Award $award) {

    }

    public function create() {

    }
}
