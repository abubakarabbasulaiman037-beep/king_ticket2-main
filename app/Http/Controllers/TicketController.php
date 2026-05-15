<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    public function myTickets()
    {
        $tickets = Ticket::where('user_id', Auth::id())->with('event')->latest()->get();
        return view('tickets.myticket', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        // Allow only ticket owner or event owner to view
        if (Auth::id() !== $ticket->user_id && Auth::id() !== $ticket->event->user_id) {
            abort(403);
        }
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Download ticket as PDF
     */
    public function downloadPDF(Ticket $ticket)
    {
        // Allow only ticket owner or event owner to download
        if (Auth::id() !== $ticket->user_id && Auth::id() !== $ticket->event->user_id) {
            abort(403);
        }

        // Check if PDF already exists
        if ($ticket->pdf_path && Storage::disk('public')->exists($ticket->pdf_path)) {
            return Storage::disk('public')->download($ticket->pdf_path, "Ticket-{$ticket->id}.pdf");
        }

        // Generate PDF if it doesn't exist
        try {
            $pdfService = new \App\Services\PdfTicketService();
            $pdfUrl = $pdfService->generate($ticket);
            
            if ($ticket->pdf_path && Storage::disk('public')->exists($ticket->pdf_path)) {
                return Storage::disk('public')->download($ticket->pdf_path, "Ticket-{$ticket->id}.pdf");
            }
        } catch (\Throwable $e) {
            return back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }

        return back()->with('error', 'Unable to generate PDF');
    }

    /**
     * Download ticket as image (PNG)
     */
    public function downloadImage(Ticket $ticket)
    {
        // Allow only ticket owner or event owner
        if (Auth::id() !== $ticket->user_id && Auth::id() !== $ticket->event->user_id) {
            abort(403);
        }

        try {
            // Generate PDF first if needed
            if (!$ticket->pdf_path || !Storage::disk('public')->exists($ticket->pdf_path)) {
                $pdfService = new \App\Services\PdfTicketService();
                $pdfService->generate($ticket);
            }

            // Get fresh ticket data
            $ticket->refresh();

            if (!$ticket->pdf_path || !Storage::disk('public')->exists($ticket->pdf_path)) {
                return back()->with('error', 'PDF not found');
            }

            // Load PDF and convert first page to image
            $pdfPath = Storage::disk('public')->path($ticket->pdf_path);
            
            // Check if Imagick is available
            if (extension_loaded('imagick')) {
                try {
                    $imagick = new \Imagick();
                    $imagick->setResolution(150, 150);
                    $imagick->readImage($pdfPath . '[0]'); // First page only
                    $imagick->setImageFormat('png');
                    $imageContent = $imagick->getImageBlob();
                    $imagick->destroy();

                    return response()->streamDownload(
                        function() use ($imageContent) {
                            echo $imageContent;
                        },
                        "Ticket-{$ticket->id}.png",
                        ['Content-Type' => 'image/png']
                    );
                } catch (\Throwable $imagickError) {
                    \Log::warning('Imagick conversion failed, returning PDF instead', [
                        'ticket_id' => $ticket->id,
                        'error' => $imagickError->getMessage()
                    ]);
                }
            }

            // Fallback: Convert PDF to image using alternative method OR return PDF
            // Try using PHP GD or return the PDF
            return Storage::disk('public')->download($ticket->pdf_path, "Ticket-{$ticket->id}.pdf");

        } catch (\Throwable $e) {
            \Log::error('Image download failed', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);
            return back()->with('error', 'Failed to generate image: ' . $e->getMessage());
        }
    }
}
