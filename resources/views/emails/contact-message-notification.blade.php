<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('frontend.new_contact_message_subject') }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #111827;">
    <h2 style="margin-bottom: 16px;">{{ __('frontend.new_contact_message_subject') }}</h2>

    <p><strong>Name:</strong> {{ $messageData->name }}</p>
    <p><strong>Email:</strong> {{ $messageData->email }}</p>
    <p><strong>Phone:</strong> {{ $messageData->phone ?: '-' }}</p>
    <p><strong>Subject:</strong> {{ $messageData->subject ?: '-' }}</p>
    <p><strong>Sent At:</strong> {{ $messageData->created_at?->format('Y-m-d H:i:s') }}</p>

    <hr style="margin: 18px 0; border: none; border-top: 1px solid #e5e7eb;">

    <p><strong>Message:</strong></p>
    <p style="white-space: pre-wrap;">{{ $messageData->message }}</p>
</body>
</html>

