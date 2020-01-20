<template>
  <v-row justify="center">
    <v-dialog v-model="dialog" width="600px">

      <div class="main-container" v-if="is_task_loaded">
      <!-- <div class="main-container" v-if="!false"> -->

        <div class="task-heading">
          <div v-if="is_title_editting">
            <v-text-field
              name="title"
              label="Новая задача"
              required
              hide-details
              filled
              v-model="title"
              :loading="title_loading"
              :disabled="title_loading"
            ></v-text-field>

            <div class="d-flex" style="padding-top: 1px">
              <v-spacer></v-spacer>
              <v-btn small depressed @click="editTitleCancel()" class="ml-1" color="warning">отмена</v-btn>
              <v-btn small depressed @click="editTitleSave()" class="ml-1" color="success">сохранить</v-btn>
            </div>
          </div>
          <div v-else>
            <span class="title" style="vertical-align:middle;">{{title}}</span>
            <v-btn depressed small @click="editTitle()" class="ml-2">
                <v-icon>mdi-playlist-edit</v-icon>
            </v-btn>
          </div>
        </div>

        <v-divider class="my-4"></v-divider>

        <div class="task-heading">
          <div v-if="is_description_editting">
            <v-textarea
              name="description"
              label="Опишите задачу (необязательно)"
              filled
              auto-grow
              rows="2"
              row-height="1"
              hide-details
              v-model="description"
              :loading="description_loading"
              :disabled="description_loading"
            ></v-textarea>

            <div class="d-flex" style="padding-top: 1px">
              <v-spacer></v-spacer>
              <v-btn small depressed @click="editDescriptionCancel()" class="ml-1" color="warning">отмена</v-btn>
              <v-btn small depressed @click="editDescriptionSave()" class="ml-1" color="success">сохранить</v-btn>
            </div>
          </div>
          <div v-else>
            <span class="body-2 pre-line" style="vertical-align:middle;">{{description}}</span>
            <v-btn depressed small v-if="description.trim()" @click="editDescription()" class="ml-2">
                <v-icon>mdi-playlist-edit</v-icon>
            </v-btn>
            <v-btn v-else depressed small  @click="editDescription()">
              Добавить описание
            </v-btn>
          </div>
        </div>

        <v-divider class="my-4"></v-divider>

        
        <v-dialog
          ref="deadline"
          v-model="deadline_modal"
          :return-value.sync="deadline"
          persistent
          width="290px"
        >
          <template v-slot:activator="{ on }">
            <v-text-field
              v-model="deadline"
              label="Указать дедлайн"
              prepend-icon="mdi-calendar-range"
              readonly
              clearable
              hide-details
              filled
              v-on="on"

              @click:clear="onClearDeadline"

              :loading="deadline_loading"
              :disabled="deadline_loading"
            ></v-text-field>
          </template>
          <v-date-picker locale="ru" v-model="deadline" scrollable>
            <v-spacer></v-spacer>
            <v-btn text color="primary" @click="deadline_modal = false">Вернуться</v-btn>
            <v-btn text color="primary" @click="editDeadlineSave()">Выбрать</v-btn>
          </v-date-picker>
        </v-dialog>

        <!-- <pre>{{deadline}}</pre> -->

        <v-divider class="my-8"></v-divider>

        <BoardTaskToDoList/>

        <v-divider class="my-8"></v-divider>

        <BoardComments/>
      </div>
      
      <div class="main-container" v-if="!is_task_loaded">
      <!-- <div class="main-container" v-if="!true"> -->

        <v-skeleton-loader
          class="mx-auto"
          type="heading"
        ></v-skeleton-loader>

        <v-divider class="my-4"></v-divider>

        <v-skeleton-loader
          class="mx-auto"
          type="paragraph"
        ></v-skeleton-loader>

        <v-divider class="my-4"></v-divider>

        <v-skeleton-loader
          class="mx-auto"
          style="margin-left: -16px;"
          type="list-item-avatar"
          tile
        ></v-skeleton-loader>

        <v-divider class="my-8"></v-divider>

        <v-skeleton-loader
          class="mx-auto"
          type="heading"
        ></v-skeleton-loader>

        <v-skeleton-loader
          class="mx-auto"
          type="list-item"
          style="margin-left: -16px;"
        ></v-skeleton-loader>

        <v-divider class="my-8"></v-divider>

        <v-skeleton-loader
          class="mx-auto"
          type="heading"
        ></v-skeleton-loader>

        <v-skeleton-loader
          class="mx-auto"
          type="list-item"
          style="margin-left: -16px;"
        ></v-skeleton-loader>

        <v-skeleton-loader
          class="mx-auto"
          style="margin-left: -16px;"
          type="list-item-two-line"
        ></v-skeleton-loader>
        <v-skeleton-loader
          class="mx-auto"
          style="margin-left: -16px;"
          type="list-item-two-line"
        ></v-skeleton-loader>
        <v-skeleton-loader
          class="mx-auto"
          style="margin-left: -16px;"
          type="list-item-two-line"
        ></v-skeleton-loader>

      </div>
    </v-dialog>
  </v-row>
</template>

<script>
  import {eventEmitter} from '../../main'
  import BoardComments from './BoardComments.vue'
  import BoardTaskToDoList from './BoardTaskToDoList.vue'
  import {SERVER_API} from '../../main'
  import axios from 'axios'
  export default {
    props : ['taskID'],
    data () {
      return {
        is_task_loaded : false,
        dialog: true,
        deadline_modal: false,

        title : '',
        is_title_editting: false,
        title_loading: false,

        description : '',
        is_description_editting: false,
        description_loading: false,
        
        deadline: null,
        deadline_loading: false,

        checkList : '',
        done : null,
        
        tmp : {},
      }
    },
    watch: {
      'dialog' : () => {
        // eslint-disable-next-line
        console.log('watch -> dialog');
        eventEmitter.$emit('close');
      },
    },
    created(){
      // eslint-disable-next-line
      console.log('created');
      this.loadTask(this.taskID);
    },
    destroyed(){
      // eslint-disable-next-line
      console.log('destroyed');
    },
    methods:{
      loadTask(id){
        this.is_task_loaded = false;
        axios.defaults.withCredentials = true;
        axios
          .get(SERVER_API + '?cmd=getSingleTask&data=' + JSON.stringify({
            'taskID' : id,
          }))
          .then(response => {
            // eslint-disable-next-line
            console.log(response.data);
            if (response.data.status == 'success') {
              // успешно
              this.title = response.data.task.title;
              this.description = response.data.task.description;
              if (response.data.task.deadline * 1) {
                // eslint-disable-next-line
                // console.log(response.data.task.deadline * 1);
                this.setStrDeadline(response.data.task.deadline * 1);
              }
              this.checkList = response.data.task.checkList;
              this.done = response.data.task.done;

              this.is_task_loaded = true;
            } else if (response.data.status == 'fail') {
              // ошибка
              // eslint-disable-next-line
              console.log(response);
            }
          })
          .catch(error => {
            // eslint-disable-next-line
            console.log(error);
          });
      },
      editTitle(){
        this.tmp.title = this.title;
        this.is_title_editting = true;
      },
      editTitleCancel(){
        this.title = this.tmp.title;
        this.is_title_editting = false;
      },
      editTitleSave(){
        this.title_loading = true;
        this.changeField('title', this.title, (new_value) => {
          this.is_title_editting = false;
          this.title = new_value.title;
        });
      },

      editDescription(){
        this.tmp.description = this.description;
        this.is_description_editting = true;
      },
      editDescriptionCancel(){
        this.description = this.tmp.description;
        this.is_description_editting = false;
      },
      editDescriptionSave(){

        this.description_loading = true;
        this.changeField('description', this.description, (new_value) => {
          this.is_description_editting = false;
          this.description = new_value.description;
        });
      },


      editDeadlineSave(){
        this.deadline_loading = true;

        let this_time = new Date(this.deadline).getTime() / 1000

        this.changeField('deadline', this_time, (new_value) => {
          this.setStrDeadline(new_value.deadline);
        });
      },
      setStrDeadline(ts){
        if (ts) {
          let time = this.getStringifyDate(ts * 1000);
          this.$refs.deadline.save(time);
          this.deadline = time;
        } else {
          this.$refs.deadline.save('');
          this.deadline = '';
        }
        this.deadline_modal = false;

      },
      onClearDeadline(){
        this.deadline_loading = true;

        this.changeField('deadline', '', () => {
          this.setStrDeadline('');
        });
      },

      changeField(fieldName, newVal, fallback_fnc){
        axios.defaults.withCredentials = true;
        axios
          .get(SERVER_API + '?cmd=changeTaskField&data=' + encodeURIComponent(JSON.stringify({
            'taskID' : this.taskID,
            'field' : fieldName,
            'value' : newVal,
          })))
          .then(response => {
            // eslint-disable-next-line
            console.log(response.data);
            if (response.data.status == 'success') {
              // успешно
              fallback_fnc(response.data.new_value);
            } else if (response.data.status == 'fail') {
              // ошибка
              // eslint-disable-next-line
              console.log(response);
            }
          })
          .catch(error => {
            // eslint-disable-next-line
            console.log(error);
          })
          .finally(() => {
            switch(fieldName){
              case 'title':
                this.title_loading = false
                break;
              case 'description':
                this.description_loading = false
                break;
              case 'deadline':
                this.deadline_loading = false
                break;
              default:
                this.loading = false
            }
          });
      },
      getStringifyDate(ts){
        let date = new Date(ts);
        let str = 'yyyy-mm-dd';

        let yyyy = date.getFullYear();
        let mm = ("0" + (date.getMonth() + 1)).slice(-2);
        let dd = ("0" + date.getDate()).slice(-2);

        str = str.replace('yyyy', yyyy);
        str = str.replace('mm', mm);
        str = str.replace('dd', dd);

        return str;
      },
    },
    components: {
      BoardComments,
      BoardTaskToDoList,
    }
  }
</script>

<style scoped>
  .main-container {
    cursor: auto;
    background-color: #fff;
    padding: 1rem;
  }
  .title {
    font-weight: 400;
  }
  .pre-line {
    white-space: pre-line;
  }
</style>