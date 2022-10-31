<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        "merchant_id",
        "payment_id",
        "status",
        "amount",
        "amount_paid"
    ];
}
