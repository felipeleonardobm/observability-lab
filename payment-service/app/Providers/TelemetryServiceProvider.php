<?php

namespace App\Providers;

use App\Telemetry\Telemetry;
use Illuminate\Support\ServiceProvider;
use OpenTelemetry\API\Trace\TracerInterface;
use OpenTelemetry\Contrib\Otlp\SpanExporterFactory;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\SDK\Trace\TracerProvider;

class TelemetryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TracerProvider::class, function () {
            $exporter = (new SpanExporterFactory())->create();
            $processor = new SimpleSpanProcessor($exporter);
            return new TracerProvider($processor);
        });

        $this->app->singleton(TracerInterface::class, function ($app) {
            $tracerProvider = $app->make(TracerProvider::class);

            return $tracerProvider->getTracer(
                config('otel.service_name'),
                config('otel.version'),
            );
        });

        $this->app->singleton(Telemetry::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
