<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reply to your contact request</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #3B82F6; color: white; padding: 20px; text-align: center; }
        .content { background: #f9fafb; padding: 30px; }
        .message-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #3B82F6; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reply to your contact request</h1>
        </div>

        <div class="content">
            <p>Dear {{ $contactForm->full_name }},</p>

            <p>Thank you for contacting us. We have received your message and would like to respond:</p>

            <div class="message-box">
                {!! nl2br(e($replyMessage)) !!}
            </div>

            <p>If you have any further questions or concerns, please don't hesitate to reach out to us.</p>

            <p>Best regards,<br>
            <strong>{{ config('mail.from.name', 'Support Team') }}</strong></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

