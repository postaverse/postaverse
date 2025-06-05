<!DOCTYPE html>
<html>

<head>
    <title>Verify Your Email Address</title>
</head>

<body>
    <h1>Verify Your Email Address</h1>

    <p>Hi {{ $handle }},</p>

    <p>Thank you for registering with Postaverse! Before we can create your account, please verify your email address by
        clicking the link below.</p>

    <p><a href="{{ $url }}"
            style="display: inline-block; background-color: #3490dc; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">Verify
            Email</a></p>

    <p>If you did not create an account, no further action is required.</p>

    <p>Thanks,<br>The Postaverse Team</p>
</body>

</html>
