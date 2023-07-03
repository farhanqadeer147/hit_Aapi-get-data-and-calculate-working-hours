<!DOCTYPE html>
<html>
<head>
    <title>Login Times</title>
</head>
<body>
    <h1>Login Times</h1>

    <table>
        <thead>
            <tr>
                <th>Email</th>
                <th>Total Login Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($loginTimes as $email => $totalTime)
                <tr>
                    <td>{{ $email }}</td>
                    <td>{{ floor($totalTime / 60) }} hours {{ $totalTime % 60 }} minutes</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
