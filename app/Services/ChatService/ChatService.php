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
     * @var array{instructions: string, wiki_page: string}
     */
    private array $config;

    /**
     * ChatService constructor.
     */
    public function __construct(
        OpenAiServiceInterface $openAiService,
        ExternalDataInterface $externalDataService,
        array $config
    ) {
        $this->openAiService = $openAiService;
        $this->externalDataService = $externalDataService;

        if (! isset($config['instructions'], $config['wiki_page'])) {
            throw new \InvalidArgumentException("Missing required config keys: 'instructions' and/or 'wiki_page' in config/chat.php");
        }

        $this->config = $config;
    }

    /**
     * Handle the user's question and return a response.
     */
    public function handleUserQuestion(string $question): array
    {
        $wikiPageContent = $this->externalDataService->getWikipediaPage($this->config['wiki_page']);

        $systemMessage = [
            'role' => 'system',
            'content' => $this->config['instructions'].
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
