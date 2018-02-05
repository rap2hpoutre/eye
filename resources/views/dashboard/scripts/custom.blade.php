schedulerChartData = {
    labels: {!! json_encode($transformer->generateCustom($witness)['day']) !!},
    series: [
        {!! json_encode($transformer->generateCustom($witness)['count']) !!},
    ]
};

md.startAnimationForLineChart(new Chartist.Bar('#scheduleAverageChart{{ $witness->getSafeName() }}', schedulerChartData, getOptionsSimpleBarChart('Date (day of month)', '', '')));
