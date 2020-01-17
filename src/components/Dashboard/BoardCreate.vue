<template>
    <!-- :style="{'background-color': color_active}" -->
  <div class="board  board--dotted"
    >
    <div>
      <v-text-field
        label="Название доски"
        hide-details
        v-model="title"
        :disabled="loading"
      ></v-text-field>
      <v-checkbox
        v-model="is_private"
        :label="'Приватный?'"
        color="orange"
        hide-details
      ></v-checkbox>

      <div class="colors">
        <div class="colors__item"
             v-for="(color,i) in colors"
             :key="i"
             :style="{'background-color': color.bgcolor}"
             :class="{'colors__item--active': color === color_active}"
             @click="setColor(color)"
             ></div>
      </div>
    </div>
    <div class="d-flex  mt-auto">
      <v-spacer></v-spacer>
      <v-btn text :loading="loading" class="elevation-1" @click="create()">Создать доску</v-btn>
    </div>
  </div>
</template>
<script>
  // import {eventEmitter} from '../../main'
  import {SERVER_API} from '../../main'
  import axios from 'axios'
  export default {
    props: ['colors'],
    data () {
      return {
        loading : false,
        title: '',
        is_private: true,
        color_active : {
          'bgcolor' : 'white',
          'textcolor' : '#000',
        }
      }
    },
    methods : {
      setColor(color){
        this.color_active = color
      },

      create(){
        if (this.title && this.title.trim()) {
          this.loading = true;
          let colorIndex = this.colors.indexOf(this.color_active);
              colorIndex = (colorIndex == -1)? '' : colorIndex;
          // eslint-disable-next-line
          console.log(colorIndex);

          axios.defaults.withCredentials = true;
          axios
            .get(SERVER_API + '?cmd=createBoard&data=' + JSON.stringify({
              'title' : this.title.trim(),
              'color' : colorIndex,
              'is_private' : this.is_private,
            }))
            .then(response => {
              // eslint-disable-next-line
              console.log(response.data);
              if (response.data.status == 'success') {
                // успешно
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
      },
    }
  }
</script>

<style>
.colors {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-left: -6px;
  padding: 15px 0 10px;
}
.colors__item {
  /*width: 30px;*/
  flex-grow: 1;
  margin-left: 6px;
  /*height: 30px;*/

  border-radius: 4px;
  cursor: pointer;
  box-shadow: 0 0 8px 0px rgba(0,0,0,.5);

  transition: box-shadow .2s;
  max-width: 20px;
  max-height: 20px;
  overflow: hidden;
}
.colors__item:after {
  content: "";
  display: block;
  padding-top: 100%;
}
.colors__item--active {
  box-shadow: 0px 0px 0px 2px #000;
}
</style>