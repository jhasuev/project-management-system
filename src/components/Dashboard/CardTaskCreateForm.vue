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
  import {SERVER_API} from '../../main'
  import axios from 'axios'
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
        // eslint-disable-next-line
        // console.log(this.boardID, this.cardID);

        this.loading = true;

        if (this.title && this.title.trim()) {

          axios.defaults.withCredentials = true;
          axios
            .get(SERVER_API + '?cmd=createTask&data=' + JSON.stringify({
              'title' : this.title.trim(),
              'boardID' : this.boardID,
              'cardID' : this.cardID,
            }))
            .then(response => {
              // eslint-disable-next-line
              console.log('createTask', response.data);
              if (response.data.status == 'success') {
                // успешно
                this.title = '';
                // this.$emit('tasksUpdate');
                eventEmitter.$emit("tasksUpdate");
              } else if (response.data.status == 'fail') {
                // ошибка
              }
            })
            .catch(error => {
              // eslint-disable-next-line
              console.log(error);
            })
            .finally(() => (this.loading = false));
        }
        
      }
    }
  }
</script>
