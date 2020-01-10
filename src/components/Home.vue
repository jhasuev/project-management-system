<template>
  <div>
    <Login :no_change_title="true"/>
    <div class="text-center">
      Нет аккаунта? <br/>
      <router-link :to="'/register'">Зарегистрируйтесь!</router-link>
    </div>
  </div>
</template>

<script>
  import Login from './Auth/Login.vue'
  import {eventEmitter} from '../main'
  export default {
   created(){
    if (!this.$router) {
      // this.$router.push('dashboard');
      this.$router.push('login');
    } else {
      eventEmitter.$emit("change_title", 'Главная');
    }
   },
   components: {
    Login,
   }
  }
</script>