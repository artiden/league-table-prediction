<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>League simulation</title>

        @vite(['resources/js/app.js'])
    </head>
    <body>
        <div id="app">
            <root-component history-url="{{ route('history') }}"/>
        </div>
    </body>
</html>
