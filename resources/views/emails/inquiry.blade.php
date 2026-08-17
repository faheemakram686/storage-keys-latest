<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New storage inquiry</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <p>A new inquiry was submitted from the website.</p>
    <table cellpadding="8" cellspacing="0" style="border-collapse: collapse;">
        <tr>
            <td style="font-weight: bold; vertical-align: top;">Name</td>
            <td>{{ $inquiry->name }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; vertical-align: top;">Email</td>
            <td>{{ $inquiry->email }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; vertical-align: top;">Phone</td>
            <td>{{ $inquiry->phone }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; vertical-align: top;">Storage type</td>
            <td>{{ $inquiry->storage_type }}</td>
        </tr>
        <tr>
            <td style="font-weight: bold; vertical-align: top;">Message</td>
            <td>{!! nl2br(e($inquiry->message ?: '—')) !!}</td>
        </tr>
    </table>
    <p>Thanks,<br>{{ config('app.name') }}</p>
</body>
</html>
