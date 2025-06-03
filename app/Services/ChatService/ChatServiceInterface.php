<?php

namespace App\Services\ChatService;

interface ChatServiceInterface
{
    /**
     * Handle the user's question by fetching the relevant Wikipedia page content
     * and generating a response using OpenAI's chat API.
     *
     * @param string $question The user's question.
     *
     * @return array An array containing the user's message and the AI's reply.
     */
    public function handleUserQuestion(string $question): array;
}
