<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Http\Resources\RoomResource;
use App\Models\Message;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('verifiedNumber');
    }

    public function sendMessage(Request $request){


        $sendToId = $request->sendToId;
        $message = $request->message;

        $user = Auth::user();


        if ($message == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter message in Body',
                'status'=> 401
            ];
            return response($response , 401);
        }

        if ($sendToId == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter sendToId in Body',
                'status'=> 401
            ];
            return response($response , 401);
        }


        $room = $user->rooms()->whereIn('user_id' , [$sendToId])->get();


        if ($room == null || sizeof($room) <= 0){


            $room = Room::create([
                'lastMessageTime'=> Carbon::now()->format('Y-m-d H:i:s'),
            ]);


            $room->users()->attach($user->id);
            $room->users()->attach($sendToId);

            Message::create([
                'text'=>$message,
                'user_id'=>$user->id,
                'room_id'=>$room->id
            ]);

            return response([
                "status" => 200,
                "message" => "success",
                "data" => null
            ] , 200);

        } else {


            Message::create([
                'text'=>$message,
                'user_id'=>$user->id,
                'room_id'=>$room[0]->id
            ]);

            $room[0]->update([
               'lastMessageTime'=> Carbon::now()
            ]);

            $response = [
                "status" => 200,
                "message" => "success",
                "data" => null
            ];

            return response( $response , 200);

        }
    }


    public function getRoomsOfUser()
    {
        $user = Auth::user();

        $rooms = $user->rooms()->orderBy('lastMessageTime', 'DESC')->paginate(20);

        $response = [
            'status'=> 200,
            'message'=> 'successa',
            'currentPage' => $rooms->currentPage(),
            'lastPage' => $rooms->lastPage(),
            'data'=> RoomResource::collection($rooms),
        ];

        return response($response , 200);

    }


    public function getMessagesOfRoom(Request $request)
    {
        $roomId = $request->roomId;

        if ($roomId == null){

            $response = [
                'data'=>null,
                'message'=> 'Please Enter roomId in Body',
                'status'=> 401
            ];
            return response($response , 401);
        }

        $room = Room::find($roomId);

        $messages = $room->messages()->orderby('created_at' , 'DESC')->paginate(20);

        $response = [
            'status'=> 200,
            'message'=> 'success',
            'currentPage' => $messages->currentPage(),
            'lastPage' => $messages->lastPage(),
            'data'=> MessageResource::collection($messages),
        ];

        return response($response , 200);
    }


}
