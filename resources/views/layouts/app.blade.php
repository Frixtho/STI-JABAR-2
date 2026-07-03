<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'PLN Financial' }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <script>
        tailwind.config = {
            theme: {
                darkMode : 'class',
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                    colors: {
                        pln: {
                            50: '#eef7f7',
                            100: '#d7ecec',
                            600: '#0f4c4c',
                            700: '#0b3d3d',
                            800: '#093333',
                            900: '#062525',
                        },
                        accent: {
                            400: '#e8e14a',
                            500: '#d9d024',
                        },
                    },
                },
            },
        };
    </script>
</head>
<body class="antialiased font-sans bg-gray-50">
    @yield('content')
</body>
</html>