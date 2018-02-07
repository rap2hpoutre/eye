@extends('eyewitness::layout')

@section('content')

    <div class="flex w-full max-w-1200 mx-auto pt-6 mt-8 md:px-4 lg:px-8">
        <div class="bg-white border-t border-b sm:rounded sm:border shadow-inner w-full">
            <div class="flex mb-8 border-b border-grey-light pb-4">
                <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
                    <div class="h-full flex justify-center items-center">
                        @eyewitness_svg('paragraph', 'svgcolor-white')
                    </div>
                </div>

                <div class="flex w-full justify-between">
                    <h1 class="font-hairline ml-4 mt-2 text-2xl">View failed job</h1>
                    <div class="flex-1 text-right">
                        <div class="mt-2 mr-2">
                            <eye-menu title='Actions' color='svgcolor-white' background='bg-brand shadow-lg btn-pop'>
                                <div slot="dropdown" class="bg-white shadow rounded border overflow-hidden" v-cloak>
                                    <button class="block px-4 py-3 border-b text-grey-darkest bg-white hover:text-white bg-circuit-hover bg-md-hover hover:bg-brand svg-hover w-full" @click="submitForm" data-action="{{ route('eyewitness.queues.show', $queue->id) }}#failed" data-method="get">
                                        <div class="flex items-center">
                                            <div>
                                                @eyewitness_svg('double-left', 'dropdown-menu-svg', 20, 20)
                                            </div>
                                            <div class="ml-3 -mt-1 font-normal">
                                                Back
                                            </div>
                                        </div>
                                    </button>
                                    <button class="block px-4 py-3 border-b text-grey-darkest bg-white hover:text-white bg-circuit-hover bg-md-hover hover:bg-brand svg-hover w-full" @click="submitForm" data-action="{{ route('eyewitness.failedjob.retry', [$queue->id, $job->id]) }}" data-method="post">
                                        <div class="flex items-center">
                                            <div>
                                                @eyewitness_svg('refresh-02', 'dropdown-menu-svg', 20, 20)
                                            </div>
                                            <div class="ml-3 -mt-1 font-normal">
                                                Retry
                                            </div>
                                        </div>
                                    </button>
                                    <button class="block px-4 py-3 border-b text-grey-darkest bg-white hover:text-white bg-circuit-hover bg-md-hover hover:bg-brand svg-hover w-full" @click="submitForm" data-action="{{ route('eyewitness.failedjob.show', [$queue->id, $job->id]) }}" data-method="delete">
                                        <div class="flex items-center">
                                            <div>
                                                @eyewitness_svg('trash', 'dropdown-menu-svg', 20, 20)
                                            </div>
                                            <div class="ml-3 -mt-1 font-normal">
                                                Delete
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            </eye-menu>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 pb-4">

                <div class="pl-1 pb-3 pt-3">
                    <div class="flex">
                        <div class="w-1/3 md:w-1/5 text-right">
                            <p class="block tracking-wide text-grey text-right mr-1">Failed</p>
                        </div>
                        <div class="w-2/3 md:w-4/5">
                            <p class="text-grey-darker ml-4"><span class="uppercase font-mono font-semibold rounded text-sm text-white py-1 px-2 bg-orange">{{ $job->failed_at }}</span></p>
                        </div>
                    </div>
                </div>
                <div class="pl-1 pb-3 pt-3">
                    <div class="flex">
                        <div class="w-1/3 md:w-1/5 text-right">
                            <p class="block tracking-wide text-grey text-right mr-1">Driver</p>
                        </div>
                        <div class="w-2/3 md:w-4/5">
                            <p class="text-grey-darker ml-4"><span class="uppercase font-mono font-semibold rounded text-sm text-white py-1 px-2 bg-green">{{ $queue->driver }}</span></p>
                        </div>
                    </div>
                </div>
                <div class="pl-1 pb-3 pt-3">
                    <div class="flex">
                        <div class="w-1/3 md:w-1/5 text-right">
                            <p class="block tracking-wide text-grey text-right mr-1">Connection</p>
                        </div>
                        <div class="w-2/3 md:w-4/5">
                            <p class="text-grey-darker ml-4"><span class="uppercase font-mono font-semibold rounded text-sm text-white py-1 px-2 bg-blue">{{ $queue->connection }}</span></p>
                        </div>
                    </div>
                </div>
                <div class="pl-1 pb-3 pt-3">
                    <div class="flex">
                        <div class="w-1/3 md:w-1/5 text-right">
                            <p class="block tracking-wide text-grey text-right mr-1">Queue</p>
                        </div>
                        <div class="w-2/3 md:w-4/5">
                            <p class="text-grey-darker ml-4"><span class="uppercase font-mono font-semibold rounded text-sm text-white py-1 px-2 bg-blue">{{ $queue->tube }}</span></p>
                        </div>
                    </div>
                </div>
                <div class="pl-1 pb-3 pt-3">
                    <div class="flex">
                        <div class="w-1/3 md:w-1/5 text-right">
                            <p class="block tracking-wide text-grey text-right mr-1">Max tries</p>
                        </div>
                        <div class="w-2/3 md:w-4/5">
                            <p class="text-grey-darker ml-4"><span class="uppercase font-mono font-semibold rounded text-sm text-white py-1 px-2 bg-blue">{{ is_null($job->maxTries) ? 'null' : $job->maxTries }}</span></p>
                        </div>
                    </div>
                </div>
                <div class="pl-1 pb-3 pt-3">
                    <div class="flex">
                        <div class="w-1/3 md:w-1/5 text-right">
                            <p class="block tracking-wide text-grey text-right mr-1">Timeout</p>
                        </div>
                        <div class="w-2/3 md:w-4/5">
                            <p class="text-grey-darker ml-4"><span class="uppercase font-mono font-semibold rounded text-sm text-white py-1 px-2 bg-blue">{{ is_null($job->timeout) ? 'null' : $job->timeout }}</span></p>
                        </div>
                    </div>
                </div>
                <div class="pl-1 pb-3 pt-3">
                    <div class="flex">
                        <div class="w-1/3 md:w-1/5 text-right">
                            <p class="block tracking-wide text-grey text-right mr-1">Raw job ID</p>
                        </div>
                        <div class="w-2/3 md:w-4/5">
                            <p class="text-grey-darker ml-4"><span class="uppercase font-mono font-semibold rounded text-sm text-white py-1 px-2 bg-grey">{{ $job->job_id }}</span></p>
                        </div>
                    </div>
                </div>
                <div class="pl-1 pb-3 pt-3">
                    <div class="flex">
                        <div class="w-1/3 md:w-1/5 text-right">
                            <p class="block tracking-wide text-grey text-right mr-1">Payload</p>
                        </div>
                        <div class="w-2/3 md:w-4/5">
                            @foreach($job->payload as $name => $meta)
                                <p class="mb-2">
                                    <span class="ml-4 font-bold">{{ $name }}:</span>
                                    @if (is_null($meta))
                                        <span class="uppercase font-mono font-semibold rounded text-sm text-white py-1 px-2 bg-grey">null</span>
                                    @else
                                        {{ print_r($meta, true) }}
                                    @endif
                                </p>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="pl-1 pb-3 pt-3">
                    <div class="flex">
                        <div class="w-1/3 md:w-1/5 text-right">
                            <p class="block tracking-wide text-grey text-right mr-1">Exception</p>
                        </div>
                        <div class="w-2/3 md:w-4/5">
<pre class="ml-4 break-words bg-black text-white whitespace-pre-wrap px-2 font-mono">{{ $job->exception }}</pre>
                        </div>
                    </div>
                </div>
                <div class="flex mt-8 border-t pt-4">
                    <div class="flex-1">
                        <eye-btn-link link="{{ route('eyewitness.queues.show', $queue->id) }}#failed" color="bg-brand" icon='@eyewitness_svg('double-left', 'svgcolor-white h-4 w-4')'>Back</eye-btn-link>
                    </div>

                    <div class="flex-1 text-right">
                        <eye-menu title='Actions' color='svgcolor-white' background='bg-brand shadow-lg btn-pop'>
                            <div slot="dropdown" class="bg-white shadow rounded border overflow-hidden" v-cloak>
                                <button class="block px-4 py-3 border-b text-grey-darkest bg-white hover:text-white bg-circuit-hover bg-md-hover hover:bg-brand svg-hover w-full" @click="submitForm" data-action="{{ route('eyewitness.failedjob.retry', [$queue->id, $job->id]) }}" data-method="post">
                                    <div class="flex items-center">
                                        <div>
                                            @eyewitness_svg('refresh-02', 'dropdown-menu-svg', 20, 20)
                                        </div>
                                        <div class="ml-3 -mt-1 font-normal">
                                            Retry
                                        </div>
                                    </div>
                                </button>
                                <button class="block px-4 py-3 border-b text-grey-darkest bg-white hover:text-white bg-circuit-hover bg-md-hover hover:bg-brand svg-hover w-full" @click="submitForm" data-action="{{ route('eyewitness.failedjob.show', [$queue->id, $job->id]) }}" data-method="delete">
                                    <div class="flex items-center">
                                        <div>
                                            @eyewitness_svg('trash', 'dropdown-menu-svg', 20, 20)
                                        </div>
                                        <div class="ml-3 -mt-1 font-normal">
                                            Delete
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </eye-menu>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
