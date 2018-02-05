@if(count($queues))
    @foreach($queues as $queue)
        <div class="bg-white border-t border-b sm:rounded sm:border shadow-inner mb-12">
            <div class="flex mb-8 border-b border-grey-light pb-4">
                <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
                    <div class="h-full flex justify-center items-center">
                        @eyewitness_svg('design-system', 'svgcolor-white')
                    </div>
                </div>

                <div class="flex w-full justify-between">
                    <h1 class="font-hairline ml-4 mt-2 text-2xl">Overview for <span class="font-normal">{{ $queue->connection }} - {{ $queue->tube }}</span></h1>

                    <div class="mt-2 mr-2">
                        <eye-btn-link link="{{ route('eyewitness.queues.show', $queue->id) }}" color="bg-brand" icon='@eyewitness_svg('zoom-split', 'svgcolor-white h-2 w-2')'>More</eye-btn-link>
                    </div>
                </div>
            </div>

            <div class="px-6 pb-4 border-b">
                @eyewitness_tutorial('Click on the "more" button in the top right to get a detailed view of this queue.')

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

            <div class="bg-brand my-6 pb-4 md:pb-0 md:mx-6 md:rounded shadow-lg text-white font-hairline">
                <div class="text-center">
                    <h4 class="text-white font-normal tracking-wide pt-4">Total processed jobs over past 14 days</h4>
                </div>
                <div class="ct-queue-{{ $queue->id }} md:p-6"></div>
            </div>
        </div>
    @endforeach
@else
    <div class="bg-white border-t border-b sm:rounded sm:border shadow-inner">
        <div class="flex mb-8 border-b border-grey-light pb-4">
            <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
                <div class="h-full flex justify-center items-center">
                    @eyewitness_svg('design-system', 'svgcolor-white')
                </div>
            </div>
            <h1 class="font-hairline ml-4 mt-2 text-2xl">Queues</h1>
        </div>
        <div class="text-center px-6 pb-8">
            @eyewitness_tutorial('Eyewitness will automatically monitor each of your queues and record performance etc. You can set different alerts and notifications based upon how your queue is performing.')

            <div class="py-8">
                <div class="mb-4">
                    @eyewitness_svg('wifi-off')
                </div>
                <p class="text-2xl text-grey-darker font-medium mb-4">Queue monitoring not active</p>
                <p class="text-grey-dark max-w-sm mx-auto mb-6">Make sure you have run <span class="code">queue:restart</span> so that we can monitor the queues.</p>
                <p class="text-grey-dark max-w-sm mx-auto mb-6">If you are testing Eyewitness on your development environment - you'll need to run the queue at least once (<em>you can use your normal command, or <span class="code">queue:work --once</span> should work in most instances</em>).</p>
                <p class="text-grey-dark max-w-sm mx-auto mb-6">You also need your scheduler running (<em>or you can manually run <span class="code">eyewitness:poll</span> on your development computer to simulate the capture of data.)</em></p>
            </div>
        </div>
    </div>
@endif
