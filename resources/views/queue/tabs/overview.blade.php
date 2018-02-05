<div class="bg-white border-t border-b sm:rounded sm:border shadow-inner mb-12">
    <div class="flex mb-8 border-b border-grey-light pb-4">
        <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
            <div class="h-full flex justify-center items-center">
                @eyewitness_svg('design-system', 'svgcolor-white')
            </div>
        </div>

        <h1 class="font-hairline ml-4 mt-2 text-2xl">Overview for <span class="font-normal">{{ $queue->connection }} - {{ $queue->tube }}</span></h1>
    </div>

    <div class="px-6 pb-4 border-b">
        @eyewitness_tutorial('This is a quick summary of the queue. For a detailed view click the history button to view more.')

        <div class="pl-1 pb-3 pt-3">
            <div class="flex">
                <div class="w-1/2 md:w-1/4 text-right">
                    <p class="block tracking-wide text-grey text-right mr-1">Queue status</p>
                </div>
                <div class="w-1/2 md:w-3/4">
                    <p class="text-grey-darker ml-4">
                        @if (is_null($queue->healthy))
                            <span class="uppercase font-bold rounded text-xs text-white bg-orange py-1 px-3 mr-1">Unknown</span>
                        @elseif ($queue->healthy)
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
                    <p class="block tracking-wide text-grey text-right mr-1">Current wait time</p>
                </div>
                <div class="w-1/2 md:w-3/4">
                    <p class="text-grey-darker ml-4">
                        <span class="uppercase font-mono font-semibold rounded text-xs text-white py-1 px-2 bg-blue">{{ $queue->current_wait_time }} secs</span>
                    </p>
                </div>
            </div>
        </div>
        <div class="pl-1 pb-3 pt-3">
            <div class="flex">
                <div class="w-1/2 md:w-1/4 text-right">
                    <p class="block tracking-wide text-grey text-right mr-1">Pending jobs</p>
                </div>
                <div class="w-1/2 md:w-3/4">
                    <div class="w-1/2 md:w-3/4">
                        <p class="text-grey-darker ml-4"><span class="uppercase font-mono font-semibold rounded text-xs text-white py-1 px-2 bg-blue">{{ $eye->queue()->getPendingJobsCount($queue) }}</span></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="pl-1 pb-3 pt-3">
            <div class="flex">
                <div class="w-1/2 md:w-1/4 text-right">
                    <p class="block tracking-wide text-grey text-right mr-1">Failed jobs</p>
                </div>
                <div class="w-1/2 md:w-3/4">
                    <p class="text-grey-darker ml-4"><span class="uppercase font-mono font-semibold rounded text-xs text-white py-1 px-2 bg-blue">{{ $eye->queue()->getFailedJobsCount($queue) }}</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
