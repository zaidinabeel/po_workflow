<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'purchase_requisition_id',
        'vendor_id',
        'file_path',
        'final_price',
        'is_system_recommended',
        'is_selected'
    ];

    public function purchaseRequisition()
    {
        return $this->belongsTo(PurchaseRequisition::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
