<?php

namespace App\Http\Controllers;

use App\Services\ChatService\ChatServiceInterface;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function send(Request $request, ChatServiceInterface $chatService)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $userQuestion = $request->input('message');

        $data = $chatService->handleUserQuestion($userQuestion);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
