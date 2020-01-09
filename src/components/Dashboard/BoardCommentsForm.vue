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
  export default {
    data () {
      return {
        comment : '',
      }
    },
    methods: {
      addComment(){
        alert('add comment');
      }
    }
  }
</script>
