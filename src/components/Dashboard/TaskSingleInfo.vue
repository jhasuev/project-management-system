<template>
  <v-row justify="center">
    <v-dialog v-model="dialog" width="600px">
      <div class="main-container">

        <div class="task-heading">
          <div v-if="is_title_editting">
            <v-text-field
              name="title"
              label="Новая задача"
              required
              hide-details
              filled
              v-model="title"
            ></v-text-field>

            <div class="d-flex" style="padding-top: 1px">
              <v-spacer></v-spacer>
              <v-btn small depressed @click="is_title_editting = false" class="ml-1" color="warning">отмена</v-btn>
              <v-btn small depressed @click="is_title_editting = false" class="ml-1" color="success">сохранить</v-btn>
            </div>
          </div>
          <div v-else>
            <span class="title" style="vertical-align:middle;">{{title}}</span>
            <v-btn depressed small @click="is_title_editting = true" class="ml-2">
                <v-icon>mdi-playlist-edit</v-icon>
            </v-btn>
          </div>
        </div>

        <v-divider class="my-4"></v-divider>

        <div class="task-heading">
          <div v-if="is_desc_editting">
            <v-textarea
              name="desc"
              label="Опишите задачу (необязательно)"
              filled
              auto-grow
              rows="2"
              row-height="1"
              hide-details
              v-model="desc"
            ></v-textarea>

            <div class="d-flex" style="padding-top: 1px">
              <v-spacer></v-spacer>
              <v-btn small depressed @click="is_desc_editting = false" class="ml-1" color="warning">отмена</v-btn>
              <v-btn small depressed @click="is_desc_editting = false" class="ml-1" color="success">сохранить</v-btn>
            </div>
          </div>
          <div v-else>
            <span class="body-2" style="vertical-align:middle;">{{desc}}</span>
            <v-btn depressed small v-if="desc.trim()" @click="is_desc_editting = true" class="ml-2">
                <v-icon>mdi-playlist-edit</v-icon>
            </v-btn>
            <v-btn v-else depressed small  @click="is_desc_editting = true">
              Добавить описание
            </v-btn>
          </div>
        </div>

        <v-divider class="my-4"></v-divider>

        
        <v-dialog
          ref="dialog"
          v-model="modal"
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
            ></v-text-field>
          </template>
          <v-date-picker locale="ru" v-model="deadline" scrollable>
            <v-spacer></v-spacer>
            <v-btn text color="primary" @click="modal = false">Вернуться</v-btn>
            <v-btn text color="primary" @click="$refs.dialog.save(deadline)">Выбрать</v-btn>
          </v-date-picker>
        </v-dialog>

        <!-- <pre>{{deadline}}</pre> -->

        <v-divider class="my-8"></v-divider>

        <BoardTaskToDoList/>

        <v-divider class="my-8"></v-divider>

        <BoardComments/>

      </div>
    </v-dialog>
  </v-row>
</template>

<script>
  import BoardComments from './BoardComments.vue'
  import BoardTaskToDoList from './BoardTaskToDoList.vue'
  export default {
    props : ['taskID'],
    data () {
      return {
        dialog: true,
        title : 'Задача котору нужно выполнить...',
        is_title_editting: false,
        desc : ' ',
        is_desc_editting: false,
        deadline: null,
        modal: false,
      }
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
</style>