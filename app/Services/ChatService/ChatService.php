<?php

namespace App\Services\ChatService;

use App\Services\ExternalData\ExternalDataInterface;
use App\Services\OpenAi\OpenAiServiceInterface;

class ChatService implements ChatServiceInterface
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
     * 
     * Initializes the ChatService with the OpenAI service and external data service.
     * @param OpenAiServiceInterface $openAiService The OpenAI service instance.
     * @param ExternalDataInterface $externalDataService The external data service instance.
     * @param array $config Configuration array containing 'instructions' and 'wiki_page'.
     * 
     * @throws \InvalidArgumentException If 'instructions' or 'wiki_page' is not provided in the config.
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
     * Handle the user's question by fetching the relevant Wikipedia page content
     * and generating a response using OpenAI's chat API.
     *
     * @param string $question The user's question.
     *
     * @return array An array containing the user's message and the AI's reply.
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
