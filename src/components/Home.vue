<template>
  <div>
    <Login :use_in_main="true"/>
    <div class="text-center pb-3">
      Еще не зарегистрированы?<br/>
      <router-link :to="'/register'">Присоединяйтесь!</router-link>
    </div>
  </div>
</template>

<script>
  import {eventEmitter} from '../main'
  import Login from './Auth/Login.vue'
  export default {
    created(){
      eventEmitter.$emit("change_title", 'Главная');
      this.redirect('/dashboard', true);

      /*this.axios_req('getBoardTitle', {
        data : {
          'boardID' : this.boardID
        }
      }, (response) => {
        // eslint-disable-next-line
        console.log(response);
      }, ()=>{
        // finally
      });*/
      
    },
    components : {
      Login,
    }
  }
</script>