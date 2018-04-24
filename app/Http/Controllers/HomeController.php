<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function generatePDF()

    {
        $data = ['title' => 'Welcome to HDTuto.com'];

        $pdf = PDF::loadView('myPDF', $data);

        return $pdf->download('hdtuto.pdf');

    }
}
