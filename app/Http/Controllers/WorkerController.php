<?php

namespace App\Http\Controllers;

class WorkerController extends Controller
{
    public function index()
    {
        return view('worker.dashboard');
    }
}
