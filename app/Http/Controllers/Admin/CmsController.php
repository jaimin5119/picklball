<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CmsPages;
use Validator;
use Illuminate\Support\Str;

class CmsController extends Controller
{
    public function index(){
        $cms_list = CmsPages::whereNull('deleted_at')->orderBy('page_title', 'ASC')->get();

        return view('admin.cms_pages.list_cms_pages', ['list' => $cms_list ]);
    }

    public function addPage(){
        return view('admin.cms_pages/add_cms_page');
    }

    public function storePage(Request $request){
        $validator = Validator::make($request->all(), [
            // 'page_title' => 'required',
            'page_content' => 'required',
        ]);

        if ($validator->fails()) {
            // return $validator->errors();
            return back()->withErrors($validator->errors());
        }

        $store_qry = CmsPages::create([
                        // 'page_title' => $request->page_title,
                        'slug' => Str::slug($request->page_title),

                        'content' => $request->page_content,
                    ]);

        if($store_qry){
            return back()->with(['success' => 'CMS Page added successfully.']);
        }
        else{
            return back()->withErrors('Error in adding CMS Page.');
        }
    }

    public function editPage($id){
        $edit = CmsPages::where('id', $id)->whereNull('deleted_at')->first();

        if(!empty($edit)){
            return view('admin.cms_pages/add_cms_page', ['edit' => $edit]);
        }
        else{
            return back()->withErrors('Error! No CMS Page found.');
        }
    }

    public function updatePage(Request $request){
        $validator = Validator::make($request->all(), [
            // 'page_title' => 'required',
            'page_content' => 'required',
        ]);

        if ($validator->fails()) {
            // return $validator->errors();
            return back()->withErrors($validator->errors());
        }

        $id = $request->xid;
        $update_qry = CmsPages::where('id', $id)
                    ->update([
                        // 'page_title' => $request->page_title,
                        'content' => $request->page_content,
                    ]);

        if($update_qry){
            return back()->with(['success' => 'CMS Page updated successfully.']);
        }
        else{
            return back()->withErrors('Error in updating CMS Page.');
        }
    }

    // public function delPage(){}
}
