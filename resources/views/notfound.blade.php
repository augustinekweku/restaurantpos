<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Page Not Found</title>

        <!-- Fonts -->

        <!-- Styles -->
        <link rel="stylesheet" href="/css/all.css">
        <script>
                (function () {
                    window.Laravel = {
                        csrfToken: '{{ csrf_token() }}'
                    }
                })();
        </script>
    </head>
    <body>
<div>
    <h1 style="text-align: center;margin-top:200px;">You don't have permission to access this page</h1>
</div>
    </body>
<script src="{{mix('/js/app.js')}}"></script>
</html>
