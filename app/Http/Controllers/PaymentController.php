<?php

namespace App\Http\Controllers;

use App\Services\PaymentGateway\FirstPaymentGateway;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function firstPaymentGateway(Request $request)
    {
        $payment_gateway = new FirstPaymentGateway($request->all());
        $validate_errors = $payment_gateway->validateData();

        if ($validate_errors->isNotEmpty()) return response()->json($validate_errors, 403);

        if(!$payment_gateway->checkSignature()) {
            return response()->json(
                [
                    "sign" => [
                        "Authentication failed."
                    ]
                ],
                401
            );
        }

        $payment_gateway->writeToBase();

        return response()->json([
            "success" => true
        ]);
    }

    public function secondPaymentGateway(Request $request)
    {

    }
}
