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
        <BoardSettings :boardID="setting_boardID" v-if="setting_boardID" :colors="colors"/>
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
            <v-btn text :to="'dashboard/b/' + board.id">Перейти</v-btn>
          </div>
        </div>
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
      boards : [],
      setting_boardID : null,
    }
  },
  methods : {
    showBoardList(){
      this.axios_req('getBoardList', {
        'boardID' : 2
      }, (response) => {
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