<?php

namespace App\Services\PaymentGateway;

use App\Contracts\PaymentGateway\PaymentGateway;
use App\Models\Merchant;
use App\Models\Payment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class FirstPaymentGateway implements PaymentGateway
{

    private Collection $data;

    public function __construct(array $request_data)
    {
        $this->data = collect($request_data);
    }

    public function validateData(): MessageBag
    {
        $validator = Validator::make($this->data->all(), [
            'merchant_id' => 'required|integer|exists:merchants',
            'payment_id' => 'required|integer',
            'status' => 'required|in:new,completed',
            'amount' => 'required|integer',
            'amount_paid' => 'required|integer',
            'timestamp' => 'required|integer',
            'sign' => 'required|string'
        ]);

        return $validator->errors();
    }

    public function checkSignature(): bool
    {
        $filtered = $this->data->except(['sign']);
        $merchant_id = $this->data->get("merchant_id");
        $merchant_key = Merchant::where('merchant_id', $merchant_id)->first('merchant_key')->merchant_key;
        $joint = $filtered->join(':', $merchant_key);
        $hash = hash('sha256', $joint);

        return $hash == $this->data->get("sign");
    }

    public function writeToBase()
    {

        Payment::updateOrCreate(
            [
                'merchant_id' => $this->data->get('merchant_id')
            ],
            $this->data->except(['sign'])->all()
        );
    }

}
