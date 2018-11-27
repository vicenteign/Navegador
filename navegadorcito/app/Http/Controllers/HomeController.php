<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if($user->isAdmin())
            return redirect()->route('admin');
        if($user->isStudent())
            return redirect()->route('/fichaAlumno');
        if($user->isProfesor())
            return redirect()->route('/fichaProfesor');
        return view('home');
    }
}
