<?php

namespace App\Http\Controllers;

use App\Mail\HourMessages;
use App\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ChatController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('chat.chat');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $fromUser = Auth::user();

        $this->validate($request, [
            'message' => 'required|max:255',
        ]);

        $message = Message::create([
            'message' => $request->input('message'),
            'user_id' => $fromUser->id,
        ]);

        Mail::to($fromUser)
            ->send(new HourMessages($fromUser->id));

        return response()->json([
            'status' => 'success',
            'message' => [
                'time' => $message->created_at->diffForHumans(),
                'user_id' => $fromUser->id,
                'username' => $fromUser->name,
                'message' => $message->message
            ]
        ], 200);
    }

    /**
     * Get dialog messages
     * @param Request $request
     * @return Response
     */
    public function messages(Request $request)
    {
        $data = Message::getMessages();

        $messages = [];
        $next = $data->nextPageUrl();

        foreach ($data as $message) {
            $messages[] = [
                'time' => (new Carbon($message->created_at))->diffForHumans(),
                'user_id' => $message->user_id,
                'username' => $message->username,
                'message' => $message->message
            ];
        }

        return response()->json([
            'status' => 'success',
            'messages' => $messages,
            'next' => $next
        ], 200);
    }
}
