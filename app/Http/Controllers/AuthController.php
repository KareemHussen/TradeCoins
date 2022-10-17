<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Mail\RegisterMail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register' , 'forgetPassword' , 'checkForgetPasswordOtp']]);
        $this->middleware('verifiedNumber', ['except' => ['login','register' , 'sendPhoneOtp' , 'checkPhoneOtp' , 'forgetPassword' , 'checkForgetPasswordOtp']]);
    }

    public function login(Request $request)
    {


        if ($request->phone == null){

            $response = [
                'status'=> 400,
                'message'=> 'Please Enter Phone in Body',
                'data'=>null,
            ];

            return response($response , 400);
        }

        if ($request->password == null){

            $response = [
                'status'=> 400,
                'message'=> 'Please Enter password in Body',
                'data'=>null,

            ];
            return response($response , 400);
        }

        $phone = $request->phone;
        $password = $request->password;

        $user = User::where('phone', $phone)->first();


        if ($user){

            $userPassword = $user->password;

            if (Hash::check($password , $userPassword)){

                $response = [
                    'status'=> 200,
                    'message'=> 'success',
                    'data'=> new UserResource($user),
                ];


                return response($response , 200);


            } else {

                $response = [
                    'status'=> 400,
                    'message'=> 'Wrong Phone or Password',
                    'data'=>null,
                ];
                return response($response , 400);

            }

        } else {
            $response = [
                'status'=> 400,
                'message'=> 'Wrong Phone or Password',
                'data'=>null,
            ];
            return response($response , 400);
        }

    }

    public function checkEmailOtp(Request $request){

        $user = Auth::user();

        if ($request->otp == null){

            $response = [
                'status' => 400,
                'message' => "please enter otp",
                'data'=> null,
            ];
            return response($response , 400);

        }

        $user  = User::where([['email','=',$user->email],['emailOtp','=',request('otp')]])->first();

        if($user){
            Auth::login($user, true);
            User::where('email','=',$user->email)->update(['emailOtp' => null , 'email_verified_at' => Carbon::now()]);

            $response = [
                'status' => 200,
                'message' => "success",
                'data'=> null,
            ];
            return response($response , 200);

        } else{

            $response = [
                'status' => 400,
                'message' => "fail",
                'data'=> null,
            ];
            return response($response , 400);
        }
    }

    public function checkPhoneOtp(Request $request){

        $user = Auth::user();

        if ($request->otp == null){

            $response = [
                'status' => 400,
                'message' => "please enter otp",
                'data'=> null,
            ];
            return response($response , 400);

        }

        $user  = User::where([['phone','=',$user->phone],['phoneOtp','=',request('otp')]])->first();

        if($user){
            User::where('email','=',$user->email)->update(['phoneOtp' => null , 'number_verified_at' => Carbon::now()]);

            $response = [
                'status' => 200,
                'message' => "success",
                'data'=> null,
            ];

            return response($response , 200);
        }
        else{
            $response = [
                'status' => 400,
                'message' => "fail",
                'data'=> null,
            ];

            return response($response , 400);

        }
    }

    public function sendEmailOtp(Request $request){

        $otp = rand(1000,9999);

        $user = Auth::user();

        $email = $request->email;

        if ($email == null){
            $response = [
                'status' => 400,
                'message' => "please enter Email in body",
                'data'=> null,
            ];
            return response($response , 400);
        }


        $user::update([
           'email' => $email,
           'emailOtp' => $otp
        ]);

        $data = [
            'subject'=>'Email Verification',
            'name'=> $user->name,
            'emailOtp'=>$otp
        ];

        try {
            Mail::to($email)->send(new RegisterMail($data));

            $response = [
                'status' => 200,
                'message' => "success",
                'data'=> $otp,
            ];

            return response($response , 200);



        }catch (Exception $exception){

            $response = [
                'status' => 400,
                'message' => $exception->getMessage(),
                'data'=> $otp,
            ];

            return response($response , 400);

        }

    }

    public function sendPhoneOtp(){

        $otp = rand(1000,9999);

        $user = Auth::user();

        User::where('phone','=',$user->phone)->update(['phoneOtp' => $otp]);

        try {

            #Make Request
            $response = Http::asForm()->post('http://localhost:8000/chats/send?id=gamed', [
                'receiver' => "+201017046725",
                'message' => [
                    'text' => $otp
                ],
            ]);

            if ($response->successful()){

                $response = [
                    'status' => 200,
                    'message' => "success",
                    'data'=> null,

                ];
                return response($response , 200);

            }

            $response = [
                'status' => 400,
                'message' => "fail",
                'data'=>null,
            ];

            return response($response , 400);


        }catch (Exception $exception){

            $response = [
                'status' => 400,
                'message' => $exception->getMessage(),
                'data'=>null,
            ];
            return response($response , 400);

        }

    }

    public function register(Request $request){

        $validator = Validator::make($request->all(),[
            'name' =>'required|string|min:2|max:24',
            'phone' => 'required|string',
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()){

            $response = [
                'status' => 400,
                'message' => "fail",
                'data'=>$validator->errors(),
            ];
            return response($response , 400);
        }

        $user = User::where('phone',$request->phone)->first();

        if ($user) {

            if ($user->phone_verified_at != null) {

                $response = [
                    'status' => 400,
                    'message' => "Phone already taken",
                    'data' => null,
                ];
                return response($response, 400);

            } else {

                $response = [
                    'status' => 400,
                    'message' => "Phone already taken but not verified",
                    'data' => null,
                ];
                return response($response, 400);
            }
        }



        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'goodVote' => 0,
            'badVote' => 0,
        ]);

        $response = [
            'status' => 200,
            'message' => 'success',
            'data' => new UserResource($user)
        ];

        return response($response , 200);

    }

    public function logout()
    {
        Auth::logout();

        $response = [
            'status' => 200,
            'message' => 'success',
            'data' => null
        ];

        return response($response,200);
    }

    public function me()
    {

        return response()->json([
            'status' => 200,
            'message' => 'success',
            'user' => new UserResource(Auth::user()),
        ]);
    }

    public function resetPassword(Request $request)
    {
        if ($request->oldPassword == null){

            $response = [
                'status'=> 400,
                'message'=> 'Please Enter oldPassword in Body',
                'data'=> null,
            ];

            return response($response , 400);
        }


        if ($request->phoneOrEmail == null){

            $response = [
                'status'=> 400,
                'message'=> 'Please Enter phoneOrEmail in Body',
                'data'=> null,
            ];

            return response($response , 400);
        }

        $password = $request->oldPassword;
        $userPassword = Auth::user()->password;

        $phoneOrEmail = $request->phoneOrEmail;

        if (Hash::check($password , $userPassword)){


            if ($phoneOrEmail == 0){

                if (Auth::user()->email_verified_at != null){

                    $this->sendEmailOtp();

                } else {
                    $response = [
                        'status'=> 400,
                        'message'=> 'Phone must be verified first',
                        'data'=> null,
                    ];
                    return response($response , 400);
                }


            } else if ($phoneOrEmail == 1){


                if (Auth::user()->email_verified_at != null){

                    $this->sendPhoneOtp();

                } else {
                    $response = [
                        'status'=> 400,
                        'message'=> 'Phone must be verified first',
                        'data'=> null,
                    ];
                    return response($response , 400);
                }

            }else {

                $response = [
                    'status'=> 400,
                    'message'=> 'phoneOrEmail can just be 0 or 1',
                    'data'=> null,
                ];

                return response($response , 400);

            }


        } else {

            $response = [
                'status' => 400,
                'message' => 'fail',
                'data' => null
            ];

            return response($response,400);
        }


    }

    public function checkResetPasswordEmailOtp(Request $request){

        $user = Auth::user();

        if ($request->otp == null){

            $response = [
                'status' => 400,
                'message' => "please enter otp",
                'data'=> null,
            ];
            return response($response , 400);

        }

        if ($request->newPassword == null){

            $response = [
                'status' => 400,
                'message' => "please enter newPassword",
                'data'=> null,
            ];
            return response($response , 400);

        }

        $validator = Validator::make($request->all(),[
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()){

            $response = [
                'status' => 400,
                'message' => "fail",
                'data'=>$validator->errors(),
            ];

            return response($response , 400);
        }

        $user  = User::where([['email','=',$user->email],['emailOtp','=',$request->otp]])->first();

        if($user){

            Auth::user()->update([
                'emailOtp' => null,
                'password' => request('newPassword')
            ]);

            $response = [
                'status' => 200,
                'message' => 'success',
                'data' => null
            ];

            return response($response,200);

        }
        else{

            $response = [
                'status' => 400,
                'message' => "fail",
                'data'=> null,
            ];
            return response($response , 400);
        }
    }

    public function checkResetPasswordPhoneOtp(Request $request){

        $user = Auth::user();

        if ($request->otp == null){

            $response = [
                'status' => 400,
                'message' => "please enter otp",
                'data'=> null,
            ];
            return response($response , 400);

        }

        if ($request->newPassword == null){

            $response = [
                'status' => 400,
                'message' => "please enter newPassword",
                'data'=> null,
            ];
            return response($response , 400);

        }

        $validator = Validator::make($request->all(),[
            'password' => 'required|string|min:8'
        ]);

        if ($validator->fails()){

            $response = [
                'status' => 400,
                'message' => "fail",
                'data'=>$validator->errors(),
            ];

            return response($response , 400);
        }

        $user  = User::where([['phone','=',$user->phone],['phoneOtp','=',$request->otp]])->first();

        if($user){
            Auth::user()->update([
                'emailOtp' => null,
                'password' => request('newPassword')
            ]);

            $response = [
                'status' => 200,
                'message' => 'success',
                'data' => null
            ];

            return response($response,200);

        }
        else{

            $response = [
                'status' => 400,
                'message' => "fail",
                'data'=> null,
            ];
            return response($response , 400);
        }
    }

    public function forgetPassword(Request $request){

        $phone = $request->phone;

        if ($phone == null){
            $response = [
                'status' => 400,
                'message' => "Please Enter Phone in body",
                'data'=> null,
            ];
            return response($response , 400);
        }

        $user = User::where('phone' , $phone)->first();

        if ($user){

            $otp = rand(1000,9999);

            try {

                #Make Request
                $response = Http::asForm()->post('http://localhost:8000/chats/send?id=gamed', [
                    'receiver' => $phone,
                    'message' => [
                        'text' => $otp
                    ],
                ]);


                if ($response->successful()){

                    User::where('phone' , $phone)->update(['phoneOtp' => $otp]);

                    $response = [
                        'status' => 200,
                        'message' => "success",
                        'data'=> null,

                    ];
                    return response($response , 200);

                }

                $response = [
                    'status' => 400,
                    'message' => "The receiver number is not exists",
                    'data'=>null,
                ];

                return response($response , 400);


            }catch (Exception $exception){

                $response = [
                    'status' => 400,
                    'message' => $exception->getMessage(),
                    'data'=>null,
                ];
                return response($response , 400);

            }


        } else {
            $response = [
                'status' => 400,
                'message' => "There's no account with this number",
                'data'=> null,
            ];
            return response($response , 400);
        }

    }

    public function checkForgetPasswordOtp(Request $request){


        if ($request->otp == null){

            $response = [
                'status' => 400,
                'message' => "please enter otp",
                'data'=> null,
            ];
            return response($response , 400);

        }

        if ($request->phone == null){

            $response = [
                'status' => 400,
                'message' => "please enter phone",
                'data'=> null,
            ];
            return response($response , 400);
        }

        $phone = $request->phone;

        $user = User::where([['phone','=',$phone],['phoneOtp','=',request('otp')]])->first();

        if($user){
            $user = User::where('phone','=',$phone)->update(['phoneOtp' => null , 'number_verified_at' => Carbon::now()])->first();

            $response = [
                'status' => 200,
                'message' => "success",
                'data'=> new UserResource($user),
            ];


            return response($response , 200);
        }
        else{
            $response = [
                'status' => 400,
                'message' => "fail",
                'data'=> null,
            ];

            return response($response , 400);

        }
    }

}
