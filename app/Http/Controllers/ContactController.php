<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Display contact page
     */
    public function index()
    {
        return view('pages.contact');
    }

    /**
     * Send contact message
     */
    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
        ]);
        
        // Save to database (optional)
        // Contact::create($request->all());
        
        // Send email to admin
        try {
            $adminEmail = setting('store_email', 'admin@owlstore.id');
            
            Mail::send('emails.contact', [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'userMessage' => $request->message,
            ], function($mail) use ($adminEmail, $request) {
                $mail->to($adminEmail)
                     ->subject('Pesan Baru dari ' . $request->name)
                     ->replyTo($request->email, $request->name);
            });
        } catch (\Exception $e) {
            Log::error('Failed to send contact email: ' . $e->getMessage());
            // Continue anyway, don't show error to user
        }
        
        // Send auto-reply to user (optional)
        // Mail::send('emails.contact-autoreply', [...], function($mail) use ($request) { ... });
        
        return redirect()->route('contact')
            ->with('success', 'Terima kasih! Pesan Anda telah terkirim. Kami akan membalas dalam 1x24 jam.');
    }
}