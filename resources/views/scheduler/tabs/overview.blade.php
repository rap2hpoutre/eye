<div class="bg-white border-t border-b sm:rounded sm:border shadow-inner">
    <div class="flex mb-8 border-b border-grey-light pb-4">
        <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
            <div class="h-full flex justify-center items-center">
                @eyewitness_svg('analytics-89', 'svgcolor-white')
            </div>
        </div>
        <h1 class="font-hairline ml-4 mt-2 text-2xl">Overview for <span class="font-normal">{{ $scheduler->command }}</span></h1>
    </div>
    <div class="px-6 pb-4 border-b">
        <div class="pl-1 pb-3 pt-3">
            <div class="flex">
                <div class="w-1/2 md:w-1/4 text-right">
                    <p class="block tracking-wide text-grey text-right mr-1">Schedule status</p>
                </div>
                <div class="w-1/2 md:w-3/4">
                    <p class="text-grey-darker ml-4">
                        @if (is_null($scheduler->healthy))
                            <span class="uppercase font-bold rounded text-xs text-white bg-orange py-1 px-3 mr-1">Unknown</span>
                        @elseif ($scheduler->healthy)
                            <span class="uppercase font-semibold rounded text-xs text-white bg-green py-1 px-2 mr-1">Working</span>
                        @else
                            <span class="uppercase font-semibold rounded text-xs text-white bg-red py-1 px-2 mr-1">Failing</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="pl-1 pb-3 pt-3">
            <div class="flex">
                <div class="w-1/2 md:w-1/4 text-right">
                    <p class="block tracking-wide text-grey text-right mr-1">Schedule</p>
                </div>
                <div class="w-1/2 md:w-3/4">
                    <p class="text-grey-darker ml-4"><span class="uppercase font-mono font-semibold rounded text-xs text-white py-1 px-2 bg-blue">{{ $scheduler->schedule }}</span></p>
                </div>
            </div>
        </div>
        <div class="pl-1 pb-3 pt-3">
            <div class="flex">
                <div class="w-1/2 md:w-1/4 text-right">
                    <p class="block tracking-wide text-grey text-right mr-1">Last run</p>
                </div>
                <div class="w-1/2 md:w-3/4">
                    <p class="text-grey-darker ml-4">
                        @if ($scheduler->last_run)
                            <td class="text-center text-sm py-3 font-hairline hidden sm:table-cell">{{ $scheduler->last_run->diffForHumans() }}</td>
                        @else
                            <td class="text-center hidden sm:table-cell"><span class="rounded py-1 px-2 uppercase font-semibold text-white text-xs bg-orange">Never</span></td>
                        @endif
                    </p>
                </div>
            </div>
        </div>
        <div class="pl-1 pb-3 pt-3">
            <div class="flex">
                <div class="w-1/2 md:w-1/4 text-right">
                    <p class="block tracking-wide text-grey text-right mr-1">Next due</p>
                </div>
                <div class="w-1/2 md:w-3/4">
                    <p class="text-grey-darker ml-4">{{ $scheduler->next_run_due->diffForHumans() }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-brand my-6 pb-4 md:pb-0 md:mx-6 md:rounded shadow-lg text-white font-hairline">
        <div class="text-center">
            <h4 class="text-white font-normal tracking-wide pt-4">Trend of run time over past 21 days</h4>
        </div>
        <div class="ct-chart md:p-6"></div>
    </div>
</div>
