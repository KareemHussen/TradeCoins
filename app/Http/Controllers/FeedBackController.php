<?php

namespace App\Http\Controllers;

use App\Models\FeedBack;
use Illuminate\Http\Request;

class FeedBackController extends Controller
{


    public function getFeedBacksOfUser(Request $request)
    {

        if ($request->user_id == null){
            $response = [
                'data'=>null,
                'message'=> 'Please Enter ad_id in Body',
                'status'=> 401
            ];
            return response($response , 401);
        }

        return FeedBack::where('user_id' , $request->user_id)->orderby('created_by')->all();
    }


    public function createFeedBack(Request $request)
    {
        if ($request->review == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter review in Body',
                'status'=> 401
            ];
            return response($response , 401);
        }

        if ($request->user_id == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter user_id in Body',
                'status'=> 401
            ];
            return response($response , 401);
        }


        $feedBack = FeedBack::create([
            'review' => $request->review,
            'user_id' => $request->user_id,
        ]);

        $response = [
            'data'=> $feedBack,
            'message'=> 'Successfully create FeedBack',
            'status'=> 200
        ];
        return response($response , 200);
    }
}
