<!DOCTYPE html>
<html>
<head>
    <title>New Task Assigned</title>
</head>
<body>
    <h1>Hello {{ $assigner->name }},</h1>
    <p>You have a new task assigned:</p>
    <p><strong>Task:</strong> {{ $taskMessage }}</p>
    <p>To view the details, click on the link below:</p>
    <a href="{{ $leadLink }}">View Lead</a>
    <p>Thank you!</p>
</body>
</html>