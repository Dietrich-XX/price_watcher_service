{{-- Обычная простенькая верстка по причине багованных x-mail компонентов которые ломали джобу --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm Your Email Address</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; background-color: #f4f4f4; padding: 20px;">

<div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 8px;">

    <h2 style="margin-top: 0; color: #333;">Confirm Your Email Address</h2>

    <p>Please confirm your email address by clicking the button below.</p>

    <p style="text-align: center;">
        <a href="{{ route('web.subscribers.email-verification', ['token' => $verificationToken]) }}"
           style="
                   display: inline-block;
                   padding: 12px 24px;
                   background-color: #2563eb;
                   color: #ffffff;
                   text-decoration: none;
                   border-radius: 5px;
                   font-weight: bold;
               ">
            Verify Email
        </a>
    </p>

    <p>If you did not create an account, no further action is required.</p>

    <p>Thanks,<br>{{ config('app.name') }}</p>

</div>

</body>
</html>
