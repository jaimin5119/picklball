<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactUs;

class ContactUsController extends Controller
{
    public function index(){
        $list = ContactUs::whereNull('deleted_at')->get();

        return view('admin.contact_us.list_contact_us', ['list' => $list]);
    }
}
