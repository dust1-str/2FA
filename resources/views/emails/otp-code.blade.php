<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333333;
        }
        .content {
            text-align: center;
        }
        .otp-code {
            font-size: 24px;
            font-weight: bold;
            color: #333333;
            background-color: #f4f4f4;
            padding: 10px;
            border-radius: 4px;
            display: inline-block;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding-top: 20px;
            color: #777777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Your OTP Code</h1>
        </div>
        <div class="content">
            <p>We have received a request to access your account. Use the following OTP code to complete the process:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p>This code is valid for 2 minutes. If you did not request this code, please ignore this email.</p>
        </div>
        <div class="footer">
            <p>Thank you for using our service!</p>
        </div>
    </div>
</body>
</html>