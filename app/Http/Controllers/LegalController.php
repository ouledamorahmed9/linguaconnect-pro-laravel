<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LegalController extends Controller
{
    public function privacy()
    {
        return view('legal.privacy');
    }

    public function terms()
    {
        return view('legal.terms');
    }

    public function refund()
    {
        return view('legal.refund');
    }

    /**
     * Show the Contact Us page.
     * This was the missing method causing your error.
     */
    public function contact()
    {
        return view('legal.contact');
    }

    public function faq()
    {
        return view('legal.faq');
    }
}