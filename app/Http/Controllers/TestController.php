<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class TestController extends Controller
{
    public function adminIndex()
    {
        //
    }

    public function shopIndex()
    {
        //
    }

    public function guestIndex()
    {
        return Inertia::render('Test/Test');
    }

    public function guestTransition()
    {
        return Inertia::render('Test/Transition');
    }
}
