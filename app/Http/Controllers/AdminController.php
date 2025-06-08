<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\Admin;
use Mail;
use Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Vistor;

class AdminController extends Controller
{

    public function login(){

        if(Auth::guard('admin')->check()){
            return redirect()->route('admin.dash');
        }

        return view('admin.auth.login');
    }

    public function postLogin(Request $request){
        // return $request;
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }


        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {

            // echo "admin logged success";
            return redirect()->route('admin.dash');
        }
        else{
            return back()->withErrors("Invalid email or password.");
        }
    }

    public function dashboard(){
       
        $statistics_page = DB::table('vistors')->select('page_name')->distinct()->get();

        $statistics = DB::table('vistors')->select('user_id')->distinct()->get();
        $statistics_count = $statistics->count();
 
        //date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)
   
       //dd(date('d-m-Y h:i A'));

        return view('admin.dash',compact('statistics_page','statistics_count'));
    }


    public function logout(Request $request){
        if (Auth::guard('admin')->check()) {
            // return "admin auth checked";
            Auth::guard('admin')->logout();
            $request->session()->flush();
            $request->session()->regenerate();
        }

        return redirect()->route('admin.login');
    }


    public function changePwd(){
        return view('admin.change_password');
    }

    public function updatePwd(Request $request){
        
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required| min:8'
        ]);

        if ($validator->fails()) {
            // return $validator->errors();
            return back()->withErrors($validator->errors());
        }

        $admin = Auth::guard('admin')->id();

        $chk = Admin::where('id', $admin)->select('password')->first();
        // return $admin;
        if (Hash::check($request->current_password, $chk->password)) {
            $new_pwd = Admin::where('id', $admin)
                    ->update([
                        'password' => bcrypt($request->password)
                    ]);

            if($new_pwd){
                return back()->with(['success' => 'Password updated successfully.']);
            }
        }
        else{
            return back()->withErrors('Invalid old password.');
        }
    }


    public function forgotPass(){
        return view('admin.auth.forgot-password');
    }

    public function postForgotPass(Request $request){
        // return $request;
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $adm = Admin::where('email', $request->email)->first();

            if(isset($adm->id)){
                // return $adm;
                // $adm =  $adm[0];
                $otp = rand(100000, 999999);
                
                $token = base64_encode(convert_uuencode(base64_encode($adm['email']."___".$otp)));
                // dd($token);
                $adm_email = $adm['email'];

                $otp_admin =  Admin::where('id', $adm['id'])->update(['otp' => $otp]);

                $url = route('admin.reset_pwd', [ $token ]);
                return $url;
                Mail::send('admin.mails.reset_pwd', ['token' => $url, 'name' => $adm->f_name." ".$adm->l_name, ], function ($message) use ($adm_email)
                {

                    $message->from('support@testdomain.com', 'Test Admin Support');
                    $message->subject("reset password requested.");
                    $message->to($adm_email);
                });

                
                if(empty(array_filter(Mail::failures()))) {
                    return back()->with(['success' => 'Success! password reset link has been sent to your email']);
                }
                else{
                    // return Mail::failures();
                    return back()->withErrors('Failed! there is some issue with email provider');
                }
            }
            else{
                return back()->withErrors("This email is not registered");
            }
    }


    public function resetPassword($xstr){
        $decode = base64_decode(convert_uudecode(base64_decode($xstr)));

        $decode = explode('___', $decode);
        $email = $decode[0];

        // dd($email);
        $verfiy_qry = Admin::where('email', $email)->where('otp', $decode[1])->first();


        if(empty($verfiy_qry)){
            return "Invalid link.";
        }
        else{
            // echo "link valid";
            return view('admin.auth.newpassword', ['email' => $email]);
        }
    }

    public function setNewPassword(Request $request){
        // return $request;
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|min:8'
        ]);

        if ($validator->fails()) {
            // return $validator->errors();
            return back()->withErrors($validator->errors());
        }

        $admin_chk = Admin::where('email', $request->email)->first();

        if(isset($admin_chk->id) && $admin_chk->otp != null){
            $admin_id = $admin_chk->id;
            $new_pass = bcrypt($request->password);
            $update_pass = Admin::where('id', $admin_id)->update([ 'password' => $new_pass]);

            if($update_pass){
                $update_otp = Admin::where('id', $admin_id)->update([ 'otp' => null]);
                return redirect()->route('admin.login')->with(['success' => 'Success! password has been modified. Please login using your new password.']);
            }
            else{
                return back()->withErrors('Failed! there is some issue with password update.');
            }
        }
        else{
            return back()->withErrors('Failed! This admin email is not found.');
        }
    }
    public function adminUsers(Request $request){
        $list = DB::table('admins')->get();


        return view('admin.adminuser.index',compact('list'));

    }

    public function admineditPage($id){
        $edit = DB::table('admins')->where('id', $id)->first();

        if(!empty($edit)){
            return view('admin.adminuser.edit', ['edit' => $edit]);
        }
        else{
            return back()->withErrors('Error! No CMS Page found.');
        }
    }

    public function adminupdatePage(Request $request){
        $validator = Validator::make($request->all(), [
            // 'page_title' => 'required',
            // 'page_content' => 'required',
        ]);

        if ($validator->fails()) {
            // return $validator->errors();
            return back()->withErrors($validator->errors());
        }
        $updatedData = [
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email' => $request->email,
        ];
        $id = $request->xid;
        $update_qry =  DB::table('admins')
        ->where('id', $id)
        // ->update($updatedData);
        ->update([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email' => $request->email,
        ]);
        if($update_qry){
            return back()->with(['success' => 'admin updated successfully.']);
        }
        else{
            return back()->withErrors('Error in updating CMS Page.');
        }
    }
}
