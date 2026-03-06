<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'purchase_order_id',
        'vendor_id',
        'file_path',
        'status',
        'notes',
        'paid_at'
    ];

    protected $dates = ['paid_at'];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
