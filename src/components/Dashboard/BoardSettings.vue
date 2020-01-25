<template>
  <v-row justify="center">
    <v-dialog v-model="dialog" width="600px">
      <div class="main-container" :style="{'background-color' : color_active.bgcolor, 'color' : color_active.textcolor }">

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

        <v-checkbox
          v-model="is_private"
          :label="'Приватный?'"
          color="orange"
          hide-details

          :loading="is_private_loading"
          :disabled="is_private_loading"

          @change="saveAccessable()"
        ></v-checkbox>

        <v-divider class="my-4"></v-divider>

        <div
          class="colors"
          :class="{'colors--loading' : color_loading}"
        >
          <div class="colors__item"
               v-for="(color,i) in colors"
               :key="i"
               :style="{'background-color': color.bgcolor}"
               :class="{'colors__item--active': color === color_active}"
               @click="setColor(i)"
               ></div>
        </div>

        <v-divider class="my-4"></v-divider>

        <v-card :loading="new_participant_loading">
          <v-list class="pb-5">
            <v-subheader>Все участники:</v-subheader>
            <div
              v-for="(participant, i) in participants"
              :key="i"
            >
              <div class="px-4 py-1">
                <div>
                  <v-icon small class="mr-2">mdi-account</v-icon>
                  {{participant.fullName}}
                  (<span class="grey--text">@{{participant.login}}</span>)
                </div>
              </div>
            </div>
            <v-divider class="my-2"></v-divider>
            <v-list-item class="d-block">
              <v-form
                @submit.prevent="addParticipant()"
              >
                <v-text-field
                  label="Логин участника"
                  hide-details
                  required
                  :clearable="true"

                  @input="new_participant_code = ''"

                  v-model="new_participant"
                  :disabled="new_participant_field_loading"
                  :loading="new_participant_field_loading"
                ></v-text-field>
                <div class="text-center  red--text  mt-4" v-if="new_participant_code">{{new_participant_lists[new_participant_code]}}</div>
                <v-btn class="mt-4" @click="addParticipant()" :disabled="new_participant_field_loading">
                  <v-icon small class="mr-2">mdi-account-plus</v-icon>
                  Добавить участника
                </v-btn>
              </v-form>
            </v-list-item>
          </v-list>
        </v-card>

        <v-divider class="my-4"></v-divider>

      </div>
    </v-dialog>
  </v-row>
</template>

<script>
  import {eventEmitter} from '../../main'
  
  export default {
    props : ['boardID', 'colors'],
    data () {
      return {
        is_board_loaded : false,
        dialog: true,

        title : '',
        is_title_editting: false,
        title_loading: false,

        is_private : null,
        is_private_loading : false,

        color_active : {},
        color_loading : false,

        tmp : {},

        participants : [],

        new_participant : '',
        new_participant_loading : false,
        new_participant_field_loading : false,
        new_participant_code : '',
        new_participant_lists : {
          'already_added' : 'Пользователь уже участвует в данной доске',
          'login_not_exists' : 'Такого пользователя не существует',
          'login_incorrect' : 'Неверный логин',
          'fail' : 'Что-то пошло не так. Попробуйте позже.',
          'login_is_empty' : 'Введите логин',
        },
      }
    },
    watch: {
      'dialog' : () => {
        // eslint-disable-next-line
        console.log('watch -> dialog');
        eventEmitter.$emit('closeSettings');
      },
    },
    created(){
      // eslint-disable-next-line
      console.log('created');
      this.loadBoard(this.boardID);
      this.loadParticipants(this.boardID);
    },
    destroyed(){
      // eslint-disable-next-line
      console.log('destroyed');
    },
    methods:{
      loadBoard(id){
        this.is_board_loaded = false;
        this.axios_req('getSingleBoard', {
          data : {
            'boardID' : id,
          }
        }, (response) => {
          // eslint-disable-next-line
          console.log(response);
          if (response.data.status == 'success') {
            // успешно
            this.title = response.data.board.title;
            this.is_private = !!(response.data.board.is_private * 1);
            this.color_active = this.colors[response.data.board.color];

          } else if (response.data.status == 'fail') {
            // ошибка
            // eslint-disable-next-line
            // console.log(response);
          }
        }, ()=>{
          // finally
          this.is_board_loaded = true;
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


      saveAccessable(){
        this.is_private_loading = true;
        let is_private = (this.is_private*1)?'1':'0';

        // eslint-disable-next-line
        // console.log('before send:', is_private);

        this.changeField('is_private', is_private, (new_value) => {
          this.is_private = !!(new_value.is_private * 1);
        });
      },

      setColor(color){
        this.color_loading = true;

        // eslint-disable-next-line
        console.log('before send:', color);

        this.changeField('color', color, (new_value) => {
          this.color_active = this.colors[new_value.color * 1];
        });
      },

      addParticipant(){
        this.new_participant_code = '';

        if (this.new_participant.trim()) {
          this.new_participant_field_loading = true

          this.axios_req('addParticipant', {
            data : {
              'boardID' : this.boardID,
              'login' : this.new_participant,
            }
          }, (response) => {
            // eslint-disable-next-line
            console.log(response.data);
            if (response.data.status == 'success') {
                this.new_participant = '';
                this.loadParticipants(this.boardID);
            } else {
              if (response.data.status && this.new_participant_lists[response.data.status]) {
                this.new_participant_code = response.data.status
              } else {
                this.new_participant_code = 'fail';
              }
            }
          }, ()=>{
            // finally
            this.new_participant_field_loading = false
          });
        } else {
          this.new_participant_code = 'login_is_empty';
        }

      },

      loadParticipants(id){
        this.new_participant_loading = true;
        this.axios_req('loadParticipants', {
          data : {
            'boardID' : id,
          }
        }, (response) => {
          // eslint-disable-next-line
          console.log(response);
          if (response.data.status == 'success') {
            // успешно
            this.participants = response.data.participants;
          } else if (response.data.status == 'fail') {
            // ошибка
          }
        }, ()=>{
          // finally
          this.new_participant_loading = false;
        });
      },

      changeField(fieldName, newVal, fallback_fnc){
        this.axios_req('changeBoardField', {
          data : {
            'boardID' : this.boardID,
            'field' : fieldName,
            'value' : newVal,
          }
        }, (response) => {
            // eslint-disable-next-line
            console.log(response.data);
            if (response.data.status == 'success') {
              // успешно
              fallback_fnc(response.data.new_value);
              this.$emit('loadBoards');
            } else if (response.data.status == 'fail') {
              // ошибка
              // eslint-disable-next-line
              console.log(response);
            }
        }, ()=>{
          // finally
          switch(fieldName){
            case 'title':
              this.title_loading = false
              break;
            case 'is_private':
              this.is_private_loading = false
              break;
            case 'color':
              this.color_loading = false
              break;
            default:
              this.loading = false
          }
        });
      },
    },
    components: {
    }
  }
</script>

<style scoped>
  .colors--loading {
    pointer-events: none;
    filter: grayscale(1);
  }
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