<template>
  <div class="main elevation-8" :class="{'opened' : show_actions}">
    <div class="show-hide-btn">
      <v-icon @click="show_actions = !show_actions" small v-if="show_actions">mdi-arrow-collapse-right</v-icon>
      <v-icon @click="show_actions = !show_actions" small v-else>mdi-arrow-collapse-left</v-icon>
    </div>
    <div class="actions-wrapper  py-5 px-4">
      <h1 class="headline mb-5 text-center">Последние действия</h1>
      <div class="actions">
       
        <v-card v-for="(action, i) in actions" :key="i" class="mb-5">
          <v-card-title>{{users[action.userID].fullName}}</v-card-title>
          <v-card-subtitle>@{{users[action.userID].login}}</v-card-subtitle>
          <v-card-text>
            <div v-if="actionTypes[action.actionType]">
              <div v-if="actionTypes[action.actionType].type == 'task'">
                <!-- задача -->
                <div v-if="action.actionType == 'task_created'">
                  <b>{{actionTypes[action.actionType].text}}</b> => <b>{{tasks[action.actionID].title}}</b>
                </div>
                <div v-else>
                  <b>{{actionTypes[action.actionType].text}}</b>
                  в задаче: 
                  <b>{{tasks[action.actionID].title}}</b>
                </div>
              </div>
              <div v-else-if="actionTypes[action.actionType].type == 'card'">
                <!-- карточка -->
                <div v-if="action.actionType == 'card_created'">
                  <b>{{actionTypes[action.actionType].text}}</b> => <b>{{cards[action.actionID].title}}</b>
                </div>
                <div v-else>
                  <b>{{actionTypes[action.actionType].text}}</b> в карточке: <b>{{cards[action.actionID].title}}</b>
                </div>
              </div>
              <div v-else-if="actionTypes[action.actionType].type == 'users'">
                <!-- пользователи -->
                <div v-if="action.actionType == 'board_participant_added'">
                  {{actionTypes[action.actionType].text}}: <b>{{users[action.actionID].fullName}}</b> в доску
                </div>
              </div>
              <div v-else-if="actionTypes[action.actionType].type == 'board'">
                <!-- доски -->
                  {{actionTypes[action.actionType].text}}
                <div v-if="action.actionType == 'board_participant_added'">
                </div>
              </div>
            </div>
          </v-card-text>
          <v-card-subtitle class="pt-0 pb-1 text-right action-date">{{getStringifyDate(action.time * 1000)}}</v-card-subtitle>
        </v-card>

      </div>
    </div>
  </div>
</template>
<script>
  export default {
    props: ['boardID'],
    data () {
      return {
        show_actions : !false,
        actionTypes : {
          task_created : {
            text : 'Создал(а) задачу',
            type : 'task'
          },
          task_comment_added : {
            text : 'Добавил(а) коментарий',
            type : 'task'
          },
          task_checkList_changed : {
            text : 'Изменил(а) чек-лист',
            type : 'task'
          },
          task_field_deadline_changed : {
            text : 'Изменил(а) дедлайн',
            type : 'task'
          },
          task_field_description_changed : {
            text : 'Изменил(а) описание',
            type : 'task'
          },
          card_field_title_changed : {
            text : 'Изменил(а) название',
            type : 'task'
          },


          board_field_is_private_changed : {
            text : 'Изменил(а) доступ к доске',
            type : 'board'
          },
          board_field_color_changed : {
            text : 'Изменил(а) цвет к доске',
            type : 'board'
          },
          board_field_title_changed : {
            text : 'Изменил(а) название к доске',
            type : 'board'
          },
          board_created : {
            text : 'Создал(а) доску',
            type : 'board'
          },


          card_created : {
            text : 'Создал(а) карточка',
            type : 'card'
          },


          board_participant_added : {
            text : 'Добавил(а) нового участника',
            type : 'users'
          },
        },
        actions : [],
        users : {},
        cards : {},
        tasks : {},

        timerID : null,
      }
    },
    created(){
      this.loadActions();
    },
    beforeDestroy(){
      if (this.timerID) {
        clearTimeout(this.timerID);
        this.timerID = null;
      }
    },
    methods: {
      loadActions(){
        this.axios_req('getActions', {
          data : {
            'boardID' : this.boardID
          }
        }, (response) => {

          if (response.data.status == 'success') {
            this.actions = [];
            this.users = {};
            this.cards = {};
            this.tasks = {};

            this.actions = response.data.actions;

            response.data.users.forEach(user => {
              this.users[user.id] = user;
            });

            response.data.cards.forEach(card => {
              this.cards[card.id] = card;
            });

            response.data.tasks.forEach(task => {
              this.tasks[task.id] = task;
            });
            // eslint-disable-next-line
            // console.log(this.tasks);


          }

        }, ()=>{
          // обновляем список действий каждые 10 секунд
          this.timerID = setTimeout(()=>{
            this.loadActions();
          }, 1000 * 10);
        });
      },

      getUser(id){
        return this.users.filter(user => (user.id == id))[0]
      }
    },
    computed : {},
  }
</script>
<style scoped>
  .main {
    width: 400px;
    max-width: 100%;
    position: absolute;
    bottom: 0;
    right: 0;
    top: 0;

    /*background-color: #f00;*/
    transition: .2s;

    transform: translateX(100%);
  }
  .main.opened {
    transform: translateX(0%);
  }
  .show-hide-btn {
    position: absolute;
    right: 100%;
    top: 5px;

    margin-right: 10px;

    cursor: pointer;
  }
  .actions-wrapper {
    max-height: 100%;
    overflow-y: auto;
  }

  .actions {}
  .action-date {
    /*position: absolute;*/
    font-size: 10px;
  }
  .actions-item {
    padding: 0 7px 20px 7px;
    margin-bottom: 20px;
    border-bottom: 1px solid #aaa;
  }
  .actions-item:last-child {
    border-bottom: none;
  }
  /*.actions-head {
    margin-bottom: 5px;
    font-size: 16px;
    line-height: 1.2;
  }*/
  .actions-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }
  .actions-head-user {
    font-size: 15px;
    white-space: nowrap;
    text-overflow: ellipsis;
    overflow: hidden;
  }
  .actions-head-user--login {
    color: #aaa;
    font-size: 13px;

  }
  .actions-head-date {
    margin-left: 10px;
    font-size: 11px;
    color: #aaa;

    white-space: nowrap;
  }
  .actions-desc {
    padding-top: 15px;
    font-size: 15px;
  }

</style>