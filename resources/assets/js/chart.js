
window.chartOptionBar = function (xAxisTitle, yAxisTitle, key) {
    return {
      axisX: {
        showGrid: false
      },
      axisY: {
          offset: 60,
          labelInterpolationFnc: function(value) {
            return value + key;
          }
      },
      chartPadding: { top: 15, right: 0, bottom: 15, left: 0},
      plugins: [
          Chartist.plugins.tooltip({
              appendToBody: true,
              transformTooltipTextFnc: function(x){
                  if (x) {
                      return x+key;
                  } else {
                      return '0'+key;
                  }
              }
          }),
          Chartist.plugins.ctAxisTitle({
            axisX: {
              axisTitle: xAxisTitle,
              axisClass: 'ct-axis-title',
              offset: {
                x: 0,
                y: 40
              },
              textAnchor: 'middle'
            },
            axisY: {
              axisTitle: yAxisTitle,
              axisClass: 'ct-axis-title',
              offset: {
                x: 0,
                y: 15
              },
              textAnchor: 'middle',
              flipTitle: true
            }
          })
      ]
    };
}

window.chartOptionLine = function (xAxisTitle, yAxisTitle, key, high) {
    result = {
        lineSmooth: Chartist.Interpolation.cardinal({
            tension: 10
        }),
        axisX: {
            showGrid: true,
        },
        axisY: {
            offset: 60,
            labelInterpolationFnc: function(value) {
              return value + key;
            }
        },
        low: 0,
        chartPadding: { top: 15, right: 0, bottom: 15, left: 0},
        plugins: [
            Chartist.plugins.tooltip({
                appendToBody: true,
                transformTooltipTextFnc: function(x){
                    if (x) {
                        return x+key;
                    } else {
                        return '0'+key;
                    }
                }
            }),
            Chartist.plugins.ctAxisTitle({
              axisX: {
                axisTitle: xAxisTitle,
                axisClass: 'ct-axis-title',
                offset: {
                  x: 0,
                  y: 36
                },
                textAnchor: 'middle'
              },
              axisY: {
                axisTitle: yAxisTitle,
                axisClass: 'ct-axis-title',
                offset: {
                  x: 0,
                  y: 0
                },
                textAnchor: 'middle',
                flipTitle: false
              }
            })
        ]
    }

    if (high) {
        result.high = high;
    }

    return result;
}
