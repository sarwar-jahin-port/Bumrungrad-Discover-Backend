<?php

namespace App\Http\Traits;

use App\Mail\SubmissionNotification;
use Illuminate\Support\Facades\Mail;

trait NotifiesAdmin
{
    /**
     * Email the admin inbox with a form submission's data and any uploaded
     * files. Never lets a mail failure turn a successful submission into an
     * error response for the patient.
     */
    public function notifyAdmin(string $formLabel, array $data, array $attachmentPaths = []): void
    {
        try {
            Mail::to('rodiscoverbangladesh@gmail.com')
                ->send(new SubmissionNotification($formLabel, $data, $attachmentPaths));
        } catch (\Throwable $e) {
            \Log::warning("{$formLabel} notification email failed: " . $e->getMessage());
        }
    }
}
