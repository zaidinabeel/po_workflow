<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'purchase_requisition_id',
        'quotation_id',
        'vendor_id', 
        'po_number', 
        'status', 
        'total_amount', 
        'notes',
        'upload_token',
    ];

    public function purchaseRequisition()
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
