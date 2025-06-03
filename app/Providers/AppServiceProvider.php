<?php

namespace App\Providers;

use App\Services\ChatService\ChatService;
use App\Services\ChatService\ChatServiceInterface;
use App\Services\ExternalData\ExternalDataInterface;
use App\Services\ExternalData\ExternalDataService;
use App\Services\OpenAi\OpenAiService;
use App\Services\OpenAi\OpenAiServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->bind(
            ExternalDataInterface::class,
            ExternalDataService::class
        );

        $this->app->bind(
            OpenAiServiceInterface::class,
            OpenAiService::class
        );

        $this->app->bind(
            ChatServiceInterface::class,
            ChatService::class
        );

        $this->app->singleton(OpenAiService::class, function ($app) {
            return new OpenAiService;
        });

        $this->app->singleton(ExternalDataService::class, function ($app) {
            return new ExternalDataService;
        });

        $this->app->singleton(ChatService::class, function ($app) {
            $config = config('chat');

            return new ChatService(
                $app->make(OpenAiService::class),
                $app->make(ExternalDataService::class),
                $config
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
