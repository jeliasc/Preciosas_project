<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        if (Auth::user()->can('ver usuarios')) {
            try {
                $users=User::all();
                return view('users.index', compact('users'));
            } catch (\Throwable $th) {
                $error = 'Eerror';
                $error = $error.''.$th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }
        }else{
            Session::flash('eAuth', 'Error, permiso denegado');
            return redirect('home');
        }
    }

    public function create(){
        if(Auth::user()->can('crear usuarios')){
            try {
                $roles=Role::pluck('name', 'id');
                return view('users.create', compact('roles'));
            } catch (\Throwable $th) {
                $error = 'Error';
                $error = $error.''.$th->getMessage();
                Session::flash('eAuth', $error);
                return redirect('home');
            }
        }else{
            Session::flash('Error, permiso denegado');
            return redirect('home');
        }
    }
}
