<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#fb503b">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Eyewitness.io | Laravel application monitor</title>

        @eyewitness_css
    </head>

    <body class="bg-black bg-circuit-dark bg-lg h-screen font-sans">
        <div class="container mx-auto h-full flex justify-center items-center" id="app">
            <div class="w-full md:w-1/2 mt-4">
                <div class="bg-white sm:rounded shadow-lg">
                    <div class="flex mb-2 border-b border-grey-light pb-4">
                        <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
                            <div class="h-full flex justify-center items-center">
                                @eyewitness_svg('eyewitness/eye', 'svgcolor-white', 48, 48)
                            </div>
                        </div>
                        <h1 class="font-hairline ml-4 mt-2 text-2xl">Login</h1>
                    </div>

                    <form v-on:submit.capture="formSubmit" class="form p-3 md:p-4" method="POST" action="{{ route('eyewitness.authenticate') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        @if (session('error'))
                            @eyewitness_error(session("error"))
                        @elseif (session('success'))
                            @eyewitness_success(session("success"))
                        @elseif (session('warning'))
                            @eyewitness_warning(session("warning"))
                        @endif

                        <div class="pt-4">
                            <div class="flex mb-8">
                                @eyewitness_svg('key-26', 'mb-3', 24, 24)
                                <eye-input type="text" name="app_token" label="App Token" value="{{ old('app_token') }}"></eye-input>
                            </div>

                            <div class="flex">
                                @eyewitness_svg('lock', 'mb-3', 24, 24)
                                <eye-input type="password" name="secret_key" label="Secret Key" value="{{ old('secret_key') }}"></eye-input>
                            </div>

                            <div class="text-right mt-8">
                                <eye-btn color="bg-brand" type="submit" icon='@eyewitness_svg('log-in', 'svgcolor-white h-4 w-4')'>Login</eye-btn>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="flex items-center justify-center w-full mt-2">
                    <a href="https://eyewitness.io" class="no-underline text-white p-1" rel="noopener" target="_blank">
                        <div class="flex items-center">
                            <span>Powered by</span> <div class="w-32 mt-1">@eyewitness_svg('eyewitness/logo')</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        @eyewitness_js
    </body>
</html>
