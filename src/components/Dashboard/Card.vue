<template>
  <div>
    <div v-if="is_title_editing" class="mb-3">
      <v-text-field
        name="title"
        label="Название карточки"
        required
        hide-details
        filled
        v-model="card.title"
        :loading="title_loading"
        :disabled="title_loading"
        @keydown.enter="editTitleSave()"
      ></v-text-field>

      <div class="d-flex">
        <v-spacer></v-spacer>
        <v-btn icon small @click="editTitleSave()" class="ml-1">
          <v-icon small>mdi-content-save</v-icon>
          <!-- сохранить -->
        </v-btn>
      </div>
    </div>
    <v-tooltip top v-else>
      <template v-slot:activator="{ on }">
        <!-- <div class="card-title" v-on="on">{{card.title}}</div> -->
        <div class="d-flex align-center mb-2">
          
          <span class="card-title-edit mr-2" @click="is_title_editing = !is_title_editing">
            <v-icon small>mdi-pencil</v-icon>
          </span>

          <span class="card-title  flex-grow-1" v-on="on">{{card.title}}</span>
        </div>
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

    <TaskSingleInfo :boardID="boardID" :taskID="editTaskID" v-if="editTaskID"/>

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
        is_title_editing : false,
        title_loading : false,
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
      },
      editTitleSave(){
        if (this.card.title && this.card.title.trim()) {
          this.title_loading = true;
          this.axios_req('changeCardField', {
            data : {
              'boardID' : this.boardID,
              'cardID' : this.card.id,
              'field' : 'title',
              'value' : this.card.title,
            }
          }, (response) => {
              if (response.data.status == 'success') {
                // успешно
                this.is_title_editing = false;
                this.card.title = response.data.new_value.title;
              } else if (response.data.status == 'fail') {
                // ошибка
                // eslint-disable-next-line
                console.log(response);
              }
          }, ()=>{
            // finally
            this.title_loading = false
          });
        }
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
    /*margin-bottom: 11px;*/
    font-size: 18px;

    cursor: pointer;
  }

  .card-title-edit {
    cursor: pointer;
  }

</style>