<template>
  <v-form @submit.prevent="createCard()">
    <v-text-field
      label="Новая карточка"
      required
      hide-details
      full-width
      flat
      solo
      background-color="transparent"
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
      createCard(){
        alert('Создание карточки');
      }
    }
  }
</script>
