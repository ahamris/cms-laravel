<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('mail.demo_request.subject') }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #3B82F6; color: white; padding: 20px; text-align: center; }
        .content { background: #f9fafb; padding: 30px; }
        .info-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .info-row { padding: 10px 0; border-bottom: 1px solid #e5e7eb; }
        .info-row:last-child { border-bottom: none; }
        .label { font-weight: bold; color: #6b7280; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ __('mail.demo_request.title') }}</h1>
        </div>

        <div class="content">
            <p>{{ __('mail.demo_request.greeting', ['name' => $subscription->full_name]) }}</p>

            <p>{{ __('mail.demo_request.intro') }}</p>

            <div class="info-box">
                <h3>{{ __('mail.demo_request.request_details_title') }}</h3>

                <div class="info-row">
                    <span class="label">{{ __('mail.demo_request.labels.name') }}</span> {{ $subscription->full_name }}
                </div>

                <div class="info-row">
                    <span class="label">{{ __('mail.demo_request.labels.email') }}</span> {{ $subscription->email }}
                </div>

                @if($subscription->phone)
                <div class="info-row">
                    <span class="label">{{ __('mail.demo_request.labels.phone') }}</span> {{ $subscription->phone }}
                </div>
                @endif

                @if($subscription->company_name)
                <div class="info-row">
                    <span class="label">{{ __('mail.demo_request.labels.company') }}</span> {{ $subscription->company_name }}
                </div>
                @endif

                @if($subscription->preferred_demo_date)
                <div class="info-row">
                    <span class="label">{{ __('mail.demo_request.labels.preferred_date') }}</span> {{ format_localized_date_long($subscription->preferred_demo_date) }}
                </div>
                @endif

                @if($subscription->preferred_demo_time)
                <div class="info-row">
                    <span class="label">{{ __('mail.demo_request.labels.preferred_time') }}</span> {{ $subscription->preferred_demo_time }}
                </div>
                @endif
            </div>

            <p>{{ __('mail.demo_request.confirmation') }}</p>

            <p>{{ __('mail.demo_request.contact') }}</p>

            <p>{{ __('mail.demo_request.closing') }}<br>
            <strong>{{ __('mail.demo_request.team_name') }}</strong></p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ __('mail.demo_request.copyright') }}</p>
        </div>
    </div>
</body>
</html>
