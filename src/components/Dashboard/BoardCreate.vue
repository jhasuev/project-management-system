<template>
    <!-- :style="{'background-color': color_active}" -->
  <div class="board  board--dotted"
    >
    <div>
      <v-text-field
        label="Название доски"
        hide-details
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
             :style="{'background-color': color}"
             :class="{'colors__item--active': color === color_active}"
             @click="setColor(color)"
             ></div>
      </div>
    </div>
    <div class="d-flex  mt-auto">
      <v-spacer></v-spacer>
      <v-btn text :loading="!true" class="elevation-1">Создать доску</v-btn>
    </div>
  </div>
</template>
<script>
  export default {
    data () {
      return {
        is_private: true,
        colors: [
          'white',
          'orange',
          'red',
          'pink',
          'purple',
          'cyan',
          'teal',
          'lime',
        ],
        color_active : 'white'
      }
    },
    methods : {
      setColor(color){
        this.color_active = color
      }
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