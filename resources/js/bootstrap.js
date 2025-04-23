import axios from 'axios';
import 'bootstrap';

try{
    window.Popper = require('popper.js').default;
    window.$ = window.Jquery = require('jquery');

    require('bootstrap');
}catch(e){

}


window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
