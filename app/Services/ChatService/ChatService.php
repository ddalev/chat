<?php

namespace App\Services\ChatService;

use App\Services\ExternalData\ExternalDataInterface;
use App\Services\OpenAi\OpenAiServiceInterface;

class ChatService
{
    /**
     * @var App\Services\OpenAi\OpenAiServiceInterface
     */
    private OpenAiServiceInterface $openAiService;

    /**
     * @var App\Services\ExternalData\ExternalDataInterface
     */
    private ExternalDataInterface $externalDataService;

    /**
     * ChatService constructor.
     */
    public function __construct(
        OpenAiServiceInterface $openAiService,
        ExternalDataInterface $externalDataService
    ) {
        $this->openAiService = $openAiService;
        $this->externalDataService = $externalDataService;
    }

    /**
     * Handle the user's question and return a response.
     */
    public function handleUserQuestion(string $question): array
    {
        $wikiPageContent = $this->externalDataService->getWikipediaPage('Hantec_Markets');

        $systemMessage = [
            'role' => 'system',
            'content' => "You are an assistant that answers ONLY based on the provided content below. If the user's question is not related to this content, reply with:\n".
                "\"Sorry, I can't answer that question.\"\n\n".
                "=== CONTENT ===\n".
                mb_substr($wikiPageContent, 0, 3900, 'UTF-8').
                '================',
        ];

        $userMessage = ['role' => 'user', 'content' => $question];

        $chat = $this->openAiService->chat($userMessage, $systemMessage);

        return [
            'userMessage' => $question,
            'reply' => $chat['choices'][0]['message']['content'] ?? 'No response from OpenAI.',
        ];
    }
}
