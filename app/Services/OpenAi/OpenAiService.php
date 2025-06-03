<?php

namespace App\Services\OpenAi;

use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;

class OpenAiService implements OpenAiServiceInterface
{
    /**
     * Send a message to OpenAI Chat API and get the response.
     *
     * @param  array  $messages
     * @param  float|null  $temperature
     * @return array|null
     */
    public function chat(array $userMessages, array $systemMessage = [], string $model = 'gpt-3.5-turbo', float $temperature = 0.7): ?CreateResponse
    {
        try {
            $response = OpenAI::chat()->create([
                'model' => $model,
                'messages' => [
                    $systemMessage,
                    $userMessages,
                ],
            ]);

            return $response;
        } catch (GuzzleException $e) {
            Log::error($e->getMessage(), ['user_message' => $userMessages]);

            // Quietly handle the error and return null.
            return null;
        }
    }
}
