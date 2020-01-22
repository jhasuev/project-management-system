<template>
  <div></div>
</template>

<script>
  export default {
    created(){
      /*this.axios_req('getBoardTitle', {
        'boardID' : 2
      }, (response) => {
        // eslint-disable-next-line
        console.log(response);
      }, ()=>{
        // finally
      });*/

      this.axios_req('checkAuth', null, (response) => {
        if(this.$router.currentRoute.path == '/') {
          if(response.data.status == 'success') {
            this.$router.push('/dashboard');
          } else {
            this.$router.push('/login');
          }
        }
      }, ()=>{
        // finally
      });
    },
  }
</script>