<?php

namespace App\Mail;

use App\Models\PurchaseRequisition;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApproverNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PurchaseRequisition $requisition,
        public string $stage,          // 'stage1' or 'stage2'
        public string $approverName,
    ) {}

    public function envelope(): Envelope
    {
        $stageLabel = $this->stage === 'stage1' ? 'Stage 1 (Compliance)' : 'Stage 2 (IT)';
        return new Envelope(
            subject: "[Action Required] PR #{$this->requisition->id} Awaiting Your Approval — {$stageLabel}",
            replyTo: [new \Illuminate\Mail\Mailables\Address('adnan.raikar@mainlydigital.in', 'ProcureFlow')]
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.approver_notification',
            with: [
                'requisition' => $this->requisition,
                'stage'       => $this->stage,
                'approverName'=> $this->approverName,
                'approvalUrl' => url('/approvals'),
            ]
        );
    }

    public function attachments(): array { return []; }
}
