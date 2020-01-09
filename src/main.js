import Vue from 'vue'
import App from './App.vue'
import vuetify from './plugins/vuetify';
import VueRouter from 'vue-router'
import router from './routes'

Vue.use(VueRouter)

Vue.config.productionTip = false

export const DOC_TITLE = 'Система Управления Проектом';
export const eventEmitter = new Vue();

new Vue({
  render: h => h(App),
  vuetify,
  router,
}).$mount('#app')

