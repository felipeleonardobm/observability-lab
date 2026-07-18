<?php

namespace App\Telemetry;

use Closure;
use OpenTelemetry\API\Trace\TracerInterface;
use Throwable;

class Telemetry
{
    public function __construct(
        private readonly TracerInterface $tracer,
    ) {}

    public function startSpan(string $name)
    {
        return $this->tracer
            ->spanBuilder($name)
            ->startSpan();
    }

    public function span(
        string $name,
        Closure $callback,
        array $attributes = []
    ) {
        $span = $this->tracer
            ->spanBuilder($name)
            ->startSpan();

        $scope = $span->activate();

        foreach ($attributes as $key => $attribute) {
            $span->setAttribute($key, $attribute);
        }

        try {
            return $callback();
        } catch (Throwable $e) {
            $span->recordException($e);
            $span->setStatus("ERROR");

            throw $e;
        } finally {
            $scope->detach();
            $span->end();
        }
    }
}