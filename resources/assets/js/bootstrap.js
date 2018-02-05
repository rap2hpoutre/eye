window._ = require('lodash');
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Chartist = require('chartist');
import ctToolTips from 'chartist-plugin-tooltips';
import ctAxisTitle from 'chartist-plugin-axistitle';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}
