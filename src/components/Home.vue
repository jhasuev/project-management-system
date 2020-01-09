<template>
  <h1>Home</h1>
</template>

<script>
  import {eventEmitter} from '../main'
  export default {
   created(){
    if (!this.$router) {
      // this.$router.push('dashboard');
      this.$router.push('login');
    } else {
      eventEmitter.$emit("change_title", 'Главная');
    }
   }
  }
</script>