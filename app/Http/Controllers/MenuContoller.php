<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('menu.submenu');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index1()
    {
        return view('menu.menudispensado');
    }
    
    public function index2()
    {
        return view('menu.menucompras');
    }
    
    
    public function index3()
    {
        return view('menu.menucotizaciones');
    }
}
