<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Jobs\PaymentProcessor;
use App\Models\Payment;
use App\Telemetry\Telemetry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        private Telemetry $telemetry,
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(int $id)
    {
        $this->telemetry->span('Get payment', function () use ($id) {
            return response()->json([
                'payment' => Payment::find($id)
            ], 200);
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePaymentRequest $request)
    {
        $this->telemetry->span('Create payment', function () use ($request) {
            $validated = $request->validated();
    
            $payment = Payment::create([
                'user_id' => $validated['user_id'],
                'amount' => $validated['amount'],
                'currency' => $validated['currency'],
            ]);
    
            Log::info('Inserted new payment.', [
                'payment_id' => $payment->id,
                'status' => $payment->status,
            ]);
    
            PaymentProcessor::dispatch($payment);
    
            return response()->json($payment, 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
