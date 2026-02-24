<?php

namespace App\Http\Controllers;

/**
 * F7.2: Contact form endpoint
 */
class ContactController extends BaseController
{
    /**
     * POST /api/v1/contact
     * Submit contact form
     */
    public function store(): void
    {
        $data = $this->getJsonBody();
        
        $this->validateRequired($data, ['name', 'email', 'subject', 'message']);

        // Validate email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email', 422);
        }

        // Simple spam check
        if (strlen($data['message']) < 10) {
            $this->error('Message too short', 422);
        }

        try {
            // Send email
            $to = getenv('CONTACT_EMAIL') ?: 'contact@example.com';
            $subject = "New Contact: " . htmlspecialchars($data['subject']);
            $message = sprintf(
                "Name: %s\nEmail: %s\n\nMessage:\n%s",
                htmlspecialchars($data['name']),
                htmlspecialchars($data['email']),
                htmlspecialchars($data['message'])
            );
            $headers = "From: " . htmlspecialchars($data['email']);

            mail($to, $subject, $message, $headers);

            $this->success([
                'received' => true,
                'timestamp' => date('Y-m-d H:i:s'),
            ], 'Message received successfully');
        } catch (\Exception $e) {
            error_log("Contact form error: " . $e->getMessage());
            $this->error('Failed to send message', 500);
        }
    }
}
