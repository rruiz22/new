<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? '403 Forbidden') ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; color: #333; text-align: center; padding: 60px; }
        .error-container { display: inline-block; background: #fff; padding: 40px 60px; border-radius: 12px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); }
        h1 { font-size: 4em; color: #e74c3c; margin-bottom: 0.2em; }
        p { font-size: 1.2em; }
        a { color: #3498db; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>403</h1>
        <p><strong><?= esc($message ?? 'Access forbidden') ?></strong></p>
        <p>You do not have permission to access this page.</p>
        <p><a href="<?= base_url('/') ?>"><?= esc($return_home ?? 'Return to Home') ?></a></p>
    </div>
</body>
</html> 