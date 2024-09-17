<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Weeks history</title>
    </head>
    <body>
    @foreach($matches as $week => $weekMatches)
        <h2>Week №{{ $week }}</h2>
        <table>
            <thead>
            <tr>
                <th>Team 1</th>
                <th>Team 2</th>
                <th>Result</th>
            </tr>
            </thead>
            <tbody>
            @foreach($weekMatches as $match)
                <tr>
                    <td>{{ $match->teamA->name }}</td>
                    <td>{{ $match->teamB->name }}</td>
                    <td>{{ $match->win_team_goals }}-{{ $match->lost_team_goals }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </hr>
    @endforeach
    </body>
</html>
