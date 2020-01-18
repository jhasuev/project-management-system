<template>
  <div>
    <v-tooltip top>
      <template v-slot:activator="{ on }">
        <div class="card-title" v-on="on">{{card.title}}</div>
      </template>
      <span>{{card.title}}</span>
    </v-tooltip>

    <CardTaskCreateForm :boardID="boardID" :cardID="card.id" />

    <v-card
      class="mx-auto"
      max-width="400"
      flat
      v-if="filteredTasks.length"
    >
      <v-list
      >
        <v-subheader>Все задачи:</v-subheader>
        <v-divider></v-divider>
        <v-list-item-group color="primary">

          <div v-for="(task, i) in filteredTasks" :key="i">
            <v-list-item
            >
              <v-list-item-content>
                <v-list-item-title>{{task.title}}</v-list-item-title>
                <v-list-item-subtitle class="text--primary" v-if="task.checkList || task.deadline">
                  <div class="pt-2 d-flex align-center">
                    <v-icon class="mr-auto" v-if="task.checkList">mdi-format-list-checkbox</v-icon>
                    <v-icon class="ml-auto" v-if="task.deadline">mdi-calendar-today</v-icon>
                  </div>
                </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-divider v-if="(filteredTasks.length - 1) != i"></v-divider>
          </div>
        </v-list-item-group>
      </v-list>
    </v-card>
  </div>
</template>
<script>
  // import {eventEmitter} from '../../main'
  import CardTaskCreateForm from './CardTaskCreateForm.vue'
  // import {SERVER_API} from '../../main'
  // import axios from 'axios'

  export default {
    props: ['boardID', 'card', 'tasks'],
    data () {
      return {
        // tasks : [],
      }
    },
    created(){
      // this.loadTasks();
    },
    methods: {
      
    },
    computed: {
      filteredTasks(){
        return this.tasks.filter((task) => {
          return task.cardID == this.card.id;
        });
      }
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