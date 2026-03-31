<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('components.⚡users-component.blade.php',compact('users'));
    }
}
