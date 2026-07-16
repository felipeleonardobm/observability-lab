<?php

namespace App\Jobs;

use App\Enums\PaymentStatusEnum;
use App\Models\Payment;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class PaymentProcessor implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Payment $payment
    ) {} 

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $this->payment->update([
                'status' => PaymentStatusEnum::PROCESSING
            ]);

            sleep(random_int(1, 5));

            $roll = random_int(1, 100);

            $status = match (true) {
                $roll <= 75 => PaymentStatusEnum::APPROVED,
                $roll <= 90 => PaymentStatusEnum::DENIED,
                $roll <= 95 => throw new Exception(),
                default => PaymentStatusEnum::FAILED,
            };

            $this->payment->update([
                'status' => $status
            ]);

            Log::info('Payment processed.', [
                'payment_id' => $this->payment->id,
                'status' => $this->payment->status,
            ]);
        } catch (\Throwable $e) {
            Log::info('Payment processing failed.', [
                'payment_id' => $this->payment->id,
                'status' => $this->payment->status,
            ]);
        }
    }
}
