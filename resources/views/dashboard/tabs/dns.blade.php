
@if (count(config('eyewitness.application_domains')))
    @foreach(config('eyewitness.application_domains') as $domain)
        <div class="bg-white border-t border-b sm:rounded sm:border shadow-inner mb-12">
            <div class="flex mb-8 border-b border-grey-light pb-4">
                <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
                    <div class="h-full flex justify-center items-center">
                        @eyewitness_svg('world-pin', 'svgcolor-white')
                    </div>
                </div>

                <div class="flex w-full justify-between">
                    <h1 class="font-hairline ml-4 mt-2 text-2xl">DNS: {{ $domain }}</h1>

                    <div class="mt-2 mr-2">
                        <eye-btn-link link="{{ route('eyewitness.dns.show', ['domain' => $domain]) }}" color="bg-brand" icon='@eyewitness_svg('zoom-split', 'svgcolor-white h-2 w-2')'>History</eye-btn-link>
                    </div>
                </div>
            </div>
            <div class="text-center px-6 pb-8">
                @if ($table = \Eyewitness\Eye\Repo\History\Dns::where('meta', $domain)->orderBy('created_at', 'desc')->first())
                    @include('eyewitness::dns.table', $table)
                @else
                    <div class="py-8">
                        <div class="mb-4">
                            @eyewitness_svg('wifi-off')
                        </div>
                        <p class="text-2xl text-grey-darker font-medium mb-4">No DNS records found yet</p>
                        <p class="text-grey max-w-sm mx-auto mb-6">Your domains are scheduled to be checked in a short time (usually within an hour). You can force a check right now by running<br/><span class="code">php artisan eyewitness:poll --force</span>.</p>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@else
    <div class="bg-white border-t border-b sm:rounded sm:border shadow-inner">
        <div class="flex mb-8 border-b border-grey-light pb-4">
            <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
                <div class="h-full flex justify-center items-center">
                    @eyewitness_svg('world-pin', 'svgcolor-white')
                </div>
            </div>
            <h1 class="font-hairline ml-4 mt-2 text-2xl">DNS</h1>
        </div>
        <div class="text-center px-6 pb-8">
            @eyewitness_tutorial('Eyewitness keeps an eye on your application DNS. If any changes are detected, you will receive an alert. A history of up to 5 changes are kept, so you can see what was recently changed on your DNS records.')

            <div class="py-8">
                <div class="mb-4">
                    @eyewitness_svg('wifi-off')
                </div>
                <p class="text-2xl text-grey-darker font-medium mb-4">DNS monitoring not active</p>
                <p class="text-grey max-w-sm mx-auto mb-6">You need to set your <span class="code">application_domains</span> in the <span class="code">config/eyewitness.php</span> config file to activate DNS monitoring.</p>
            </div>
        </div>
    </div>
@endif

