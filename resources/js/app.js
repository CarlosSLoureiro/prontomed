window.$ = window.jQuery = require('jquery');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

require('./bootstrap');

require('datatables.net');

require('./layouts/v1/main');
