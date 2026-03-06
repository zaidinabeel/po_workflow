<?php

namespace App\Mail;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VendorPurchaseOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PurchaseOrder $po,
        public string $uploadUrl,
        public string $pdfStoragePath  // relative path under storage/app/public/
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->po->po_number . ' - ' . ($this->po->purchaseRequisition->title ?? 'Purchase Order');

        return new Envelope(
            subject: $subject,
            replyTo: [new \Illuminate\Mail\Mailables\Address('adnan.raikar@mainlydigital.in', 'Axis Capital Ltd.')]
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.vendor_po',
            with: [
                'po'        => $this->po,
                'uploadUrl' => $this->uploadUrl,
            ]
        );
    }

    public function attachments(): array
    {
        // Build absolute path from the relative storage path
        $absolutePath = storage_path('app/public/' . $this->pdfStoragePath);

        if (file_exists($absolutePath)) {
            // Use safe filename for the attachment display name
            $safePoNumber = str_replace('/', '_', $this->po->po_number);
            return [
                Attachment::fromPath($absolutePath)
                    ->as($safePoNumber . '.pdf')
                    ->withMime('application/pdf'),
            ];
        }

        \Illuminate\Support\Facades\Log::warning('PO PDF not found for attachment: ' . $absolutePath);
        return [];
    }
}
