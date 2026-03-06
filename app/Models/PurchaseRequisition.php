<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequisition extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'title',
        'description',
        'status',
        'selected_quotation_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function selectedQuotation()
    {
        return $this->belongsTo(Quotation::class, 'selected_quotation_id');
    }
    
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function purchaseOrder()
    {
        return $this->hasOne(PurchaseOrder::class)->latest();
    }

    public function approvals()
    {
        return $this->morphMany(Approval::class, 'approvable');
    }
}
