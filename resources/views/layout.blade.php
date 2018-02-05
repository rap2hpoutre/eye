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

    <body class="bg-black bg-circuit-dark bg-lg font-sans my-overflow-y-scroll">
        <div class="flex flex-col min-h-screen w-full" id="app">

            <eye-notification></eye-notification>

            <div class="bg-brand bg-circuit bg-lg shadow-lg">
                <div class="w-full max-w-1200 mx-auto px-3">
                    <div class="flex items-center justify-between">
                        <div class="w-auto text-center text-white text-2xl font-medium">
                            <a href="{{ route('eyewitness.dashboard') }}#overview">
                                @eyewitness_svg('eyewitness/logo', 'svgcolor-white w-40')
                            </a>
                        </div>
                        <div class="w-auto flex items-center text-right text-xs">
                            <eye-menu title="menu">
                                <div slot="dropdown" class="bg-white shadow rounded border overflow-hidden" v-cloak>
                                    <a href="{{ route('eyewitness.dashboard') }}#overview" class="no-underline block px-4 py-3 border-b text-grey-darkest bg-white hover:text-white bg-circuit-hover bg-md-hover hover:bg-brand hover:bg-circuit hover:background">
                                        <div class="flex items-center">
                                            <div>
                                                @eyewitness_svg('analytics-89', 'dropdown-menu-svg', 20, 20)
                                            </div>
                                            <div class="ml-3 -mt-1">
                                                Dashboard
                                            </div>
                                        </div>
                                    </a>
                                    <a href="https://docs.eyewitness.io/" class="no-underline block px-4 py-3 border-b text-grey-darkest bg-white hover:text-white bg-circuit-hover bg-md-hover hover:bg-brand hover:bg-circuit hover:background" rel="noopener" target="_blank">
                                        <div class="flex items-center">
                                            <div>
                                                @eyewitness_svg('books', 'dropdown-menu-svg', 20, 20)
                                            </div>
                                            <div class="ml-3 -mt-1">
                                                Documentation
                                            </div>
                                        </div>
                                    </a>
                                    <a href="{{ route('eyewitness.settings.index') }}#recipients" class="no-underline block px-4 py-3 border-b text-grey-darkest bg-white hover:text-white bg-circuit-hover bg-md-hover hover:bg-brand hover:bg-circuit hover:background">
                                        <div class="flex items-center">
                                            <div>
                                                @eyewitness_svg('ui-04', 'dropdown-menu-svg', 20, 20)
                                            </div>
                                            <div class="ml-3 -mt-1">
                                                Settings
                                            </div>
                                        </div>
                                    </a>
                                    <a href="https://eyewitness.io/remote" class="no-underline block px-4 py-3 border-b text-grey-darkest bg-white hover:text-white bg-circuit-hover bg-md-hover hover:bg-brand hover:bg-circuit hover:background">
                                        <div class="flex items-center">
                                            <div>
                                                @eyewitness_svg('cable-49', 'dropdown-menu-svg', 20, 20)
                                            </div>
                                            <div class="ml-3 -mt-1">
                                                Remote addon
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="no-underline block px-4 py-3 border-b text-grey-darkest bg-white hover:text-white bg-circuit-hover bg-md-hover hover:bg-brand hover:bg-circuit hover:background" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <div class="flex items-center">
                                            <div>
                                                @eyewitness_svg('button-power', 'dropdown-menu-svg', 20, 20)
                                            </div>
                                            <div class="ml-3 -mt-1">
                                                Logout
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </eye-menu>
                        </div>
                    </div>
                </div>
            </div>


            @yield('content')

            <div class="flex items-end justify-center w-full">
                <a href="https://eyewitness.io" class="no-underline text-xs text-white pb-2 pt-1 px-1 mb-1">
                    <div class="flex items-center">
                        <span>Powered by</span> <div class="w-32 mt-1">@eyewitness_svg('eyewitness/logo')</div>
                    </div>
                </a>
            </div>

            <form id="logout-form" action="{{ route('eyewitness.logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>

            <form id="genericForm" method="POST" v-bind:action="formAction" style="display: none;">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" v-bind:value="formMethod">
            </form>
        </div>

        @eyewitness_js

        @yield('scripts')

        @include('eyewitness::notifications')

    </body>
</html>
