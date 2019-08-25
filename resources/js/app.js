require('./bootstrap');

window.Vue = require('vue');

import * as VueGoogleMaps from 'vue2-google-maps'
import vSelect from 'vue-select'


// Config for vue2-google-maps
Vue.use(VueGoogleMaps, {
    load: {
      key: 'AIzaSyBDzI0csQYqh24xwIyl_-rlKynmiam4DGU',
      libraries: 'places',
    },
})

Vue.component('v-select', vSelect)
Vue.component('home', require('./components/Home.vue'));
Vue.component('site-create', require('./components/SiteCreate.vue'));
Vue.component('site-edit', require('./components/SiteEdit.vue'));
Vue.component('site-map', require('./components/SiteMap.vue'));
Vue.component('waypoint-create', require('./components/WaypointCreate.vue'));
Vue.component('waypoint-edit', require('./components/WaypointEdit.vue'));
Vue.component('path-create', require('./components/PathCreate.vue'));
Vue.component('guest-map', require('./components/GuestMap.vue'));
Vue.component('dijkstra-route', require('./components/DijkstraRoute.vue'));

const app = new Vue({
    el: '#app'
});
