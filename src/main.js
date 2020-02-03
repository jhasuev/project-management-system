import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify';
import VueRouter from 'vue-router'
import router from './routes'
import axios from 'axios'

Vue.use(VueRouter)

Vue.config.productionTip = false

export const DOC_TITLE = 'Система Управления Проектом';
export const eventEmitter = new Vue();

const SERVER_HOST = 'http://project-management-system/';
const SERVER_API = SERVER_HOST + 'api.php';
export {SERVER_API};

Vue.mixin({
  data(){
    return {
      user_authed : null,
    }
  },
  methods : {
    axios_req(cmd, params, then, finally_cb){
      // eslint-disable-next-line
      console.log({
        cmd, params, then, finally_cb
      });

      let this_route = this.$router.currentRoute.path;
      this_route;

      axios.get(SERVER_API + '?cmd=' + cmd, {
        params: params,
        withCredentials : true,
      })
      .then((response) => {
        this.$root.is_authed = !!response.data.is_authed;

        if(!this.$root.is_authed) {
          if(this.$router.currentRoute.path != '/login' && this.$router.currentRoute.path != '/register') {
            this.$router.push('/login');
          }
        }

        // if(!this.$root.is_authed) {
        //   this.$router.push('/login');
        //   return true;
        // }

        // if(response.data  &&  response.data.status == 'no_access') {
        //   if(this.$router.currentRoute.path == this_route){
        //     this.$router.push('/dashboard');
        //     return true;
        //   }
        // }

        // eslint-disable-next-line
        console.log(cmd, response);

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
    },
    redirect(to, if_user){

      let this_route = this.$router.currentRoute.path;

      // eslint-disable-next-line
      console.log('this.redirect()', this.$root.is_authed);

      if(this.$root.is_authed === null){
        this.axios_req('checkAuth', {}, (response) => {
          
          this.$root.is_authed = !!response.data.is_authed;

          if(response.data && response.data.status == 'success'){
            if(if_user && this.$router.currentRoute.path == this_route){
              this.$router.push(to);
            }
          } else {
            if(!if_user && this.$router.currentRoute.path == this_route){
              this.$router.push(to);
            }
          }

        });

      } else {

        if(this.$root.is_authed){
          if(if_user){
            this.$router.push(to);
          }
        } else {
          if(!if_user){
            this.$router.push(to);
          }
        }

      }
    },
    getDeadlineState(ts){
      ts = ts * 1;
      if (!ts) {
        return '';
      }
      ts *= 1000;
      let day_ts = 60*60*24 * 1000;

      let now_ts = new Date().getTime();

      let res = '';

      if (ts < now_ts + (day_ts * 3)) {
        res =  'orange';
      }
      if (this.getStringifyDate(ts, 'dd.mm.yyyy') == this.getStringifyDate(now_ts, 'dd.mm.yyyy')) {
        res =  'orange';
      }
      if (ts < now_ts) {
        res =  'red';
      }

      return res;
    }
  },
  created(){
    
  }
})

new Vue({
  render: h => h(App),
  vuetify,
  router,
  data() {
    return {
      is_authed : null
    }
  },
}).$mount('#app')

