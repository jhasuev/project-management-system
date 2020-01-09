<template>
  <v-form @submit.prevent="createTask()">
    <v-text-field
      label="Новая задача"
      required
      hide-details
      solo
      full-width
      class="mb-5"
    ></v-text-field>
  </v-form>
</template>
<script>
  // import {eventEmitter} from '../../main'
  export default {
    data () {
      return {
        
      }
    },
    methods: {
      createTask(){
        alert('Создание задачи');
      }
    }
  }
</script>
