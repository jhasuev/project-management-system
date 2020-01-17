import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify';
import VueRouter from 'vue-router'
import router from './routes'
import Vuex from 'vuex';
// import store from './store/';

// import axios from 'axios'

Vue.use(VueRouter)
Vue.use(Vuex)

Vue.config.productionTip = false

export const DOC_TITLE = 'Система Управления Проектом';
export const eventEmitter = new Vue();

const SERVER_HOST = 'http://project-management-system/';
export const SERVER_API = SERVER_HOST + 'api.php';

new Vue({
  render: h => h(App),
  vuetify,
  router,
  // store,
  // axios,
}).$mount('#app')

