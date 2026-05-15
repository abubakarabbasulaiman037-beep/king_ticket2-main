<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Ticket;

class PdfTicketService
{
    public function generate(Ticket $ticket)
    {
        try {
            $event = $ticket->event;
            $buyer = $ticket->user;

            // Generate QR code
            $qrData = route('tickets.show', $ticket->id);
            
            // Try using SimpleSoftwareIO library first
            try {
                $qrPng = QrCode::format('png')->size(300)->generate($qrData);
            } catch (\Throwable $e) {
                // Fallback: Use QR Server API
                $qrApi = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data='.urlencode($qrData);
                $qrPng = @file_get_contents($qrApi);
                
                if (!$qrPng) {
                    // Create placeholder QR
                    $qrPng = $this->generatePlaceholderQR();
                }
            }

            // Save QR code
            $qrFilename = 'ticket_'.$ticket->id.'_'.time().'.png';
            if (!is_dir(storage_path('app/public/qrcodes'))) {
                mkdir(storage_path('app/public/qrcodes'), 0755, true);
            }
            Storage::disk('public')->put('qrcodes/'.$qrFilename, $qrPng);
            
            // Update ticket QR code
            $ticket->update(['qr_code' => $qrFilename]);

            // Render HTML for PDF - pass the ticket directly, it has the qr_code now
            $html = view('tickets.pdf', compact('ticket', 'event', 'buyer'))->render();

            // Generate PDF with optimized settings for single page
            $pdf = Pdf::loadHTML($html);
            
            // Set paper size and margins for one page
            $pdf->setPaper('A4');
            $pdf->setOption('enable_html5_parser', true);
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isPhpEnabled', true);
            $pdf->setOption('defaultFont', 'Arial');
            $pdf->setOption('margin-top', 0);
            $pdf->setOption('margin-right', 0);
            $pdf->setOption('margin-bottom', 0);
            $pdf->setOption('margin-left', 0);
            $pdf->setOption('page-size', 'A4');

            // Save PDF
            $pdfFilename = 'tickets/pdf/ticket_'.$ticket->id.'_'.time().'.pdf';
            if (!is_dir(storage_path('app/public/tickets/pdf'))) {
                mkdir(storage_path('app/public/tickets/pdf'), 0755, true);
            }
            Storage::disk('public')->put($pdfFilename, $pdf->output());

            // Update ticket with PDF path
            $ticket->update(['pdf_path' => $pdfFilename]);

            return Storage::disk('public')->url($pdfFilename);
        } catch (\Throwable $e) {
            \Log::error('PDF Ticket Generation Error', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Generate a placeholder QR code image (for fallback)
     */
    private function generatePlaceholderQR()
    {
        // Create a simple white square with text
        $image = imagecreatetruecolor(300, 300);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        $lightgray = imagecolorallocate($image, 200, 200, 200);

        // Fill background white
        imagefilledrectangle($image, 0, 0, 300, 300, $white);

        // Draw border
        imagerectangle($image, 0, 0, 299, 299, $black);

        // Add text
        imagestring($image, 3, 10, 140, 'QR CODE', $black);

        // Capture output
        ob_start();
        imagepng($image);
        $content = ob_get_clean();
        imagedestroy($image);

        return $content;
    }
}
