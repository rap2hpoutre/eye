<div class="bg-white border-t border-b sm:rounded sm:border shadow-inner">
    <div class="flex mb-8 border-b border-grey-light pb-4">
        <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
            <div class="h-full flex justify-center items-center">
                @eyewitness_svg('calendar-grid-58', 'svgcolor-white')
            </div>
        </div>
        <h1 class="font-hairline ml-4 mt-2 text-2xl">Scheduler</h1>
    </div>
    <div class="text-center px-6 pb-8">
        @eyewitness_tutorial('Eyewitness will automatically track each of your scheduled crons. They are added automatically tracked and added here when they run, so no need to manually add anything yourself.')

        @if (count($schedulers))
            <table class="w-full" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th class="text-center text-brand text-xl font-thin border-b py-2">Name</th>
                        <th class="text-center text-brand text-xl font-thin border-b py-2 hidden sm:table-cell">Schedule</th>
                        <th class="text-center text-brand text-xl font-thin border-b py-2 hidden sm:table-cell">Last run</th>
                        <th class="text-center text-brand text-xl font-thin border-b py-2 hidden md:table-cell">Next due</th>
                        <th class="text-center text-brand text-xl font-thin border-b py-2">Status</th>
                        <th class="text-center text-brand text-xl font-thin border-b py-2">More</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedulers as $scheduler)
                        <tr>
                            <td class="text-center text-sm py-3 font-hairline">{{ $scheduler->command }}</td>
                            <td class="text-center text-sm py-3 font-hairline hidden sm:table-cell"><span class="rounded py-1 px-2 uppercase font-semibold text-white text-sm bg-blue">{{ $scheduler->schedule }}</span></td>
                            @if ($scheduler->latest_history)
                                <td class="text-center text-sm py-3 font-hairline hidden sm:table-cell">{{ $scheduler->latest_history->created_at->diffForHumans() }}</td>
                            @else
                                <td class="text-center hidden sm:table-cell"><span class="rounded py-1 px-2 uppercase font-semibold text-white text-xs bg-orange">Never</span></td>
                            @endif
                            <td class="text-center text-sm py-3 font-hairline hidden md:table-cell">{{ $scheduler->next_run_due->diffForHumans() }}</td>
                            <td class="text-center text-sm py-3">
                                @if (is_null($scheduler->healthy))
                                    <span class="shadow uppercase font-bold rounded text-xs text-white bg-orange p-1 mr-1">
                                        <span class="hidden md:table-cell">Unknown</span>
                                        <span class="md:hidden">@eyewitness_svg('alert-que', 'svgcolor-white w-3 h-3')</span>
                                    </span>
                                @elseif ($scheduler->healthy)
                                    <span class="shadow uppercase font-semibold rounded text-xs text-white bg-green p-1 px-1 mr-1">
                                        <span class="hidden md:table-cell">Working</span>
                                        <span class="md:hidden">@eyewitness_svg('check-simple', 'svgcolor-white w-3 h-3')</span>
                                    </span>
                                @else
                                    <span class="shadow uppercase font-semibold rounded text-xs text-white bg-red p-1 px-1 mr-1">
                                        <span class="hidden md:table-cell">Error</span>
                                        <span class="md:hidden">@eyewitness_svg('bold-remove', 'svgcolor-white w-3 h-3')</span>
                                    </span>
                                @endif
                            </td>
                            <td class="text-center text-xs py-3"><eye-btn-link link="{{ route('eyewitness.schedulers.show', $scheduler->id) }}" color="bg-brand" icon='@eyewitness_svg('zoom-split', 'svgcolor-white h-2 w-2')'>View</eye-btn-link>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="py-8">
                <div class="mb-4">
                    @eyewitness_svg('wifi-off')
                </div>
                <p class="text-2xl text-grey-darker font-medium mb-4">No cron schedulers monitored yet</p>
                <p class="text-grey max-w-xs mx-auto mb-6">Cron schedules will be automatically added here next time your scheduler runs.</p>
            </div>
        @endif
    </div>
</div>

