@foreach($eye->database()->getDatabases() as $db)
    <div class="bg-white border-t border-b sm:rounded sm:border shadow-inner mb-12">
        <div class="flex mb-8 border-b border-grey-light pb-4">
            <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
                <div class="h-full flex justify-center items-center">
                    @eyewitness_svg('database-2', 'svgcolor-white')
                </div>
            </div>
            <h1 class="font-hairline ml-4 mt-2 text-2xl">Overview for <span class="font-normal">{{ $db['connection'] }}</span></h1>
        </div>
        <div class="px-6 pb-4 border-b">
            <div class="pl-1 pb-3 pt-3">
                @eyewitness_tutorial('Each of your databases will be monitored, to ensure they are online and available. You can keep on their size each day using the chart below. Alerts can also be set if the database size gets too large (or too small).')
                <div class="px-6 pb-4">
                </div>
                <div class="flex">
                    <div class="w-1/2 md:w-1/4 text-right">
                        <p class="block tracking-wide text-grey text-right mr-1">Database status</p>
                    </div>
                    <div class="w-1/2 md:w-3/4">
                        <p class="text-grey-darker ml-4">
                            @if ($db['status'])
                                <span class="uppercase font-semibold rounded text-xs text-white bg-green py-1 px-2 mr-1">Online</span>
                            @else
                                <span class="uppercase font-semibold rounded text-xs text-white bg-red py-1 px-2 mr-1">Offline</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <div class="pl-1 pb-3 pt-3">
                <div class="flex">
                    <div class="w-1/2 md:w-1/4 text-right">
                        <p class="block tracking-wide text-grey text-right mr-1">Current size</p>
                    </div>
                    <div class="w-1/2 md:w-3/4">
                        <p class="text-grey-darker ml-4"><span class="uppercase font-mono font-semibold rounded text-xs text-white py-1 px-2 bg-blue">{{ $db['size'] }}MB</span></p>
                    </div>
                </div>
            </div>
            <div class="pl-1 pb-3 pt-3">
                <div class="flex">
                    <div class="w-1/2 md:w-1/4 text-right">
                        <p class="block tracking-wide text-grey text-right mr-1">Database driver</p>
                    </div>
                    <div class="w-1/2 md:w-3/4">
                        <p class="text-grey-darker ml-4">
                            <span class="rounded py-1 px-2 uppercase font-semibold text-white text-xs bg-grey">{{ $db['driver'] }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-brand my-6 pb-4 md:pb-0 md:mx-6 md:rounded shadow-lg text-white font-hairline">
            <div class="text-center">
                <h4 class="text-white font-normal tracking-wide pt-4">Trend of size over past 21 days</h4>
            </div>
            <div class="ct-database-{{ $db['connection'] }} md:p-6"></div>
        </div>
    </div>
@endforeach
