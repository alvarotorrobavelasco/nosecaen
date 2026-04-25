<?php

namespace App\Mail;

use App\Models\Cuota;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

/**
 * Mailable para enviar factura PDF al cliente.
 * 
 * @author Álvaro Torroba Velasco
 * @version 1.0.0
 */
class FacturaEnviada extends Mailable
{
    use Queueable, SerializesModels;

    public Cuota $cuota;

    /**
     * Create a new message instance.
     */
    public function __construct(Cuota $cuota)
    {
        $this->cuota = $cuota;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Factura de Mantenimiento - Nosecaen S.L.',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.factura',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Generamos el PDF en memoria y lo adjuntamos al correo
        $pdf = Pdf::loadView('cuotas.pdf', ['cuota' => $this->cuota]);
        
        return [
            Attachment::fromData(fn () => $pdf->output(), 'factura-' . $this->cuota->id . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}