<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Demo Request</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1f2937; color: white; padding: 20px; }
        .content { background: #f9fafb; padding: 30px; }
        .info-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #3B82F6; }
        .info-row { padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
        .info-row:last-child { border-bottom: none; }
        .label { font-weight: bold; color: #6b7280; min-width: 150px; display: inline-block; }
        .value { color: #111827; }
        .status-badge { display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .status-new { background: #dbeafe; color: #1e40af; }
        .status-demo-scheduled { background: #fef3c7; color: #92400e; }
        .action-button { display: inline-block; padding: 12px 24px; background: #3B82F6; color: white; text-decoration: none; border-radius: 6px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🔔 New Demo Request Received</h2>
            <p style="margin: 0; opacity: 0.9;">{{ now()->format('F j, Y - H:i') }}</p>
        </div>

        <div class="content">
            <div class="info-box">
                <h3>Contact Information:</h3>

                <div class="info-row">
                    <span class="label">Name:</span>
                    <span class="value">{{ $subscription->full_name }}</span>
                </div>

                <div class="info-row">
                    <span class="label">Email:</span>
                    <span class="value"><a href="mailto:{{ $subscription->email }}">{{ $subscription->email }}</a></span>
                </div>

                @if($subscription->phone)
                <div class="info-row">
                    <span class="label">Phone:</span>
                    <span class="value"><a href="tel:{{ $subscription->phone }}">{{ $subscription->phone }}</a></span>
                </div>
                @endif

                @if($subscription->company_name)
                <div class="info-row">
                    <span class="label">Company:</span>
                    <span class="value">{{ $subscription->company_name }}</span>
                </div>
                @endif

                @if($subscription->company_size)
                <div class="info-row">
                    <span class="label">Company Size:</span>
                    <span class="value">{{ $subscription->company_size }}</span>
                </div>
                @endif

                @if($subscription->industry)
                <div class="info-row">
                    <span class="label">Industry:</span>
                    <span class="value">{{ $subscription->industry }}</span>
                </div>
                @endif
            </div>

            <div class="info-box">
                <h3>Demo Request Details:</h3>

                <div class="info-row">
                    <span class="label">Status:</span>
                    <span class="status-badge status-{{ strtolower(str_replace('_', '-', $subscription->status)) }}">
                        {{ $subscription->formatted_status }}
                    </span>
                </div>

                @if($subscription->preferred_demo_date)
                <div class="info-row">
                    <span class="label">Preferred Date:</span>
                    <span class="value">{{ $subscription->preferred_demo_date->format('F j, Y') }}</span>
                </div>
                @endif

                @if($subscription->preferred_demo_time)
                <div class="info-row">
                    <span class="label">Preferred Time:</span>
                    <span class="value">{{ $subscription->preferred_demo_time }}</span>
                </div>
                @endif

                @if($subscription->topic)
                <div class="info-row">
                    <span class="label">Topic to Discuss:</span>
                    <span class="value">{{ $subscription->topic }}</span>
                </div>
                @endif

                @if($subscription->message)
                <div class="info-row">
                    <span class="label">Message:</span>
                    <span class="value">{{ $subscription->message }}</span>
                </div>
                @endif

                <div class="info-row">
                    <span class="label">Contact Method:</span>
                    <span class="value">{{ ucfirst($subscription->preferred_contact_method) }}</span>
                </div>

                <div class="info-row">
                    <span class="label">Source:</span>
                    <span class="value">{{ ucfirst($subscription->source) }}</span>
                </div>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/admin/content/subscriptions/' . $subscription->id . '/edit') }}" class="action-button">
                    View in Admin Panel
                </a>
            </div>
        </div>
    </div>
</body>
</html>
