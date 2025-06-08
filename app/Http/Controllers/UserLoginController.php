<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\OtpVerification;
use App\Models\User;
use App\Models\UserAuthToken;
use Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Hash;


class UserLoginController extends Controller
{
    public function multiLogin(Request $request){
        $invalid_cred = array();
        $invalid_cred['code'] = 401;
        $invalid_cred['status'] = 'error';
        $invalid_cred['message'] = 'Invalid login credentials.';
        
        // return $this->validate_mobile($request->emailMobile); 
        //login using email or mobile
        if($this->validate_mobile($request->emailMobile)){
            //login query using mobile
            
            $user = User::where('mobile',$request->emailMobile)->first();
        }
        elseif($this->checkEmail($request->emailMobile)){
            // login query using email
            
            $user = User::where('email',$request->emailMobile)->first();
        }
        else{
            return $invalid_cred;
        }

        
        if(empty($user)){
            return $invalid_cred;
        }
        else{
            // verfiy password. If yes, show data. Else return invalid credentials.
            if(Hash::check($request->password, $user->password)){
                $user_id = $user->id;
                   $accessToken = Str::random(64);
                    UserAuthToken::updateOrCreate([
                          'user_id' => $user_id,
                          'email' => $user->email
                        ],
                        [
                            'token' => $accessToken
                        ]);

                        if (!empty($user->image)) {
                            $image = asset($user->path . '/' . $user->image);
                        } else {
                           $image = "";
                        }

                        if($user->email_verified == 1){
                            $ev = 'yes';
                        }
                        else{
                            $ev = 'no';
                        }

                        if($user->mobile_verified == 1){
                            $mv = 'yes';
                        }
                        else{
                            $mv = 'no';
                        }

                        $detail['code'] = 200;
                        $detail['status'] = 'success';
                        $detail['message'] = 'You have logged in successfully';
                        $detail['data'] = array(
                                        'accessToken' => @$accessToken,
                                        'userId' => @$user_id,
                                        'firstName' => @$user->first_name,
                                        'lastName' => $user->last_name,
                                        'mobile' => @$user->mobile,
                                        'email' => @$user->email,
                                        'profile' => @$image,
                                        'deviceToken' => @$user->device_token,
                                        'email_verified' => $ev,
                                        'mobile_verified' => $mv,
                                        );
                        return response()->json($detail);
            }
            else{
                
                return $invalid_cred;
            }
        }
    }


    public function multiSendOTP(Request $request){
        // send otp to verify user email or mobile.
        $otp  = rand(000000, 999999); 
        if(is_numeric($request->emailMobile)){
            //validate unique mobile
            $validator = Validator::make($request->all(), [
                'emailMobile' => 'required|unique:users,mobile',
            ]);

            if ($validator->fails()) {
                $detail['code'] = 401;
                $detail['status'] = 'error';
                $detail['message'] = 'This mobile is already registered.';
                return response()->json($detail);
            }

            // send otp to mobile

            $otp_mobile_qry = OtpVerification::create([
                            'mobile' => $request->emailMobile,
                            'code' => $otp,
                            'status' => 1,
            ]);
            
            $detail['code'] = 200;
            $detail['status'] = 'success';
            $detail['message'] = 'Otp sent to mobile.';
            return response()->json($detail);
            
        }
        elseif($this->checkEmail($request->emailMobile)){
            //validate unique email
            $validator = Validator::make($request->all(), [
                'emailMobile' => 'required|unique:users,email',
            ]);

            if ($validator->fails()) {
                $detail['code'] = 401;
                $detail['status'] = 'error';
                $detail['message'] = 'This email id is already registered.';
                return response()->json($detail);
            }

            // send otp to email id
            Mail::send('emails.verify-user', ['otp' => $otp], function ($message) use ($request)
                {
                    $message->from('test@gmail.com', 'help');
                    $message->subject("Account Verification ");
                    $message->to($request->emailMobile);
                });

            $otp_email_qry = OtpVerification::create([
                                'email' => $request->emailMobile,
                                'code' => $otp,
                                'status' => 1,
                            ]);

            
            $detail['code'] = 200;
            $detail['status'] = 'success';
            $detail['message'] = 'Otp sent to email id.';
            return response()->json($detail);
            
        }
        else{
            $detail['code'] = 401;
            $detail['status'] = 'error';
            $detail['message'] = 'Invalid email id or mobile number.';
            return response()->json($detail);
        }
    }

    public function mobileVerifyOTP(Request $request){
        $mobile = $request->mobile;
        $otp = $request->otp;

        $otpVerifcation = OtpVerification::where('mobile', $mobile)->orderBy('updated_at', 'DESC')
                        ->first();
        if (isset($otpVerifcation->id)) {
            if($otp == $otpVerifcation->code){
                OtpVerification::whereId($otpVerifcation->id)->update(['status' => '0']);   
                $detail['code'] = 200;
                $detail['status'] = 'success';
                $detail['message'] = 'Mobile number is verified.';
                return response()->json($detail);
            }else{
                $detail['code'] = 401;
                $detail['status'] = 'error';
                $detail['message'] = 'Incorrect otp or mobile number.';
                return response()->json($detail);
            }
        }
        else{
            $detail['code'] = 401;
            $detail['status'] = 'error';
            $detail['message'] = 'Please follow registration process from beginning.';
            return response()->json($detail);
        }    
    }


    public function multiRegister(Request $request){
        $rules = array(
            'email'    => 'required|unique:users,email'
        );
        $validator  = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $detail['code'] = 401;
            $detail['status'] = 'error';
            $detail['message'] = 'Invalid email id.';
            $detail['data'] = [];
            return response()->json($detail);
        }
        // find otp_record on basis of email or mobile latest updated. Check its verification status. If 0 verified, 1 means not verified.
        if(isset($request->mobile) && $request->mobile != ''){
            $otp_verif = OtpVerification::where('email', $request->email)
                    ->orWhere('mobile', $request->mobile)
                    ->orderBy('updated_at', 'DESC')->first();
        }
        else{
            $otp_verif = OtpVerification::where('email', $request->email)
                    ->orderBy('updated_at', 'DESC')->first();
        }

        if($otp_verif->id){
            if($otp_verif->mobile != ''){
                $source = 'Mobile number';
            }
            elseif($otp_verif->email != ''){
                $source = 'Email id';
            }

            if($otp_verif->status == 1){
                $detail['code'] = 401;
                $detail['status'] = 'error';
                $detail['message'] = $source.' is not verified';
                $detail['data'] = [];
                return response()->json($detail);
            }
            elseif($otp_verif->status == 0){

                if($source == 'Email id'){
                    $user = User::create([
                        'first_name' => @$request->firstName,
                        'last_name' => @$request->lastName,
                        'mobile' => @$request->mobile,
                        'email' => @$request->email,
                        'password' => bcrypt($request->password),
                        'device_token' => @$request->deviceToken,
                        'email_verified' => 1,
                    ]);
                }
                elseif($source == 'Mobile number'){
                    $user = User::create([
                        'first_name' => @$request->firstName,
                        'last_name' => @$request->lastName,
                        'mobile' => @$request->mobile,
                        'email' => @$request->email,
                        'password' => bcrypt($request->password),
                        'device_token' => @$request->deviceToken,
                        'mobile_verified' => 1,
                    ]);
                }
                

                        // return $user;

                    if(isset($user->id)){
                        $accessToken = Str::random(64);
                        $token_qry = UserAuthToken::updateOrCreate([
                                'user_id' => $user->id,
                                'email' => $request->email
                            ],
                            [
                                'token' => $accessToken
                            ]);

                            // return $token_qry;
                            if(!isset($token_qry->id)){
                                $detail['code'] = 401;
                                $detail['status'] = 'error';
                                $detail['message'] = 'User token not generated.';
                                $detail['data'] = [];
                                return response()->json($detail);
                            }
                            // $user_detail = User::findOrFail($user);

                            if($user->email_verified == 1){
                                $ev = 'yes';
                            }
                            else{
                                $ev = 'no';
                            }

                            if($user->mobile_verified == 1){
                                $mv = 'yes';
                            }
                            else{
                                $mv = 'no';
                            }

                            $detail['code'] = 200;
                            $detail['status'] = 'success';
                            $detail['message'] = 'Registration completed';
                            $detail['data'] = array(
                                                'accessToken' => $accessToken,
                                                'userId' => @$user->id,
                                                'firstName' => @$user->first_name,
                                                'lastName' => @$user->last_name,
                                                'mobile' => @$user->mobile,
                                                'email' => @$user->email,
                                                'profile' => @$user->image,
                                                'email_verified' => $ev,
                                                'mobile_verified' => $mv,
                                            );
                            $del = OtpVerification::where('email', $user->email)
                                    ->orWhere('mobile', $user->mobile)
                                    ->delete();     
                            return response()->json($detail);
                        }
                        else{
                            $detail['code'] = 401;
                            $detail['status'] = 'error';
                            $detail['message'] = 'Error in registering user.';
                            $detail['data'] = [];
                            return response()->json($detail);
                        }
            }
        }
        else{
            $detail['code'] = 401;
            $detail['status'] = 'error';
            $detail['message'] = 'Kindly register from signup page';
            $detail['data'] = [];
            return response()->json($detail);
        }

    }

    public function logout(Request $request){
        
        $token = $request->header('token');
        $usr_auth = app('App\Http\Controllers\ApiController')->AuthCheck($token, $request->userId);
        // return $usr_auth;
        if(!empty($usr_auth)){
            return response()->json($usr_auth);
        }

        // remove auth token.
        $auth = UserAuthToken::where('user_id', $request->userId)->delete();

        $detail['code'] = 200;
        $detail['status'] = 'success';
        $detail['message'] = 'You have logged out successfully.';
        return response()->json($detail);
    }


    private function checkEmail($email) {
       $find1 = strpos($email, '@');
       $find2 = strpos($email, '.');
       return ($find1 !== false && $find2 !== false && $find2 > $find1);
    }


    private function validate_mobile($mobile)
    {
        return preg_match('/^[0-9]{10}+$/', $mobile);
    }


    

}
