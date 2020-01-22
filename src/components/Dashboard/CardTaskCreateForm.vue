<template>
  <v-form @submit.prevent="createTask()">
    <v-text-field
      label="Новая задача"
      required
      hide-details
      solo
      full-width
      class="mb-5"
      v-model="title"
      :disabled="loading"
      :loading="loading"
      :clearable="true"
    ></v-text-field>
  </v-form>
</template>
<script>
  import {eventEmitter} from '../../main'
  export default {
    props: ['boardID', 'cardID'],
    data () {
      return {
        title : '',
        loading : false,
      }
    },
    methods: {
      createTask(){

        if (this.title && this.title.trim()) {
          this.loading = true;

          this.axios_req('createTask', {
            data : {
              'title' : this.title.trim(),
              'boardID' : this.boardID,
              'cardID' : this.cardID,
            }
          }, (response) => {
            // eslint-disable-next-line
            console.log(response);
            if (response.data.status == 'success') {
              // успешно
              this.title = '';
              eventEmitter.$emit("tasksUpdate");
            } else if (response.data.status == 'fail') {
              // ошибка
            }
          }, ()=>{
            // finally
            this.loading = false
          });
        }
        
      }
    }
  }
</script>
