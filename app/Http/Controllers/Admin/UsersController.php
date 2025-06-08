<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAuthToken;
use Illuminate\Support\Str;
use App\Exports\ExportUser;
use App\Imports\ImportCity;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use App\Models\Vistor;
use App\Models\City;


class UsersController extends Controller
{
    public function index()
    {
        // list users
        $list_qry = User::select('id', 'first_name', 'last_name', 'email', 'is_active','mobile','image','path')->get();

        //return $list_qry;

        return view('admin.users.users_listing', ['list' => $list_qry]);
    }


    public function accountStatus(Request $request){
        $id = $request->id;

        $ex = explode('__', $id);
        $user_id = $ex[0];
        $status = $ex[1];

        if($status == 1 || $status == '1'){
            $set_status = 0;
        }
        else{
            $set_status = 1;
        }
        // return $set_status;
        $active = User::where('id', $user_id)->update(['is_active' => $set_status]);

        // return response($id);

        if($active){
            return response('success');
        }
        else{
            return response('error');
        }
    }

     public function bulkUploadUsers(){

        return view('admin.users.users_upload');
    }

    public function bulkUserStore(Request $request){ 
        $prod_data = Excel::toArray(new ImportCity(), request()->file('user_file'));
        $count = 0;

        
        foreach ($prod_data[0] as $row){
                if($count > 0){
                    if($row[1] == ''){
                        $err[] = "First name cannot be null.";
                        DB::rollback();
                        return back()->withErrors(['msg' => $err]);
                    }

                    //dd($row[0]);

                        $user = City::create([
                            'name_en' => $row[0],
                            'name_fe' => $row[0],
                            'image'=> '1203282826117107.nXpwde09ZTZbPOB3ACG4_height640.png',
                            'path'=>  'public/uploads/city/',
                            'latitude'=> $row[1],
                            'longitude'=> $row[2],
                            'description_en'=> 'In ac lacus luctus, accumsan erat non, tincidunt libero.',
                            'description_fe'=> 'In ac lacus luctus, accumsan erat non, tincidunt libero.',
                          ]);

                           



                }
                $count++;
            }

            if(!empty($err)){
                DB::rollback();
                return back()->withErrors(['msg' => 'Error! User not added']);
            }
            else{
                DB::commit();
                return back()->with(['success' => 'User added successfully.']);
            }

    }


     public function bulkUserUpdate(Request $request){ 
        $prod_data = Excel::toArray(new ImportUser(), request()->file('user_file_update'));
        
        $count = 0;
        DB::beginTransaction();
        $err = array();
        foreach ($prod_data[0] as $row){
                if($count > 0){


                    if($row[1] == ''){
                        $err[] = "First name cannot be null.";
                        DB::rollback();
                        return back()->withErrors(['msg' => $err]);
                    }

                    if($row[2] == ''){
                        $err[] = "Last name cannot be null.";
                        DB::rollback();
                        return back()->withErrors(['msg' => $err]);
                    }
                    if($row[3] == ''){
                        $err[] = "Mobile cannot be null.";
                        DB::rollback();
                        return back()->withErrors(['msg' => $err]);
                    }
                   

                    $user = User::where('email',$row[3])
                    ->update([
                            'first_name' => $row[1],
                            'last_name' => $row[2],
                            'mobile'=> $row[4],
                            'image'=> $row[5],
                          ]);

                }
                $count++;
            }

            if(!empty($err)){
                DB::rollback();
                return back()->withErrors(['msg' => 'Error! User not update']);
            }
            else{
                DB::commit();
                return back()->with(['success' => 'User update successfully.']);
            }

    }

    public function exportUsers(Request $request){
        return Excel::download(new ExportUser, 'users.xlsx');
    }

    public function viewVistorPage($id)
    {
         $view = Vistor::where('user_id', $id)->first();

         $view_dash = Vistor::where('user_id', $id)->where('page_name','Dashboard')->get();
         $dash_date = Vistor::where('user_id', $id)->where('page_name','Dashboard')->latest()->first();
        $view_reg = Vistor::where('user_id', $id)->where('page_name','Register')->get();
        $reg_date = Vistor::where('user_id', $id)->where('page_name','Register')->latest()->first();
        $view_login = Vistor::where('user_id', $id)->where('page_name','Login')->get();
        $login_date = Vistor::where('user_id', $id)->where('page_name','Login')->latest()->first();
       
        return view('admin.users.user_vistor_view',compact('view_dash','dash_date','view_reg','view_login','login_date','reg_date','view'));
    }

    public function vistorDashPage($id)
    {
        $list = Vistor::join('users','users.id','=','vistors.user_id')->where('user_id', $id)->where('page_name','Dashboard')->select('first_name','last_name','vistors.*')->get();
       
         return view('admin.users.user_vistor_listing',compact('list'));
    }

    public function vistorLoginPage($id)
    {
        $list = Vistor::join('users','users.id','=','vistors.user_id')->where('user_id', $id)->where('page_name','Login')->select('first_name','last_name','vistors.*')->get();
       
         return view('admin.users.user_vistor_listing',compact('list'));
    }

    public function vistorRegPage($id)
    {
        $list = Vistor::join('users','users.id','=','vistors.user_id')->where('user_id', $id)->where('page_name','Register')->select('first_name','last_name','vistors.*')->get();
       
         return view('admin.users.user_vistor_listing',compact('list'));
    }
}
