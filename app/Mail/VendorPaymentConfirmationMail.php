<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Invoice;

class VendorPaymentConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public Invoice $invoice;
    public string $paymentRef;

    public function __construct(Invoice $invoice, string $paymentRef = '')
    {
        $this->invoice    = $invoice;
        $this->paymentRef = $paymentRef;
    }

    public function envelope(): Envelope
    {
        $po       = $this->invoice->purchaseOrder;
        $poNumber = $po->po_number ?? 'N/A';
        $prTitle  = optional($po->purchaseRequisition)->title ?? '';

        return new Envelope(
            subject: "{$poNumber} - Payment Confirmation",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.vendor_payment_confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
