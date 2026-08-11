// Plugin untuk menampilkan SD terakhir di ujung kanan grafik
const zScoreLabelPlugin = {
    id: "zScoreLabel",
    afterDatasetsDraw(chart) {
        const { ctx, data } = chart;
        ctx.save();
        ctx.font = "12px Arial";
        ctx.textAlign = "left";
        ctx.textBaseline = "middle";

        data.datasets.forEach((dataset, index) => {
            const meta = chart.getDatasetMeta(index);
            if (!meta.hidden) {
                // Cari titik terakhir yang valid
                const validPoints = meta.data.filter((p) => p && !p.skip);
                const lastPoint = validPoints[validPoints.length - 1];
                // const lastPoint = meta.data[meta.data.length - 1]; // Titik terakhir
                const zScoreLabel = dataset.labelCanvas; // Ambil SD sebagai label
                if (lastPoint) {
                    ctx.fillStyle = dataset.borderColor;
                    ctx.fillText(zScoreLabel, lastPoint.x + 12, lastPoint.y);
                }
            }
        });
        ctx.restore();
    },
};

const footerPlugin = {
    id: "footerPlugin",
    beforeDraw: (chart) => {
        const { ctx, chartArea } = chart;

        // Get the canvas ID to determine which table this is
        const canvasId = chart.canvas.id;
        const tableNumber = canvasId.match(/chart-table-(\d+)/)?.[1];

        ctx.save();
        ctx.font = "bold 12px Arial";
        ctx.fillStyle = "gray";
        ctx.textAlign = "left";

        // Left footer text
        ctx.fillText(
            "© Ekurva Anak Indonesia",
            chartArea.left,
            chartArea.bottom + 61
        );

        // Right footer text - different for tables 9-12
        ctx.textAlign = "right";
        if (tableNumber >= 9 && tableNumber <= 12) {
            ctx.fillText(
                "Sumber Data: Intergrowth Postnatal For Preterm Infant",
                chartArea.right,
                chartArea.bottom + 61
            );
        } else {
            ctx.fillText(
                "Sumber Data: WHO Growth Chart",
                chartArea.right,
                chartArea.bottom + 61
            );
        }

        ctx.restore();
    },
};

// Plugin untuk menggambar latar belakang
const plugin = {
    id: "customCanvasBackgroundColor",
    beforeDraw: (chart, args, options) => {
        const { ctx } = chart;
        ctx.save();
        ctx.globalCompositeOperation = "destination-over";
        ctx.fillStyle = options.color || "#99ffff";
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    },
};
