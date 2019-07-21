<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet" media="all">

</head>
<body>
<div id="app">
    <router-view></router-view>
</div>
@routes()
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
