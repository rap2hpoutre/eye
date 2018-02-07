<div class="bg-white border-t border-b sm:rounded sm:border shadow-inner mb-12">
    <div class="flex mb-8 border-b border-grey-light pb-4">
        <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
            <div class="h-full flex justify-center items-center">
                @eyewitness_svg('paragraph', 'svgcolor-white')
            </div>
        </div>

        <div class="flex w-full justify-between">
            <h1 class="font-hairline ml-4 mt-2 text-2xl">Failed jobs for <span class="font-normal">{{ $queue->connection }} - {{ $queue->tube }}</span></h1>

            <div class="mt-2 mr-2">
                <eye-menu title='Actions' color='svgcolor-white' background='bg-brand shadow-lg btn-pop'>
                    <div slot="dropdown" class="bg-white shadow rounded border overflow-hidden" v-cloak>
                        <button class="block px-4 py-3 border-b text-grey-darkest bg-white hover:text-white bg-circuit-hover bg-md-hover hover:bg-brand svg-hover w-full" @click="submitForm" data-action="{{ route('eyewitness.failedjob.retry-all', $queue->id) }}" data-method="post">
                            <div class="flex items-center">
                                <div>
                                    @eyewitness_svg('refresh-02', 'dropdown-menu-svg', 20, 20)
                                </div>
                                <div class="ml-3 -mt-1 font-normal">
                                    Retry All
                                </div>
                            </div>
                        </button>
                        <button class="block px-4 py-3 border-b text-grey-darkest bg-white hover:text-white bg-circuit-hover bg-md-hover hover:bg-brand svg-hover w-full" @click="submitForm" data-action="{{ route('eyewitness.failedjob.destroy-all', $queue->id) }}" data-method="delete">
                            <div class="flex items-center">
                                <div>
                                    @eyewitness_svg('trash', 'dropdown-menu-svg', 20, 20)
                                </div>
                                <div class="ml-3 -mt-1 font-normal">
                                    Delete All
                                </div>
                            </div>
                        </button>
                    </div>
                </eye-menu>
            </div>
        </div>
    </div>

    <div class="px-6 pb-4 border-b">
        @eyewitness_tutorial('Eyewitness will list all your failed jobs for this queue.')

        @if ($eye->queue()->getFailedJobsCount($queue) > 0)
            <table class="w-full" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th class="text-center text-brand text-xl font-thin border-b py-2">ID</th>
                        <th class="text-center text-brand text-xl font-thin border-b py-2">Job</th>
                        <th class="text-center text-brand text-xl font-thin border-b py-2">Failed At</th>
                        <th class="border-b py-2" width="100px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($eye->queue()->getFailedJobs($queue) as $job)
                        <tr>
                            <td class="text-center text-sm py-3 text-grey-darker font-hairline">{{ $job->id }}</td>
                            <td class="text-center text-sm py-3 text-grey-darker font-hairline">{{ $job->job }}</td>
                            <td class="text-center text-sm py-3 text-grey-darker font-hairline">{{ $job->failed_at }}</td>
                            <td>
                                <eye-menu title='Actions' color='svgcolor-white' background='bg-brand shadow-lg btn-pop'>
                                    <div slot="dropdown" class="bg-white shadow rounded border overflow-hidden" v-cloak>
                                        <button class="block px-4 py-3 border-b text-grey-darkest bg-white hover:text-white bg-circuit-hover bg-md-hover hover:bg-brand svg-hover w-full" @click="submitForm" data-action="{{ route('eyewitness.failedjob.show', [$queue->id, $job->id]) }}" data-method="get">
                                            <div class="flex items-center">
                                                <div>
                                                    @eyewitness_svg('zoom-split', 'dropdown-menu-svg', 20, 20)
                                                </div>
                                                <div class="ml-3 -mt-1 font-normal">
                                                    View
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
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="py-8 text-center">
                <div class="mb-4">
                    @eyewitness_svg('like')
                </div>
                <p class="text-2xl text-grey-darker font-medium mb-4">No failed jobs!</p>
                <p class="text-grey-dark max-w-sm mx-auto mb-6">Yay - this queue does not have any failed jobs.</p>
            </div>
        @endif
    </div>
</div>
