@extends('eyewitness::layout')

@section('content')

    <div class="md:hidden bg-white">
        <eye-mobile-menu>
            <div slot="name">Options</div>
            <div slot="dropdown" v-cloak>
                <div class="container mx-auto px-4 border-b border-grey-light pt-3 pb-3">
                    @include('eyewitness::scheduler.menu')
                </div>
            </div>
        </eye-mobile-menu>
    </div>

    <div class="flex flex-grow w-full max-w-1200 mx-auto pt-6 pb-8 mt-8">
        <div class="hidden md:block">
            <div class="w-245 md:px-2 lg:px-4 lg:mr-2 flex flex-none flex-col">
                <div class="bg-white border-t border-b sm:rounded sm:border shadow-inner">
                    <div class="flex mb-2 border-b border-grey-light pb-4">
                        <div class="flex-none bg-brand bg-circuit bg-md ml-4 w-16 h-16 -mt-6 rounded shadow-lg">
                            <div class="h-full flex justify-center items-center">
                                @eyewitness_svg('calendar-grid-58', 'svgcolor-white')
                            </div>
                        </div>
                        <h1 class="font-hairline ml-4 mt-2 text-2xl">Scheduler</h1>
                    </div>

                    @include('eyewitness::scheduler.menu')
                </div>
            </div>
        </div>

        <div class="w-full md:px-2 lg:px-4">
            <eye-tab name="#overview" v-cloak>
                @include('eyewitness::scheduler.tabs.overview')
            </eye-tab>

            <eye-tab name="#history" v-cloak>
                @include('eyewitness::scheduler.tabs.history')
            </eye-tab>

            <eye-tab name="#settings" v-cloak>
                @include('eyewitness::scheduler.tabs.settings')
            </eye-tab>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        window.bus.setDefaultTab('#overview');

        schedulerChartData = {
            labels: {!! json_encode($transformer->generateScheduler($scheduler)['day']) !!},
            series: [
                {!! json_encode($transformer->generateScheduler($scheduler)['count']) !!},
            ]
        };

        new Chartist.Bar('.ct-chart', schedulerChartData, window.chartOptionBar('Date (day of month)', 'Time to run', 's'));
    </script>
@endsection

