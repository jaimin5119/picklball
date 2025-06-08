<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Faq;
class FaqController extends Controller
{
    public function index(){
        $list = Faq::whereNull('deleted_at')->orderBy('question', 'ASC')->get();

        return view('admin.faqs.list_faqs', ['list' => $list ]); 
    }

    public function addFaq(){
        return view('admin.faqs.add_faqs');
    }

    public function storeFaq(Request $request){
        
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'answer' => 'required',
        ]);

        if ($validator->fails()) {
            // return $validator->errors();
            return back()->withErrors($validator->errors());
        }

        $store_qry = Faq::create([
                        'question' => $request->question,
                        'answer' => $request->answer,
                    ]);

        if($store_qry){
            return back()->with(['success' => 'FAQ added successfully.']);
        }
        else{
            return back()->withErrors('Error in adding FAQ.');
        }

    }

    public function editFaq($id){
        $edit = Faq::where('id', $id)->whereNull('deleted_at')->first();

        if(!empty($edit)){
            return view('admin.faqs.add_faqs', ['edit' => $edit]);
        }
        else{
            return back()->withErrors('Error! No FAQ found.');
        }
    }


    public function updateFaq(Request $request){
        // return $request;
        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'answer' => 'required',
        ]);

        if ($validator->fails()) {
            // return $validator->errors();
            return back()->withErrors($validator->errors());
        }

        $id = $request->xid;
        $update_qry = Faq::where('id', $id)
                    ->update([
                        'question' => $request->question,
                        'answer' => $request->answer,
                    ]);

        if($update_qry){
            return back()->with(['success' => 'FAQ updated successfully.']);
        }
        else{
            return back()->withErrors('Error in updating FAQ.');
        }

    }

    public function delFaq(Request $request){
        // return $request->id;

        $del_id = Faq::find($request->id);

        // return $del_id;
        if(!empty($del_id)){
            $del = $del_id->delete();

            if($del){
                return 'success';
            }
            else{
                return 'error'; 
            }
        }
        else{
            return 'error';
        }
    }

}
