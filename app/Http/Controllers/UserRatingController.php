<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ratings;
use App\Models\UserAuthToken;
use Validator;
class UserRatingController extends Controller
{
    public function index(){
        $list = Ratings::join('users as u', 'u.id', '=', 'ratings.user_id')
                ->whereNull('ratings.deleted_at')
                ->where('ratings.display', 1)
                ->select('ratings.id', 'ratings.user_id', 'ratings.details', 'ratings.display', 'ratings.rating', 'u.first_name' , 'last_name')
                ->get();

        $data = array();

        foreach ($list as $key => $l) {
            $data[] = [
                'id' => $l->id,
                // 'title' => $l->title,
                'review' => $l->details,
                'rating' => $l->rating,
                'user' => [
                            'user_id' => $l->user_id,
                            'first_name' => $l->first_name,
                            'last_name' => $l->last_name,
                        ],
                'visible' => $l->display,        
            ];
        }

        $detail['code'] = 200;
        $detail['status'] = 'success';
        $detail['message'] = '';
        $detail['data'] = $data;
        return response()->json($detail);
    }

    public function addRating(Request $request){
        // $validator = Validator::make($request->all(), [
        //     'title' => 'required',
        //     'details' => 'required',
        //     'rating' => 'required',
        // ]);

        // if ($validator->fails()) {
        //     // return $validator->errors();
        //     $errorString = implode(",",$validator->errors()->all());

        //     $detail['code'] = 401;
        //     $detail['status'] = 'error';
        //     $detail['message'] = $errorString;
        //     return response()->json($detail);
        // }

        // auth check

        $usr_auth = app('App\Http\Controllers\ApiController')->AuthCheck($request->header('token'), $request->user_id);
        // return $usr_auth;
        if(!empty($usr_auth)){
            return response()->json($usr_auth);
        }
        
        $rating = Ratings::updateOrCreate([
                        'user_id' => $request->user_id,
                    ],
                    [
                        // 'title' => $request->title,
                        'details' => $request->review,
                        'rating' => $request->rating,
                    ]);

        if($rating){
            $detail['code'] = 200;
            $detail['status'] = 'success';
            $detail['message'] = 'Rating saved successfully.';
            
            return response()->json($detail);
        }
        else{
            $detail['code'] = 401;
            $detail['status'] = 'error';
            $detail['message'] = 'Error! Rating not saved.';
            
            return response()->json($detail);
        }
    }

}
