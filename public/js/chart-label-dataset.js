// Kurva WHO Table 1-8
const tableUsiaHari = ['table1', 'table2', 'table3', 'table4', 'table5'];

function generateDatasetsWho(tableKey, dataTable, customLabel) {
    const zValues = [{
            key: 'sd3neg',
            label: 'SD3neg',
            labelCanvas: '-3',
            color: 'black'
        },
        {
            key: 'sd2neg',
            label: 'SD2neg',
            labelCanvas: '-2',
            color: 'red'
        },
        {
            key: 'sd1neg',
            label: 'SD1neg',
            labelCanvas: '-1',
            color: 'orange',
            dashed: true
        },
        {
            key: 'sd0',
            label: 'SD0',
            labelCanvas: '0',
            color: 'green'
        },
        {
            key: 'sd1',
            label: 'SD1',
            labelCanvas: '1',
            color: 'orange',
            dashed: true
        },
        {
            key: 'sd2',
            label: 'SD2',
            labelCanvas: '2',
            color: 'red'
        },
        {
            key: 'sd3',
            label: 'SD3',
            labelCanvas: '3',
            color: 'black'
        },
    ];

    const datasets = zValues.map(z => ({
        label: z.label,
        labelCanvas: z.labelCanvas,
        data: tableKey.includes(tableUsiaHari) ? kurvaData[tableKey].map(item => parseFloat(item[z
            .key])) : [null, ...kurvaData[tableKey].map(item => parseFloat(item[z
            .key]))],
        borderColor: z.color,
        fill: false,
        tension: 0.4,
        borderWidth: 1,
        pointRadius: 0,
        ...(z.dashed ? {
            borderDash: [5, 5]
        } : {})
    }));

    datasets.push({
        label: customLabel,
        labelCanvas: '',
        data: dataTable,
        borderColor: color,
        backgroundColor: backgroundColor,
        pointRadius: 3,
        fill: false,
        borderWidth: 1,
    });

    return datasets;
}

// Kurva InterGrowth Table 9-12
function generateDatasetsIg(tableKey, labelTable, dataTable, customLabel) {
    const zValues = [{
            key: 'z3neg',
            label: 'Z3neg',
            labelCanvas: '-3',
            color: 'black'
        },
        {
            key: 'z2neg',
            label: 'Z2neg',
            labelCanvas: '-2',
            color: 'red'
        },
        {
            key: 'z1neg',
            label: 'Z1neg',
            labelCanvas: '-1',
            color: 'orange',
            dashed: true
        },
        {
            key: 'z0',
            label: 'Z0',
            labelCanvas: '0',
            color: 'green'
        },
        {
            key: 'z1',
            label: 'Z1',
            labelCanvas: '1',
            color: 'orange',
            dashed: true
        },
        {
            key: 'z2',
            label: 'Z2',
            labelCanvas: '2',
            color: 'red'
        },
        {
            key: 'z3',
            label: 'Z3',
            labelCanvas: '3',
            color: 'black'
        },
    ];

    const datasets = zValues.map(z => ({
        label: z.label,
        labelCanvas: z.labelCanvas,
        data: tableKey === 'table12' ? [...kurvaData[tableKey].map(item => parseFloat(item[z
            .key]))] : interpolateDailyData(kurvaData[tableKey], z.key, labelTable),
        borderColor: z.color,
        fill: false,
        tension: 0.4,
        borderWidth: 1,
        pointRadius: 0,
        ...(z.dashed ? {
            borderDash: [5, 5]
        } : {})
    }));

    datasets.push({
        label: customLabel,
        labelCanvas: '',
        data: dataTable,
        borderColor: color,
        backgroundColor: backgroundColor,
        pointRadius: 3,
        fill: false,
        borderWidth: 1,
    });

    return datasets;
}

function generateLabelsFromTable(table) {
    if (table.length === 0) return [];

    const minWeek = Math.min(...table.map(item => item.week));
    const maxWeek = Math.max(...table.map(item => item.week));
    const minDay = minWeek * 7;
    const maxDay = maxWeek * 7;

    const labels = [];
    for (let day = minDay; day <= maxDay; day++) {
        labels.push(day);
    }

    // Tambahan padding jika dibutuhkan (misalnya agar chart tidak terlalu mepet)
    labels.push(...Array(7).fill(''));

    return labels;
}

function interpolateDailyData(table, key, labels) {
    const result = [];

    if (table.length < 2) return result;

    for (let i = 0; i < table.length - 1; i++) {
        const curr = table[i];
        const next = table[i + 1];

        const currDay = curr.week * 7;
        const nextDay = next.week * 7;

        const currValue = parseFloat(curr[key]);
        const nextValue = parseFloat(next[key]);

        const daysBetween = nextDay - currDay;

        for (let d = 0; d < daysBetween; d++) {
            const interpolatedValue = currValue + (nextValue - currValue) * (d / daysBetween);
            result.push(interpolatedValue);
        }
    }

    // Tambahkan nilai akhir secara eksplisit
    result.push(parseFloat(table[table.length - 1][key]));

    // Pad dengan null jika hasil lebih pendek dari label
    while (result.length < labels.length) {
        result.push(null);
    }

    return result;
}
