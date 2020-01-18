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
      v-model="title"
      :disabled="loading"
      :loading="loading"
      :clearable="true"
    ></v-text-field>
  </v-form>
</template>
<script>
  // import {eventEmitter} from '../../main'
  import {SERVER_API} from '../../main'
  import axios from 'axios'
  export default {
    props: ['boardID'],
    data () {
      return {
        title : '',
        loading : false,
      }
    },
    methods: {
      createCard(){
        this.loading = true;

        if (this.title && this.title.trim()) {
          // asdasd
          axios.defaults.withCredentials = true;
          axios
            .get(SERVER_API + '?cmd=createBoardCard&data=' + JSON.stringify({
              'title' : this.title.trim(),
              'boardID' : this.boardID,
            }))
            .then(response => {
              // eslint-disable-next-line
              console.log('createBoardCard', response.data);
              if (response.data.status == 'success') {
                // успешно
                this.title = '';
                this.$emit('cardsListUpdate');
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
