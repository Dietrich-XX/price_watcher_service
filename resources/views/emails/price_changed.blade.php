{{-- Обычная простенькая верстка по причине багованных x-mail компонентов которые ломали джобу --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Price Changed Notification</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px;">
        <h2 style="margin-top: 0; color: #333;">Price Update Alert</h2>
        <p>The price for the following item has changed:</p>
        <p>
            <strong>URL:</strong>
            <a href="{{ $priceSubscription->url }}" target="_blank">{{ $priceSubscription->url }}</a>
        </p>

        <p>
            <strong>Current Price:</strong> ${{ number_format($priceSubscription->current_price ?? 0, 2) }}
        </p>

        @if($priceSubscription->last_checked_at)
            <p>
                <strong>Last Checked At:</strong> {{ $priceSubscription->last_checked_at->format('Y-m-d H:i:s') }}
            </p>
        @endif

        <p style="text-align: center; margin-top: 20px;">
            <a href="{{ $priceSubscription->url }}"
               style="
                   display: inline-block;
                   padding: 12px 24px;
                   background-color: #2563eb;
                   color: #ffffff;
                   text-decoration: none;
                   border-radius: 5px;
                   font-weight: bold;
               ">
                View Item
            </a>
        </p>

        <p>If you did not subscribe to this item, no further action is required.</p>

        <p>Thanks,<br>{{ config('app.name') }}</p>
    </div>
</body>
</html>
