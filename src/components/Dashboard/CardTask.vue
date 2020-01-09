<template>
  <div>
    <v-tooltip top>
      <template v-slot:activator="{ on }">
        <div class="card-title" v-on="on">Новая карточка в которой что то есть...</div>
      </template>
      <span>Новая карточка в которой что то есть...</span>
    </v-tooltip>

    <CardTaskCreateForm/>

    <v-card
      class="mx-auto"
      max-width="400"
      flat
    >
      <v-list
      >
        <v-subheader>Все задачи:</v-subheader>
        <v-divider></v-divider>
        <v-list-item-group color="primary">
          <v-list-item
          >
            <v-list-item-content>
              <v-list-item-title>Lorem title 1Lorem title 1Lorem title 1Lorem title 1Lorem title 1Lorem title 1Lorem title 1Lorem title 1Lorem title 1Lorem title 1</v-list-item-title>
              <v-list-item-subtitle class="text--primary">
                <div class="pt-2 d-flex align-center">
                  <v-icon class="mr-auto">mdi-format-list-checkbox</v-icon>
                  <v-icon class="ml-auto">mdi-calendar-today</v-icon>
                </div>
              </v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
          <v-divider></v-divider>
          <v-list-item
          >
            <v-list-item-content>
              <v-list-item-title>Lorem title 2</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-divider></v-divider>
          <v-list-item
          >
            <v-list-item-content>
              <v-list-item-title>Lorem title 3</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-divider></v-divider>
          <v-list-item
          >
            <v-list-item-content>
              <v-list-item-title>Lorem title 4</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-divider></v-divider>
          <v-list-item
          >
            <v-list-item-content>
              <v-list-item-title>Lorem title 5</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list-item-group>
      </v-list>
    </v-card>
  </div>
</template>
<script>
  // import {eventEmitter} from '../../main'
  import CardTaskCreateForm from './CardTaskCreateForm.vue'
  export default {
    props: ['id'],
    data () {
      return {
        
      }
    },
    created(){
    },
    methods: {
    },
    components: {
      CardTaskCreateForm,
    }
  }
</script>
<style>

  .card-title {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 11px;
    font-size: 18px;

    cursor: pointer;
  }

</style>