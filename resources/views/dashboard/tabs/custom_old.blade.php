

<div class="card">
    <div class="card-content">
        <div class="card-header card-header-icon card-medium" data-background-color="orange">
            {!! $witness->getIcon() !!}<br/>
        </div>
        <h4 class="card-title">{{ $witness->displayName }}</h4>
        @eyewitness_tutorial('Eyewitness will track each of your custom witness monitors, allowing you to see in depth information on the performance of each. You can configure different alerts around these, so you will be the first to know of any problems.')
        <hr/>
        <div class="row">
            <div class="col-md-4">
                Witness status: @include('eyewitness::snippet.custom_status', ['history' => $witness->history()])
                <hr/>
                Witness schedule: <span class="label label-default f-s-14">{{ $witness->schedule }}</span>
                <hr/>
                Last check: @include('eyewitness::snippet.custom_heartbeat', ['history' => $witness->history()])
                <hr/>
                Next check due: <span class="label label-default f-s-14">{{ $witness->nextDue()->diffForHumans() }}</span>
                <hr/>
            </div>
            <div class="col-md-8">
                <br/>
                <div data-background-color="orange">
                    <div class="text-center">
                        <h4>Trend of tracked monitor value past 21 days</h4>
                    </div>
                    <div class="ct-chart" id="scheduleAverageChart{{ $witness->getSafeName() }}"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-content">
        <div class="card-header card-header-icon card-medium" data-background-color="orange">
            @eyewitness_svg('timeline', '', 32, 32)
        </div>
        <h4 class="card-title">{{ $witness->displayName  }} history</h4>
        <hr/>
        @if (count($witness->history()))
            <table class="table">
                <thead class="text-primary">
                    <tr>
                        <th>Run At</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($witness->history() as $history)
                        <tr>
                            <td>{{ $history->created_at->format('Y-m-d H:i:s') }} ({{ $history->created_at->diffForHumans() }})</td>
                            <th class="text-center"><span class="label label-{{ $history->status ? 'success' : 'danger' }} f-s-14">{{ $history->status ? 'Ok' : 'Fail' }}</th>
                            <th class="text-center">
                                @if (is_null($history->value) || ($history->value == ""))
                                    <span class="label label-default f-s-14">@eyewitness_svg('bold-remove', 'svgcolor-white button-svg', 12, 12) Not captured</span>
                                @else
                                    {{ $history->value }}
                                @endif
                            </th>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            @eyewitness_info('We have not recorded any data from this witness. Once the first run is received, you can view the history here.')
        @endif
    </div>
</div>
