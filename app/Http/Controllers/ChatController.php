<?php

namespace App\Http\Controllers;

use App\Services\ExternalData\ExternalDataInterface;
use App\Services\OpenAi\OpenAiServiceInterface;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function send(Request $request, OpenAiServiceInterface $openAiService, ExternalDataInterface $externalDataService)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $userQuestion = $request->input('message');

        $wikiPageContent = $externalDataService->getWikipediaPage('Hantec_Markets');

        $systemMessage = [
            'role' => 'system',
            'content' => "You are an assistant that answers ONLY based on the provided content below. If the user's question is not related to this content, reply with:\n".
                "\"Sorry, I can't answer that question.\"\n\n".
                "=== CONTENT ===\n".
                mb_substr($wikiPageContent, 0, 3900, 'UTF-8').
                '================',
        ];

        $userMessage = ['role' => 'user', 'content' => $userQuestion];
        $chat = $openAiService->chat($userMessage, $systemMessage);

        return response()->json([
            'success' => true,
            'data' => [
                'userMessage' => $userQuestion,
                'reply' => $chat['choices'][0]['message']['content'] ?? 'No response from OpenAI.',
            ],
        ]);
    }
}
