@extends('eyewitness::layout')

@section('content')

    <div class="md:hidden bg-white">
        <eye-mobile-menu>
            <div slot="name">Options</div>
            <div slot="dropdown" v-cloak>
                <div class="container mx-auto px-4 border-b border-grey-light pt-3 pb-3">
                    @include('eyewitness::queue.menu')
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
                                @eyewitness_svg('design-system', 'svgcolor-white')
                            </div>
                        </div>
                        <h1 class="font-hairline ml-4 mt-2 text-2xl">Queue</h1>
                    </div>

                    @include('eyewitness::queue.menu')
                </div>
            </div>
        </div>

        <div class="w-full md:px-2 lg:px-4">
            <eye-tab name="#overview" v-cloak>
                @include('eyewitness::queue.tabs.overview')
            </eye-tab>

            <eye-tab name="#history" v-cloak>
                @include('eyewitness::queue.tabs.history')
            </eye-tab>

            <eye-tab name="#failed" v-cloak>
                @include('eyewitness::queue.tabs.failed')
            </eye-tab>

            <eye-tab name="#settings" v-cloak>
                @include('eyewitness::queue.tabs.settings')
            </eye-tab>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        window.bus.setDefaultTab('#overview');

        queueChartData = {
            labels: {!! json_encode($transformer->generateQueue($queue)['day']) !!},
            series: [
                {!! json_encode($transformer->generateQueue($queue)['total_process_count']) !!},
            ]
        };

        new Chartist.Bar('.ct-queue-total', queueChartData, window.chartOptionBar('Day', 'Jobs Processed', ' jobs'));

        queueChartData = {
            labels: {!! json_encode($transformer->generateQueue($queue)['day']) !!},
            series: [
                {!! json_encode($transformer->generateQueue($queue)['avg_wait_time']) !!},
            ]
        };

        new Chartist.Bar('.ct-queue-wait', queueChartData, window.chartOptionBar('Day', 'Avg Wait Time', ' secs'));

        queueChartData = {
            labels: {!! json_encode($transformer->generateQueue($queue)['day']) !!},
            series: [
                {!! json_encode($transformer->generateQueue($queue)['avg_process_time']) !!},
            ]
        };

        new Chartist.Bar('.ct-queue-process', queueChartData, window.chartOptionBar('Day', 'Avg Process Time', ' secs'));

        queueChartData = {
            labels: {!! json_encode($transformer->generateQueue($queue)['day']) !!},
            series: [
                {!! json_encode($transformer->generateQueue($queue)['avg_pending_count']) !!},
            ]
        };

        new Chartist.Bar('.ct-queue-pending', queueChartData, window.chartOptionBar('Day', 'Avg Pending Count', ' jobs'));

        queueChartData = {
            labels: {!! json_encode($transformer->generateQueue($queue)['day']) !!},
            series: [
                {!! json_encode($transformer->generateQueue($queue)['idle_time']) !!},
            ]
        };

        new Chartist.Bar('.ct-queue-idle', queueChartData, window.chartOptionBar('Day', 'Total Worker Idle Time', ' secs'));

        queueChartData = {
            labels: {!! json_encode($transformer->generateQueue($queue)['day']) !!},
            series: [
                {!! json_encode($transformer->generateQueue($queue)['exception_count']) !!},
            ]
        };

        new Chartist.Bar('.ct-queue-exception', queueChartData, window.chartOptionBar('Day', 'Exception Count', ' jobs'));
    </script>
@endsection

