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
              @click="openTask(task.id)"
            >
              <v-list-item-content>
                <v-list-item-title>{{task.title}}</v-list-item-title>
                <v-list-item-subtitle class="text--primary" v-if="task.checkList || (task.deadline * 1)">
                  <div class="pt-2 d-flex align-center">
                    <v-icon class="mr-auto" v-if="task.checkList">mdi-format-list-checkbox</v-icon>
                    <v-icon class="ml-auto" v-if="(task.deadline * 1)">mdi-calendar-today</v-icon>
                  </div>
                </v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-divider v-if="(filteredTasks.length - 1) != i"></v-divider>
          </div>
        </v-list-item-group>
      </v-list>
    </v-card>

    <TaskSingleInfo :taskID="editTaskID" v-if="editTaskID"/>

  </div>
</template>
<script>
  import {eventEmitter} from '../../main'
  import CardTaskCreateForm from './CardTaskCreateForm.vue'
  import TaskSingleInfo from './TaskSingleInfo.vue'
  // import {SERVER_API} from '../../main'
  // import axios from 'axios'

  export default {
    props: ['boardID', 'card', 'tasks'],
    data () {
      return {
        editTaskID : 0,
      }
    },
    created(){
      // this.loadTasks();
      eventEmitter.$on("close", ()=>{
        // eslint-disable-next-line
        console.log('eventEmitter.$on("close.....');
        this.editTaskID = 0;
      });
    },
    methods: {
      openTask(id){
        this.editTaskID = id;
        // eslint-disable-next-line
        console.log('------------------', id);
      }
    },
    computed: {
      filteredTasks(){
        if (typeof this.tasks != 'object') {
          return [];
        }
        return this.tasks.filter((task) => {
          return task.cardID == this.card.id;
        });
      }
    },
    components: {
      CardTaskCreateForm,
      TaskSingleInfo,
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