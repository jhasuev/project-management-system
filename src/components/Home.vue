<template>
  <div></div>
</template>

<script>
  // import Login from './Auth/Login.vue'
  // import {eventEmitter} from '../main'
  import {SERVER_API} from '../main'
  import axios from 'axios'
  export default {
    created(){
      axios.defaults.withCredentials = true;
      axios
        .get(SERVER_API + '?cmd=checkAuth')
        .then(response => {
          // eslint-disable-next-line
          // console.log(this.$router.currentRoute.path);
          if(this.$router.currentRoute.path == '/') {
            if(response.data.status == 'success') {
              this.$router.push('/dashboard');
            } else {
              this.$router.push('/login');
            }
          }
        })
        .catch(error => {
          // eslint-disable-next-line
          console.log(error);
        })
        // .finally(() => (this.loading = false));
      // eventEmitter.$emit("change_title", 'Главная');
    },
    components: {
    // Login,
    }
  }
</script>