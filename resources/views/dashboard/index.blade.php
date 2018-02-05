@extends('eyewitness::layout')

@section('content')
    <div class="md:hidden bg-white">
        <eye-mobile-menu>
            <div slot="name">Monitors</div>
            <div slot="dropdown" v-cloak>
                <div class="container mx-auto px-4 border-b border-grey-light">
                    @include('eyewitness::dashboard.menu')
                </div>
            </div>
        </eye-mobile-menu>
    </div>

    <div class="flex flex-grow w-full max-w-1200 mx-auto pt-6 pb-8 mt-8">
        <div class="hidden md:block">
            <div class="w-245 md:px-2 lg:px-4 lg:mr-2 flex flex-none flex-col">
                <div class="bg-white border-t border-b sm:rounded sm:border shadow-inner pb-2">
                    <div class="flex mb-2 border-b border-grey-light pb-4">
                        <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
                            <div class="h-full flex justify-center items-center">
                                @eyewitness_svg('eyewitness/eye', 'svgcolor-white', 48, 48)
                            </div>
                        </div>
                        <h1 class="font-hairline ml-4 mt-2 text-2xl">Monitors</h1>
                    </div>

                    @include('eyewitness::dashboard.menu')
                </div>
            </div>
        </div>

        <div class="w-full md:px-2 lg:px-4">
            <eye-tab name="#overview" v-cloak>
                @include('eyewitness::dashboard.tabs.overview')
            </eye-tab>

            @if (config('eyewitness.monitor_scheduler'))
                <eye-tab name="#scheduler" v-cloak>
                    @include('eyewitness::dashboard.tabs.scheduler')
                </eye-tab>
            @endif

            @if (config('eyewitness.monitor_queue'))
                <eye-tab name="#queue" v-cloak>
                    @include('eyewitness::dashboard.tabs.queue')
                </eye-tab>
            @endif

            @if (config('eyewitness.monitor_database'))
                <eye-tab name="#database" v-cloak>
                    @include('eyewitness::dashboard.tabs.database')
                </eye-tab>
            @endif

            @if (config('eyewitness.monitor_dns'))
                <eye-tab name="#dns" v-cloak>
                    @include('eyewitness::dashboard.tabs.dns')
                </eye-tab>
            @endif

            @if (config('eyewitness.monitor_composer'))
                <eye-tab name="#composer" v-cloak>
                    @include('eyewitness::dashboard.tabs.composer')
                </eye-tab>
            @endif

            @if (config('eyewitness.monitor_ssl'))
                <eye-tab name="#ssl" v-cloak>
                    @include('eyewitness::dashboard.tabs.ssl')
                </eye-tab>
            @endif

            @if (config('eyewitness.display_helpers'))
                <eye-tab name="#custom" v-cloak>
                    @include('eyewitness::dashboard.tabs.custom-example')
                </eye-tab>
            @endif

            @foreach($eye->getCustomWitnesses() as $witness)
                <eye-tab name="#{{ $witness->getSafeName() }}" v-cloak>
                    @include('eyewitness::dashboard.tabs.custom', ['witness' => $witness])
                </eye-tab>
            @endforeach

            <eye-tab name="#notifications" v-cloak>
                @include('eyewitness::dashboard.tabs.notifications')
            </eye-tab>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        window.bus.setDefaultTab('#overview');

        @if (config('eyewitness.monitor_database'))
            @foreach($eye->database()->getDatabases() as $db)
                chartData = {
                    labels: {!! json_encode($transformer->generateDatabase($db['connection'])['day']) !!},
                    series: [
                        {!! json_encode($transformer->generateDatabase($db['connection'])['count']) !!},
                    ]
                };

                new Chartist.Bar('.ct-database-{{ $db['connection'] }}', chartData, window.chartOptionBar('Date (day of month)', 'Size', 'mb'));
            @endforeach
        @endif

        @if (config('eyewitness.monitor_queue'))
            @foreach($queues as $queue)
                chartData = {
                    labels: {!! json_encode($transformer->generateQueue($queue)['day']) !!},
                    series: [
                        {!! json_encode($transformer->generateQueue($queue)['total_process_count']) !!},
                    ]
                };

                new Chartist.Bar('.ct-queue-{{ $queue->id }}', chartData, window.chartOptionBar('Day', 'Jobs Processed', ' jobs'));
            @endforeach
        @endif

        @foreach($eye->getCustomWitnesses() as $witness)
            chartData = {
                labels: {!! json_encode($transformer->generateCustom($witness)['day']) !!},
                series: [
                    {!! json_encode($transformer->generateCustom($witness)['count']) !!},
                ]
            };

            new Chartist.Bar('.ct-custom-{{ $witness->getSafeName() }}', chartData, window.chartOptionBar('Day', 'Count', ''));
        @endforeach
    </script>
@endsection
