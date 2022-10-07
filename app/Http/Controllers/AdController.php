<?php

namespace App\Http\Controllers;

use App\Http\Resources\AdResource;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function getBuyAds()
    {

        $ads = Ad::where('buy_sell', 0)->orderby('created_at' , 'DESC')->paginate(20);

        $response = [
            'status' => 200,
            'message' => 'success',
            'currentPage' => $ads->currentPage(),
            'lastPage' => $ads->lastPage(),
            'data' => AdResource::collection($ads),

        ];
        return response($response, 200);

    }
    public function getSellAds()
    {

        $ads = Ad::where('buy_sell' , 1)->orderby('created_at' , 'DESC')->paginate(20);

        $response = [
            'status'=> 200,
            'message'=> 'success',
            'currentPage' => $ads->currentPage(),
            'lastPage' => $ads->lastPage(),
            'data'=> AdResource::collection($ads),

        ];
        return response($response , 200);

    }


    public function getMyAds()
    {
        $ads = Auth::user()->ads()->orderby('created_at' , 'DESC')->paginate(20);

        $response = [
            'status'=> 200,
            'message'=> 'success',
            'currentPage' => $ads->currentPage(),
            'lastPage' => $ads->lastPage(),
            'data'=> AdResource::collection($ads) ,

        ];
        return response($response , 200);

    }

    public function createAd(Request $request)
    {
        if ($request->min == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter min in Body',
                'status'=> 400
            ];
            return response($response , 400);
        }

        if ($request->max == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter max in Body',
                'status'=> 400
            ];
            return response($response , 400);
        }

        if ($request->buy_sell == null && $request->buy_sell != 0){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter buy_sell in Body',
                'status'=> 400
            ];
            return response($response , 400);
        }

        if ($request->price == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter price in Body',
                'status'=> 400
            ];
            return response($response , 400);
        }

        if ($request->note == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter note in Body',
                'status'=> 400
            ];
            return response($response , 400);
        }

        if ($request->theMethod == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter theMethod in Body',
                'status'=> 400
            ];
            return response($response , 400);
        }

        if ($request->tags == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter tags in Body',
                'status'=> 400
            ];
            return response($response , 400);
        }


        $ad = Ad::create([
            'min' => $request->min,
            'max' => $request->max,
            'buy_sell' => $request->buy_sell,
            'tags' => $request->tags,
            'note' => $request->note,
            'price' => $request->price,
            'theMethod' => $request->theMethod,
            'user_id' => Auth::user()->id
        ]);

        if ($ad){

            $response = [
                'status'=> 200,
                'message'=> 'success',
                'data'=> new AdResource($ad),

            ];
            return response($response , 200);

        } else {

            $response = [
                'status'=> 400,
                'message'=> 'something wrong',
                'data'=>null,
            ];
            return response($response , 400);

        }


    }


    public function updateAd(Request $request)
    {

        if ($request->id == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter id in Body',
                'status'=> 400
            ];
            return response($response , 400);
        }

        $ad = Ad::find($request->id);

        if ($ad){


            $ad = $ad->update([
                'min' => $request->min ?: $ad->min ,
                'max' => $request->max ?: $ad->max,
                'buy_sell' => $request->buy_sell ?: $ad->buy_sell,
                'tags' => $request->tags ?: $ad->tags,
                'note' => $request->note ?: $ad->note ,
                'price' => $request->price ?: $ad->price ,
                'method' => $request->_method ?: $ad->_method
            ]);

            $response = [
                'status'=> 200,
                'message'=> 'success',
                'data'=> null,

            ];
            return response($response , 200);

        }else {

            $response = [
                'status'=> 400,
                'message'=> 'no Ad with this id',
                'data'=>null,
            ];
            return response($response , 400);

        }




    }


    public function deleteAd(Request $request)
    {
        if ($request->id == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter id in Body',
                'status'=> 400
            ];
            return response($response , 400);
        }

        $ad = Ad::where('id' , $request->id)->first();

        if ($ad){
            $ad->delete();

            $response = [
                'status'=> 200,
                'message'=> 'success',
                'data'=>null,
            ];
            return response($response , 200);

        } else {


            $response = [
                'status'=> 400,
                'message'=> 'no Ad with this id',
                'data'=>null,
            ];
            return response($response , 400);

        }

    }
}
