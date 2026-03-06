<?php

namespace App\Mail;

use App\Models\PurchaseRequisition;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RequesterStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PurchaseRequisition $requisition,
        public string $action,          // 'approved' | 'rejected' | 'po_generated'
        public string $stage,           // 'stage1' | 'stage2'
        public ?string $comments = null,
    ) {}

    public function envelope(): Envelope
    {
        $subject = match($this->action) {
            'approved'     => "✅ Your PR Has Been Approved (Stage {$this->stageNumber()}) — {$this->requisition->title}",
            'po_generated' => "🎉 Purchase Order Generated — {$this->requisition->title}",
            'rejected'     => "❌ Your PR Was Rejected — {$this->requisition->title}",
            default        => "PR Update — {$this->requisition->title}",
        };
        return new Envelope(
            subject: $subject,
            replyTo: [new \Illuminate\Mail\Mailables\Address('adnan.raikar@mainlydigital.in', 'ProcureFlow')]
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.requester_status',
            with: [
                'requisition'  => $this->requisition,
                'action'       => $this->action,
                'stage'        => $this->stage,
                'stageNumber'  => $this->stageNumber(),
                'comments'     => $this->comments,
                'prUrl'        => url('/requisitions/' . $this->requisition->id),
            ]
        );
    }

    private function stageNumber(): int
    {
        return $this->stage === 'stage1' ? 1 : 2;
    }

    public function attachments(): array { return []; }
}
