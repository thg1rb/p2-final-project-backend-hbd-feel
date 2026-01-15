<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainDashboardController extends Controller
{
    public  function index()
    {
        $data = [
            'all-user' => 20,
            'round' => '2569/1',
            'request' => 100,
        ];
        return view("main.dashboard", [
            'data' => $data
        ]);
    }
}
