import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify';
import VueRouter from 'vue-router'
import router from './routes'
import Vuex from 'vuex';
// import store from './store/';
import axios from 'axios'


Vue.use(VueRouter)
Vue.use(Vuex)

Vue.config.productionTip = false

export const DOC_TITLE = 'Система Управления Проектом';
export const eventEmitter = new Vue();

const SERVER_HOST = 'http://project-management-system/';
const SERVER_API = SERVER_HOST + 'api.php';
export {SERVER_API};

Vue.mixin({
  methods : {
    axios_req(cmd, params, then, finally_cb){
      axios.get(SERVER_API + '?cmd=' + cmd, {
        params: params,
        withCredentials : true,
      })
      .then((response) => {
        then(response);
      })
      .catch((error) => {
        // eslint-disable-next-line
        console.log(error);
      })
      .finally(() => {
        // always executed
        if (finally_cb) {
          finally_cb();
        }
      });
    },
    getStringifyDate(ts, format){
      let date = new Date(ts);
      let str = format || 'dd.mm.yyyy в hh:mins';

      let dd = ("0" + date.getDate()).slice(-2);
      let mm = ("0" + (date.getMonth() + 1)).slice(-2);
      let yyyy = date.getFullYear();
      let hh = ("0" + date.getHours()).slice(-2);
      let mins = ("0" + date.getMinutes()).slice(-2);

      str = str.replace('dd', dd);
      str = str.replace('mm', mm);
      str = str.replace('yyyy', yyyy);
      str = str.replace('hh', hh);
      str = str.replace('mins', mins);

      return str;
    }
  }
})

new Vue({
  render: h => h(App),
  vuetify,
  router,
  // store,
  // axios,
}).$mount('#app')

