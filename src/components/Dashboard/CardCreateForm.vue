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

        if (this.title && this.title.trim()) {
          // asdasd
          this.loading = true;
          this.axios_req('createBoardCard', {
            data : {
              'title' : this.title.trim(),
              'boardID' : this.boardID,
            }
          }, (response) => {
            if (response.data.status == 'success') {
              // успешно
              this.title = '';
              this.$emit('cardsUpdate');
              // this.$emit('cardsListUpdate');
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
