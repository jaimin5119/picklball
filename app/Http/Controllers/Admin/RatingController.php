<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ratings;

class RatingController extends Controller
{
    public function index(){
        $list = Ratings::join('users as u', 'u.id', '=', 'ratings.user_id')
                ->whereNull('ratings.deleted_at')
                
                ->select('ratings.id', 'ratings.user_id', 'ratings.details', 'ratings.display', 'ratings.rating', 'u.first_name' , 'last_name')
                ->get();

        return view('admin.ratings.list_ratings', ['list' => $list]);
    }


    public function displayStatus(Request $request){
        $id = $request->id;

        $ex = explode('__', $id);
        $active_id = $ex[0];
        $status = $ex[1];

        if($status == 1 || $status == '1'){
            $display = 0;
        }
        else{
            $display = 1;
        }
        // return $display;
        $active = Ratings::where('id', $active_id)->update(['display' => $display]);

        // return response($id);

        if($active){
            return response('success');
        }
        else{
            return response('error');
        }
    }

    public function delRating(Request $request){
        $del = Ratings::where('id', $request->id)->delete();

        if($del){
            return 'success';
        }
        else{
            return 'error';
        }
    }
}
