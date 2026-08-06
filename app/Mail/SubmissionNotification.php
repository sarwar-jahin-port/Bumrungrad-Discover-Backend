<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubmissionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public string $formLabel;
    public array $data;
    public array $attachmentPaths;

    public function __construct(string $formLabel, array $data, array $attachmentPaths = [])
    {
        $this->formLabel = $formLabel;
        $this->data = $data;
        $this->attachmentPaths = $attachmentPaths;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Submission: {$this->formLabel}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'email.submission',
            with: [
                'formLabel' => $this->formLabel,
                'data' => $this->data,
            ],
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return collect($this->attachmentPaths)
            ->filter(fn ($path) => $path && file_exists($path))
            ->map(fn ($path) => Attachment::fromPath($path)->as(basename($path)))
            ->values()
            ->all();
    }
}
