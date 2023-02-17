<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use App\Http\Resources\MessageResource;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "message" => "required",
            "user_id" => "required"
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors(), 403);
        }
        $message = new Message();
        $message->user_id = $request->user_id;
        $message->message = $request->message;
        $message->save();
        return response()->json([
            'data' => new MessageResource($message)
        ]);
    }

    public function index()
    {
        return response()->json([
            'data' => MessageResource::collection(Message::all())
        ]);
    }

}
