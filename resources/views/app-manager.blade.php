<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        @vite(['resources/common/css/app.css', 'resources/manager/js/app.ts'])
        @routes
        @inertiaHead
    </head>

    <body>
        @inertia
    </body>

</html>
