<?php

namespace App\Services\OpenAi;

use OpenAI\Responses\Chat\CreateResponse;

interface OpenAiServiceInterface
{
    /**
     * Send a message to OpenAI Chat API and get the response.
     *
     * @param  float|null  $temperature
     */
    public function chat(
        array $userMessages,
        array $systemMessage = [],
        string $model = 'gpt-3.5-turbo',
        float $temperature = 0.7
    ): ?CreateResponse;
}
