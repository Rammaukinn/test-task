<?php

namespace App\Services\PaymentGateway;

use App\Contracts\PaymentGateway\PaymentGateway;
use App\Models\Merchant;
use App\Models\Payment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class SecondPaymentGateway implements PaymentGateway {

    private Collection $data;
    private string $signature;

    public function __construct(array $request_data, string $signature)
    {
        ksort($request_data);
        $this->data = collect($request_data);
        $this->signature = $signature;
    }

    public function validateData(): MessageBag
    {
        $validator = Validator::make($this->data->all(), [
            'project' => 'required|integer|exists:merchants,merchant_id',
            'invoice' => 'required|integer',
            'status' => 'required|in:created,inprogress,paid,expired,rejected',
            'amount' => 'required|integer',
            'amount_paid' => 'required|integer',
            'rand' => 'required|string'
        ]);

        return $validator->errors();
    }

    public function checkSignature(): bool
    {
        $merchant_id = $this->data->get('project');
        $merchant_key = Merchant::where('merchant_id', $merchant_id)->first('merchant_key')->merchant_key;

        $joint = $this->data->join('.', $merchant_key);
        $hash = md5($joint);

        return $hash == $this->signature;
    }

    private function mutateStatus(): string
    {
        $status = $this->data->get('status');
        $mutations = [
            'inprogress' => 'pending',
            'paid' => 'completed'
        ];

        return array_key_exists($status, $mutations) ? $mutations[$status] : $status;
    }

    public function writeToBase()
    {
        $this->data['status'] = $this->mutateStatus();

        Payment::updateOrCreate(
            [
                'merchant_id' => $this->data->get('project'),
                'payment_id' => $this->data->get('invoice'),
            ],
            [
                'merchant_id' => $this->data->get('project'),
                'payment_id' => $this->data->get('invoice'),
                'status' => $this->data->get('status'),
                'amount' => $this->data->get('amount'),
                'amount_paid' => $this->data->get('amount_paid'),
            ]
        );
    }
}
