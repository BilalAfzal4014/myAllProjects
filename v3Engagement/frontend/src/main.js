import Vue from 'vue';
import App from './App.vue';
import router from './router/index';
import store from './store/store';
import mixins from './mixins/globalMixins/globalMixins';
import beforeEachHook from './navigationGuard/beforeEachHook/beforeEachHook';
import apiCall from './apiCall/apiCall';
import constants from './constants/constants';
import VueSweetalert2 from 'vue-sweetalert2';
import sweetAlertOptions from './sweetAlert/sweetAlertObject';

Vue.config.productionTip = false;

Vue.prototype.api = apiCall;
Vue.prototype.bus = new Vue();
Vue.prototype.constants = constants;
//Vue.http.headers.common['Access-Control-Allow-Origin'] = 'http://localhost:8100';
//Vue.http.headers.common['Access-Control-Request-Method'] = '*';


//Vue.http.headers.common['Access-Control-Allow-Origin'] = true;
//Vue.http.headers.common['Access-Control-Allow-Origin'] = '*';
//Vue.http.headers.common['Access-Control-Request-Method'] = '*';
Vue.mixin(mixins);
router.beforeEach(beforeEachHook);

Vue.use(VueSweetalert2, sweetAlertOptions);

new Vue({
    el: "#app",
    router,
    store,
    render: h => h(App),
});
