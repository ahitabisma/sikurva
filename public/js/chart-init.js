

// Init Chart IG Table 9-12
function initializeChartIg({
    ctx,
    labels,
    datasets,
    settings,
    xTickCallback,
    xGridColor,
    xGridLineWidth,
    stepSize,
    yMin,
    yMax,
    yTickCallback,
    yGridColor,
    yGridLineWidth,
    plugins
}) {
    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: datasets
        },
        options: {
            maintainAspectRatio: true,
            responsive: true,
            layout: {
                padding: {
                    top: 0,
                    bottom: 10,
                    left: 0,
                    right: 0
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                customCanvasBackgroundColor: {
                    color: 'white',
                }
            },
            scales: {
                x: {
                    title: {
                        display: true,
                        text: settings.ket_x,
                        font: {
                            weight: 'bold',
                            size: 12
                        },
                        padding: {
                            top: 12
                        }
                    },
                    ticks: {
                        callback: xTickCallback,
                        autoSkip: false,
                        padding: 10,
                        font: () => ({
                            size: 10
                        })
                    },
                    grid: {
                        drawTicks: false,
                        color: xGridColor,
                        lineWidth: xGridLineWidth
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: settings.ket_y,
                        font: {
                            weight: 'bold',
                            size: 12
                        }
                    },
                    min: yMin,
                    max: yMax,
                    ticks: {
                        stepSize: stepSize,
                        callback: yTickCallback,
                        autoSkip: false,
                        padding: 5,
                        font: {
                            size: 10
                        }
                    },
                    grid: {
                        color: yGridColor,
                        lineWidth: yGridLineWidth,
                        display: true
                    }
                },
                y1: {
                    position: 'right',
                    title: {
                        display: true,
                        text: settings.ket_y,
                        font: {
                            weight: 'bold',
                            size: 12
                        }
                    },
                    min: yMin,
                    max: yMax,
                    ticks: {
                        stepSize: stepSize,
                        callback: yTickCallback,
                        autoSkip: false,
                        padding: 5,
                        font: {
                            size: 10
                        }
                    },
                    grid: {
                        color: yGridColor,
                        lineWidth: yGridLineWidth,
                        display: true
                    }
                }
            }
        },
        plugins: plugins
    });
}
