<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verification Failed</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; text-align: center; padding: 50px; }
        .card { background: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); display: inline-block; }
        .error { color: #dc3545; font-size: 24px; margin-bottom: 20px; }
        a.button { display: inline-block; padding: 10px 20px; background: #007bff; color: #fff; text-decoration: none; border-radius: 5px; }
        a.button:hover { background: #0056b3; }
    </style>
</head>
<body>
<div class="card">
    <div class="error">❌ Verification Failed</div>
    <p>{{ $errorMessage }}</p>
</div>
</body>
</html>
