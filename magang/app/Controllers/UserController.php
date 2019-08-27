<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth, DB;
use App\Models\{Route, Payment, Schedule, Booking};
use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified','UserLevelMiddleware']);
    }
    
    public function user()
    {
        return view('homepage');
    }

    
}
