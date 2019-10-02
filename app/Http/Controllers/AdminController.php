<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\PagesController;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        #you have to be logged in to all routes(that goes through this controller) in order to see them
        $this->middleware('auth');
    }

    public function index()
    {          
        return view('admin.index');
    }

}
