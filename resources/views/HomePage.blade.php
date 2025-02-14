<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Ticket Master</title>
        <link rel="stylesheet" type="text/css" href="/css/main.css" />
        @viteReactRefresh
        @vite('resources/js/app.jsx')
    </head>
    <body>
        <div id="app"></div>

    </body>
    <script>
        window.CSRF_TOKEN = '{{csrf_token()}}';
        window.CSRF_FORM = '@csrf';
        window.LOGIN = @json()
    </script>

</html>
