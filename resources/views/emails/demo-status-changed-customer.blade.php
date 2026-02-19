<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Request Status Update</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #3B82F6; color: white; padding: 20px; text-align: center; }
        .content { background: #f9fafb; padding: 30px; }
        .status-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center; }
        .status-badge { display: inline-block; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: bold; margin: 10px; }
        .status-new { background: #dbeafe; color: #1e40af; }
        .status-contacted { background: #ddd6fe; color: #5b21b6; }
        .status-demo-scheduled { background: #fef3c7; color: #92400e; }
        .status-demo-completed { background: #d1fae5; color: #065f46; }
        .status-converted { background: #d1fae5; color: #065f46; }
        .info-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Demo Request Status Update</h1>
        </div>

        <div class="content">
            <p>Dear {{ $subscription->full_name }},</p>

            <p>We have an update regarding your demo request for OpenPublicatie Focus.</p>

            <div class="status-box">
                <h3>Status Changed:</h3>
                <div>
                    <span class="status-badge status-{{ strtolower(str_replace('_', '-', $oldStatus)) }}">
                        {{ ucwords(str_replace('_', ' ', $oldStatus)) }}
                    </span>
                    <span style="font-size: 20px;">→</span>
                    <span class="status-badge status-{{ strtolower(str_replace('_', '-', $subscription->status)) }}">
                        {{ $subscription->formatted_status }}
                    </span>
                </div>
            </div>

            @if($subscription->status === 'contacted')
                <p><strong>We've reached out to you!</strong> One of our team members has attempted to contact you. If you haven't heard from us yet, please check your email and phone for any missed messages.</p>
            @elseif($subscription->status === 'demo_scheduled')
                <p><strong>Your demo is scheduled!</strong></p>
                <div class="info-box">
                    @if($subscription->demo_scheduled_at)
                    <p><strong>Scheduled Date & Time:</strong><br>
                    {{ $subscription->demo_scheduled_at->format('l, F j, Y \a\t g:i A') }}</p>
                    @endif
                    <p>We're looking forward to showing you what OpenPublicatie Focus can do for your business!</p>
                </div>
            @elseif($subscription->status === 'demo_completed')
                <p><strong>Thank you for attending our demo!</strong> We hope you found it valuable and informative.</p>
                <p>If you have any questions or would like to discuss next steps, please don't hesitate to reach out to us.</p>
            @elseif($subscription->status === 'converted')
                <p><strong>Welcome to OpenPublicatie!</strong> 🎉</p>
                <p>We're excited to have you as a customer. Our team will be in touch shortly to help you get started.</p>
            @endif

            <p>If you have any questions, please feel free to contact us.</p>

            <p>Best regards,<br>
            <strong>The OpenPublicatie Team</strong></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} OpenPublicatie. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
