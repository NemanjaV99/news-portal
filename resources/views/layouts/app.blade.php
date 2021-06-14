<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page-title')</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;1,400&family=Roboto:wght@400;700;900&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/9e919944e8.js" crossorigin="anonymous"></script>
    <!-- Style -->
    <link rel="stylesheet" href="{{asset('assets/css/app.css')}}">
    @yield('page-styles')
    <!-- Script -->
    <script src="{{asset('assets/js/app.js')}}"></script>
</head>
<body>
    @yield('page-content')
    @yield('page-scripts')
    <script>

        // Format dates to human readable format
        let dateElements = document.getElementsByClassName('date-format');

        for (i = 0; i < dateElements.length; i++) {

            let newDate = new Date(dateElements[i].textContent);

            dateElements[i].textContent = newDate.toDateString();

        }

    </script>
</body>
</html>