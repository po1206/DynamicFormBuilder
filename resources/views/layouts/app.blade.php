<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <style>
            body, html {
                margin: 2px;
                padding: 0;
                height: calc(100% - 4px);
            }
        </style>
        @yield('custom_css')
    </head>
    <body role="application"> 
        @yield('content')
        @yield('custom_js')
    </body>
</html>
