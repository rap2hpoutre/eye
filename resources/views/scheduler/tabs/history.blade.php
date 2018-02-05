<div class="bg-white border-t border-b sm:rounded sm:border shadow-inner">
    <div class="flex mb-8 border-b border-grey-light pb-4">
        <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
            <div class="h-full flex justify-center items-center">
                @eyewitness_svg('timeline', 'svgcolor-white')
            </div>
        </div>
        <h1 class="font-hairline ml-4 mt-2 text-2xl">History for <span class="font-normal">{{ $scheduler->command }}</span></h1>
    </div>
    <div class="text-center px-6 pb-8">
        @eyewitness_tutorial('Here is the history of this scheduled command. Each time the schedule runs, the results are captured below. Any output by the cron is also stored (although you can disable this in the config file if needed).')

        @if (count($scheduler->history))
            <table class="w-full" cellspacing="0" cellpadding="0">
                <thead>
                    <tr>
                        <th class="text-center text-brand text-xl font-thin border-b py-2">Run at</th>
                        <th class="text-center text-brand text-xl font-thin border-b py-2">Time to run</th>
                        <th class="text-center text-brand text-xl font-thin border-b py-2">Exit status</th>
                        <th class="text-center text-brand text-xl font-thin border-b py-2">Output</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($scheduler->history as $history)
                        <tr>
                            <td class="text-center text-sm py-3 text-grey-darker font-hairline">{{ $history->created_at->format('Y-m-d H:i:s') }}  <span class="text-grey italic">({{ $history->created_at->diffForHumans() }})</span></td>
                            <td class="text-center text-sm py-3 text-grey-darker font-hairlinel">{{ round($history->time_to_run, 1) }}s</td>
                            <td class="text-center text-sm py-3">
                                @if (is_null($history->exitcode))
                                    <span class="uppercase font-bold rounded text-xs text-white bg-orange py-1 px-2 mr-1">Unknown</span>
                                @elseif ($history->exitcode > 0)
                                    <span class="uppercase font-semibold rounded text-xs text-white bg-red py-1 px-2 mr-1">Error</span>
                                @else
                                    <span class="uppercase font-semibold rounded text-xs text-white bg-green py-1 px-2 mr-1">Ok</span>
                                @endif
                            </td>
                            <td class="text-center text-xs py-3">
                                @if (is_null($history->output) || ($history->output == ""))
                                    <span class="bg-grey text-white font-semibold py-1 px-2 rounded">@eyewitness_svg('eye-ban-18', 'svgcolor-white h-2 w-2') No output</span>
                                @else
                                    <eye-modal title="{{ $history->created_at->format('Y-m-d H:i:s') }}">
                                        <div slot="button">@eyewitness_svg('zoom-split', 'svgcolor-white h-2 w-2') View</div>
                                        <div slot="body" v-cloak>
                                            <div class="bg-black text-white font-mono w-full text-left text-xs p-4">
                                                <pre>{{ $history->output }}</pre>
                                            </div>
                                        </div>
                                    </eye-modal>
                                @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="py-8">
                <div class="mb-4">
                    @eyewitness_svg('wifi-off')
                </div>
                <p class="text-2xl text-grey-darker font-medium mb-4">No recorded data from this scheduled cron</p>
                <p class="text-grey max-w-xs mx-auto mb-6">Once the first run is received, you can view the history here.</p>
            </div>
        @endif
    </div>
</div>

