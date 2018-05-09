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
    public static function generatePDF($filename, $data)
    {
        // Direct download example code
        // $pdf = PDF::loadView('myPDF', $data)->save(public_path() . '/final-data/final-data.pdf');
        // Direct download function
        // return $pdf->download('hdtuto.pdf');

        $pdfCreated = PDF::loadView('pdf', $data)->save(public_path() . '/files/final-data/'.$filename);

        return [
            'data' => $pdfCreated,
            'error' => false,
        ];

    }
}
