<?php

namespace App\Providers;

use App\Clients\PaymentClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PaymentClient::class, function ($app) {
            $pendingRequest = Http::baseUrl((string) config('services.payment.base_url'))
                ->timeout(10)
                ->acceptJson()
                ->asJson();

            return new PaymentClient(
                pendingRequest: $pendingRequest
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
