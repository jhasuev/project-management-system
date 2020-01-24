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

      axios.get(SERVER_API + '?cmd=' + cmd, {
        params: params,
        withCredentials : true,
      })
      .then((response) => {
        if(response.data  && response.data.status == 'success') {
          this.user_authed = true;
        } else {
          this.user_authed = false;
        }

        if(response.data  && response.data.status == 'no_access') {
          // eslint-disable-next-line
          // console.log('eslint-disable-next-line');
          if(this.$router.currentRoute.path == this_route){
            this.$router.push('/dashboard');
          }
        }

        // eslint-disable-next-line
        console.log(response);

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
      console.log('this.redirect()', this.user_authed);

      if(this.user_authed === null){
        this.axios_req('checkAuth', {}, (response) => {
          // eslint-disable-next-line
          console.log(this.$router.currentRoute.path, this_route);

          if(response.data && response.data.status == 'success'){
            this.user_authed = true;
            if(if_user && this.$router.currentRoute.path == this_route){
              this.$router.push(to);
            }
          } else {
            this.user_authed = false;
            if(!if_user && this.$router.currentRoute.path == this_route){
              this.$router.push(to);
            }
          }
        });
      } else {
        if(this.user_authed){
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
    setAuth(){
      this.axios_req('checkAuth', {}, (response) => {
        if(response.data && response.data.status == 'success'){
          this.user_authed = true;
        } else {
          this.user_authed = false;
        }
      });
    }
  },
  created(){
    
  }
})

new Vue({
  render: h => h(App),
  vuetify,
  router,
  // store,
  // axios,
}).$mount('#app')

