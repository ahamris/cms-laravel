<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Component Preview - {{ $tailwindPlus->component_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background: #f9fafb;
        }
    </style>
</head>
<body>
    {!! $tailwindPlus->code !!}
</body>
</html>

