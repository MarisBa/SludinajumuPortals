import 'bootstrap';
require('./bootstrap.js');

window.Vue = require('vue');
window.axios = require('axios');

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('image-preview', require('./components/imagepreview/FeatureImage.vue').default);






const app = new Vue({
    el: '#app',
   
  });