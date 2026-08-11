import './bootstrap';

import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import moment from 'moment';
import { Chart, registerables } from 'chart.js';
import Big from 'big.js';

Chart.register(...registerables); // 💥 Required

Alpine.plugin(persist);
window.Alpine = Alpine;
window.moment = moment;
window.Chart = Chart;
window.Big = Big;

Alpine.start();