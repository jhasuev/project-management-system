<template>
  <v-form @submit.prevent="addComment()">
    <v-textarea
      name="comment"
      label="Написать комментарий"
      filled
      auto-grow
      rows="2"
      row-height="1"
      hide-details
      v-model="comment"
      :loading="loading"
      :disabled="loading"
    ></v-textarea>
    <v-expand-transition v-if="comment.trim()">
      <div class="d-flex" style="padding-top: 1px">
        <v-spacer></v-spacer>
        <v-btn
          depressed
          small
          @click="addComment()"
        >
          Добавить комментарий
        </v-btn>
      </div>
    </v-expand-transition>
  </v-form>
</template>
<script>
  // import {eventEmitter} from '../../main'
  import {SERVER_API} from '../../main'
  import axios from 'axios'
  export default {
    props: ['boardID', 'taskID'],
    data () {
      return {
        loading : false,
        comment : '',
      }
    },
    methods: {
      addComment(){
        this.loading = true;

        axios.defaults.withCredentials = true;
        axios
          .get(SERVER_API + '?cmd=addComment&data=' + encodeURIComponent(JSON.stringify({
              'boardID' : this.boardID,
              'taskID' : this.taskID,
              'comment' : this.comment,
            })))
          .then(response => {
            // eslint-disable-next-line
            console.log(response.data);
            if (response.data.status == 'success') {
              // успешно
              this.comment = '';
              this.$emit('loadComments');
            } else if (response.data.status == 'fail') {
              // ошибка
              // eslint-disable-next-line
              console.log(response);
            }
          })
          .catch(error => {
            // eslint-disable-next-line
            console.log(error);
          })
          .finally(()=>{this.loading = false});
      }
    }
  }
</script>
