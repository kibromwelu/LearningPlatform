<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <div style="text-align: center; padding: 20px; font-family: Arial, sans-serif;">
        <div style="background-color: #e0f7e9; border: 1px solid #b2d8c7; border-radius: 10px; padding: 30px;">
            <h2 style="color: #4CAF50;">{{ __('Password reset Successful!') }}</h2>
            <p style="font-size: 16px; color: #333;">
                {{ __('Your password has been successfully reset, and you can now log in.') }}
            </p>
            <a href="http://localhost:5173/login" style="display: inline-block; padding: 10px 20px; background-color: #4CAF50; color: #fff; text-decoration: none; border-radius: 5px; margin-top: 20px;">
                {{ __('Go to Login') }}
            </a>
        </div>
    </div>

</body>

</html>