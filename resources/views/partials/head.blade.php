<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

<style>
    #animated-login {
        background: linear-gradient(269deg, #48c4d3, #000000, #48c4d3);
        background-size: 600% 600%;

        -webkit-animation: animate 20s ease infinite;
        -moz-animation: animate 20s ease infinite;
        -o-animation: animate 20s ease infinite;
        animation: animate 20s ease infinite;
    }

    @-webkit-keyframes animate {
        0% {
            background-position: 99% 0%
        }

        50% {
            background-position: 2% 100%
        }

        100% {
            background-position: 99% 0%
        }
    }

    @-moz-keyframes animate {
        0% {
            background-position: 99% 0%
        }

        50% {
            background-position: 2% 100%
        }

        100% {
            background-position: 99% 0%
        }
    }

    @-o-keyframes animate {
        0% {
            background-position: 99% 0%
        }

        50% {
            background-position: 2% 100%
        }

        100% {
            background-position: 99% 0%
        }
    }

    @keyframes animate {
        0% {
            background-position: 99% 0%
        }

        50% {
            background-position: 2% 100%
        }

        100% {
            background-position: 99% 0%
        }
    }
</style>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance