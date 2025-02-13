<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Supplier;
use App\Models\User;

class DeliveryNoteUploadMail extends Mailable
{
    use Queueable, SerializesModels;

    public $supplier;
    public $uploadedBy;
    public $fileName;

    public function __construct(Supplier $supplier, User $uploadedBy, string $fileName)
    {
        $this->supplier = $supplier;
        $this->uploadedBy = $uploadedBy;
        $this->fileName = $fileName;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Delivery Note Upload Notification',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.delivery-note-upload',
        );
    }
}
