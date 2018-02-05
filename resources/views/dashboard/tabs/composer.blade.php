<div class="bg-white border-t border-b sm:rounded sm:border shadow-inner mb-12">
    <div class="flex mb-8 border-b border-grey-light pb-4">
        <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
            <div class="h-full flex justify-center items-center">
                @eyewitness_svg('cctv', 'svgcolor-white')
            </div>
        </div>

        <h1 class="font-hairline ml-4 mt-2 text-2xl">Composer.lock</h1>

    </div>

    <div class="px-6 pb-4">
        @eyewitness_tutorial('Once per day Eyewitness will run a scan of your composer.lock file using the SensioLabs Security scanner. This checks your composer for any publically known security issues in your packages.')
    </div>

    @if ($eye->status()->isSick('composer'))
        <div class="flex items-end justify-center w-full items-center pb-8">
            <div class="f-modal-alert pr-8">
                <div class="f-modal-icon f-modal-warning scaleWarning">
                    <span class="f-modal-body pulseWarningIns"></span>
                    <span class="f-modal-dot pulseWarningIns"></span>
                </div>
            </div>
            <p class="text-2xl text-grey-darker font-medium">Your Composer.lock has known vulnerabilities</p>
        </div>

        @foreach (\Eyewitness\Eye\Repo\History\Composer::first()->record as $id => $alert)
            <div class="border-t">
                <div class="pl-1 pb-3 pt-3">
                    <div class="flex">
                        <div class="w-1/2 md:w-1/4 text-right">
                            <p class="block tracking-wide text-grey text-right mr-1">Package</p>
                        </div>
                        <div class="w-1/2 md:w-3/4">
                            <p class="text-grey-darker ml-4">{{ $id }}</p>
                        </div>
                    </div>
                </div>
                <div class="pl-1 pb-3 pt-3">
                    <div class="flex">
                        <div class="w-1/2 md:w-1/4 text-right">
                            <p class="block tracking-wide text-grey text-right mr-1">Version</p>
                        </div>
                        <div class="w-1/2 md:w-3/4">
                            <p class="text-grey-darker ml-4">{{ $alert['version'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="pl-1 pb-3 pt-3">
                    <div class="flex">
                        <div class="w-1/2 md:w-1/4 text-right">
                            <p class="block tracking-wide text-grey text-right mr-1">Issues</p>
                        </div>
                        <div class="w-1/2 md:w-3/4">
                            @foreach ($alert['advisories'] as $issue)
                                <p class="text-grey-darker ml-4 pb-4">
                                    - {{ $issue['title'] }} -
                                    @if (isset($issue['cve']) && (! empty($issue['cve'])))
                                        (CVE: {{ $issue['cve'] }}) -
                                    @endif
                                    @if (isset($issue['link']))
                                        <a href="{{ $issue['link'] }}">{{ $issue['link'] }}</a>
                                    @endif
                                </p>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    @elseif ($eye->status()->isHealthy('composer'))
        <div class="flex items-end justify-center w-full items-center pb-8">
            <div class="f-modal-alert pr-8">
                <div class="f-modal-icon f-modal-success animate scaleWarning">
                    <span class="f-modal-line f-modal-tip animateSuccessTip"></span>
                    <span class="f-modal-line f-modal-long animateSuccessLong"></span>
                    <div class="f-modal-placeholder"></div>
                    <div class="f-modal-fix"></div>
                </div>
            </div>
            <p class="text-2xl text-grey-darker font-medium">Your Composer.lock has no known vulnerabilities</p>
        </div>
    @else
        <div class="text-center py-8">
            <div class="mb-4">
                @eyewitness_svg('wifi-off')
            </div>
            <p class="text-2xl text-grey-darker font-medium mb-4">Composer check not yet completed</p>
            <p class="text-grey max-w-sm mx-auto mb-6">This page will update once your Composer.lock file has been scanned.  You can force a check right now by running<br/><span class="code">php artisan eyewitness:poll --force</span>.</p>
        </div>
    @endif
</div>
