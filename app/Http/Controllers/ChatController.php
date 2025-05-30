<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $userMessage = $request->input('message');

        // Изпращане на заявка към OpenAI
        $response = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $userMessage],
            ],
        ]);

        $reply = $response->json()['choices'][0]['message']['content'] ?? 'Няма отговор от OpenAI.';

        return view('chat', [
            'userMessage' => $userMessage,
            'reply' => $reply,
        ]);
    }
}