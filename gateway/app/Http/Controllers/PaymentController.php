<?php

namespace App\Http\Controllers;

use App\Clients\PaymentClient;
use App\Telemetry\Telemetry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentClient $paymentClient,
        private Telemetry $telemetry,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(int $id)
    {
        return response()->json($this->paymentClient->get('api/payments/'.$id, []), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->telemetry->span('Create payment', function () use ($request) {
            return response()->json($this->paymentClient->post('api/payments', [
                'user_id' => $request->user()->id,
                'amount' => $request->input('amount'),
                'currency' => $request->input('currency'),
            ]));
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
