<template>
  <v-container
  >
    <v-row
    >
      <v-col
        cols="12"
        sm="12"
      >
        <h1 class="headline">Ваши доски</h1>
        <BoardSettings :boardID="setting_boardID" v-if="setting_boardID" :colors="colors" @loadBoards="showBoardList()"/>
        <v-dialog
          v-model="removePopup"
          width="500"
        >
          <v-card>
            <v-card-title
              class="headline grey lighten-2 justify-center"
              primary-title
            >
              Удалить доску?
            </v-card-title>

            <v-card-text class="pt-3" v-if="tmp.boardID">
              Доска <b>"{{boards[tmp.localBoardID].title}}"</b> будет удалена навсегда.
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions class="justify-center">
              <v-btn
                small
                color="warning"
                class="mx-1"
                @click="removeBoard(tmp.boardID, tmp.localBoardID);"
                :loading="removing_loading"
              >
                Да, удалить
              </v-btn>
              <v-btn
                small
                color="transparent"
                class="mx-1"
                @click="removePopup = !removePopup"
                :disabled="removing_loading"
              >
                Нет, оставить
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-dialog>
      </v-col>
    </v-row>
    <v-row
    >
      <v-col
        cols="12"
        sm="6"
        md="4"
        lg="3"
        xl="2"
      >
        <BoardCreate :colors="colors" @boardListUpdate="showBoardList()"/>
      </v-col>

      <v-col
        cols="12"
        sm="6"
        md="4"
        lg="3"
        xl="2"

        v-for="(board,i) in boards"
        :key="i"
      >
        <div class="board  elevation-12" :style="{'background-color' : colors[board.color].bgcolor, 'color' : colors[board.color].textcolor }">
          <div class="setting-btn-wrapper" v-if="board.is_owner*1">
            
            <v-btn small icon @click="removePopup = !removePopup; tmp.boardID = board.id; tmp.localBoardID = i">
              <v-icon small>mdi-delete</v-icon>
            </v-btn>

            <v-btn small icon @click="openSettings(board.id)">
              <v-icon small>mdi-settings</v-icon>
            </v-btn>
          </div>
          
          <div class="mb-1">
            <div class="board___type" v-if="board.is_owner*1">{{(board.is_private == 1)?'Приватный':'Публичный'}}</div>
            <div class="board___type" v-else>
              <v-icon class="mr-1">mdi-link</v-icon>
              По приглашению
            </div>
          </div>

          <h2 class="board___title">{{board.title}}</h2>
          <div class="d-flex  align-center  mt-auto">
            <span class="board___date">{{getStringifyDate(board.created_time * 1000)}}</span>
            <v-spacer></v-spacer>
            <v-btn text :to="'board/' + board.id">Перейти</v-btn>
          </div>
        </div>
      </v-col>

      <v-col
        cols="12"
        sm="6"
        md="4"
        lg="3"
        xl="2"

        v-for="num in 10"
        :key="num + '__v-skeleton-loader'"

        v-show="!boards"
      >
        <v-skeleton-loader
          class="mx-auto"
          height="220"
          max-height="220"
          type="image"
        ></v-skeleton-loader>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import {eventEmitter} from '../../main'
import BoardCreate from './BoardCreate.vue'
import BoardSettings from './BoardSettings.vue'

export default {
  data () {
    return {
      colors: [
          {
            'bgcolor' : 'white',
            'textcolor' : '#000',
          },
          {
            'bgcolor' : 'orange',
            'textcolor' : '#000',
          },
          {
            'bgcolor' : 'red',
            'textcolor' : '#fff',
          },
          {
            'bgcolor' : 'pink',
            'textcolor' : '#000',
          },
          {
            'bgcolor' : 'purple',
            'textcolor' : '#fff',
          },
          {
            'bgcolor' : 'cyan',
            'textcolor' : '#000',
          },
          {
            'bgcolor' : 'teal',
            'textcolor' : '#000',
          },
          {
            'bgcolor' : 'lime',
            'textcolor' : '#000',
          },
        ],
      boards : null,
      setting_boardID : null,
      removePopup : false,
      removing_loading : false,

      tmp : {}
    }
  },
  methods : {
    showBoardList(){
      this.axios_req('getBoardList', {}, (response) => {
        if (response.data.status == 'success') {
            // успешно
            this.boards = response.data.boards;
          } else if (response.data.status == 'fail') {
            // ошибка
            // eslint-disable-next-line
            console.log(response);
          }
      }, ()=>{
        // finally
        this.loading = false
      });
    },
    openSettings(boardID){
      this.setting_boardID = boardID;
    },
    removeBoard(boardID, localID){
      this.removing_loading = true;
      this.axios_req('removeBoard', {
        data : {
          'boardID' : boardID
        }
      }, (response) => {
        if (response.data.status == 'success') {
            // успешно
            this.boards.splice(localID, 1);
          } else if (response.data.status == 'fail') {
            // ошибка
            // eslint-disable-next-line
            console.log(response);
          }
      }, ()=>{
        // finally
        this.removing_loading = false;
        this.removePopup = false;
      });
    }
  },
  created(){
    this.showBoardList();

    eventEmitter.$on('closeSettings', () => {
      this.setting_boardID = null;
    });
  },
  components: {
    BoardCreate,
    BoardSettings,
  }
}
</script>
<style>

.board {
  /*width: 290px;*/
  min-height: 220px;

  display: flex;
  flex-direction: column;
  padding: 15px;

  position: relative;
}
.board--dotted {
  border: 2px dashed #999;
}
.board--dotted:hover {
  border-color: #333;
}
.setting-btn-wrapper {
  position: absolute;
  right: 0;
  top: 0;
  opacity: .5;
}
.board:hover .setting-btn-wrapper {
  opacity: 1;
}
.board___type {
  font-size: 12px;
  /*opacity: .5;*/
  min-height: 24px;
}
.board___title {
  line-height: 1;
  font-weight: 400;
  line-height: 1.2;
  overflow: hidden;
  text-overflow: ellipsis;
}
.board___date {
  font-size: 10px;
  opacity: .95;
}

</style>