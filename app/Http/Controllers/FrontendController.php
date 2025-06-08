<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CmsPages;

class FrontendController extends Controller
{
    public function index()
    {

        return view('frontend.home'); // Ensure this view exists
    }
  
    // public function show($slug)
    // {
    //     $page = CmsPages::where('slug', $slug)->firstOrFail();
    //     return view('frontend.show', compact('page'));
    // }
    public function about()
    {
        $page = CmsPages::where('slug','about-us')->firstOrFail();
        return view('frontend.about-us', compact('page'));
    }
    public function contact()
    {
        $page = CmsPages::where('slug', 'contact-us')->firstOrFail();
        return view('frontend.contact-us', compact('page'));
    }
    public function faqs()
    {
        $page = CmsPages::where('slug', 'faqs')->firstOrFail();
        return view('frontend.faqs', compact('page'));
    }
    public function privacyPolicy()
    {

        $page = CmsPages::where('slug', 'privacy-policy')->firstOrFail();
        return view('frontend.privacy-policy', compact('page'));
    }
    public function termsAndConditions()
    {
        $page = CmsPages::where('slug','Terms-conditions')->firstOrFail();
        return view('frontend.terms-and-conditions', compact('page'));
    }
}
