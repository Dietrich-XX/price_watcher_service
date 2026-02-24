<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\PriceSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PriceChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(readonly public PriceSubscription $priceSubscription)
    {}

    /**
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Price Changed!',
        );
    }

    /**
     * @return Content
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.price_changed'
        );
    }
}
