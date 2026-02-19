<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Request Status Changed</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1f2937; color: white; padding: 20px; }
        .content { background: #f9fafb; padding: 30px; }
        .status-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; text-align: center; border-left: 4px solid #f59e0b; }
        .status-badge { display: inline-block; padding: 8px 16px; border-radius: 20px; font-size: 14px; font-weight: bold; margin: 10px; }
        .status-new { background: #dbeafe; color: #1e40af; }
        .status-contacted { background: #ddd6fe; color: #5b21b6; }
        .status-demo-scheduled { background: #fef3c7; color: #92400e; }
        .status-demo-completed { background: #d1fae5; color: #065f46; }
        .status-converted { background: #d1fae5; color: #065f46; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .info-box { background: white; padding: 15px; border-radius: 8px; margin: 10px 0; }
        .action-button { display: inline-block; padding: 12px 24px; background: #3B82F6; color: white; text-decoration: none; border-radius: 6px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📋 Demo Request Status Changed</h2>
            <p style="margin: 0; opacity: 0.9;">{{ now()->format('F j, Y - H:i') }}</p>
        </div>

        <div class="content">
            <div class="info-box">
                <strong>Customer:</strong> {{ $subscription->full_name }}<br>
                <strong>Email:</strong> <a href="mailto:{{ $subscription->email }}">{{ $subscription->email }}</a><br>
                @if($subscription->company_name)
                <strong>Company:</strong> {{ $subscription->company_name }}<br>
                @endif
            </div>

            <div class="status-box">
                <h3>Status Update:</h3>
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

            @if($subscription->admin_notes)
            <div class="info-box">
                <strong>Admin Notes:</strong><br>
                {{ $subscription->admin_notes }}
            </div>
            @endif

            <div style="text-align: center;">
                <a href="{{ url('/admin/content/subscriptions/' . $subscription->id . '/edit') }}" class="action-button">
                    View in Admin Panel
                </a>
            </div>
        </div>
    </div>
</body>
</html>
