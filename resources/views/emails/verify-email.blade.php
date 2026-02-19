<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: bold;
        }
        .header p {
            margin: 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .welcome-message {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #667eea;
        }
        .verify-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
            transition: transform 0.2s;
        }
        .verify-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3);
        }
        .security-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 20px;
            border-radius: 6px;
            margin: 25px 0;
        }
        .security-notice strong {
            color: #856404;
        }
        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }
        .alternative-link {
            background: #e9ecef;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            word-break: break-all;
            font-size: 12px;
            color: #495057;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="icon">📧</div>
            <h1>Welcome to {{ $appName }}!</h1>
            <p>Please verify your email address to get started</p>
        </div>

        <div class="content">
            <div class="welcome-message">
                <h3>Hello {{ $user->name }}!</h3>
                <p>Thank you for registering with {{ $appName }}. To complete your registration and start using all our features, please verify your email address by clicking the button below.</p>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $verificationUrl }}" class="verify-button">
                    ✓ Verify Email Address
                </a>
            </div>

            <div class="security-notice">
                <strong>🔒 Security Notice:</strong>
                <p>This verification link will expire in 60 minutes for your security. If you didn't create an account with {{ $appName }}, please ignore this email.</p>
            </div>

            <p><strong>Having trouble with the button?</strong> Copy and paste the following link into your browser:</p>
            <div class="alternative-link">
                {{ $verificationUrl }}
            </div>

            <p>Once your email is verified, you'll be able to:</p>
            <ul>
                <li>Access your personalized dashboard</li>
                <li>Receive important account notifications</li>
                <li>Use all platform features securely</li>
                <li>Reset your password if needed</li>
            </ul>
        </div>

        <div class="footer">
            <p><strong>Need help?</strong> Contact our support team if you have any questions.</p>
            <p>This is an automated message from {{ $appName }}. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
