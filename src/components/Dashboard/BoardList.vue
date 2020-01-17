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
        <board-create :colors="colors" @boardListUpdate="showBoardList()"></board-create>
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
          <div class="board___type">{{(board.is_private == 1)?'Приватный':'Публичный'}}</div>
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

import BoardCreate from './BoardCreate.vue'
import {SERVER_API} from '../../main'
import axios from 'axios'

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
    }
  },
  methods : {
    getStringifyDate(ts){
      let date = new Date(ts);
      let str = 'dd.mm.yyyy в hh:mins';

      let dd = ("0" + date.getDate()).slice(-2);
      let mm = ("0" + (date.getMonth() + 1)).slice(-2);
      let yyyy = date.getFullYear();
      let hh = ("0" + date.getHours()).slice(-2);
      let mins = ("0" + date.getMinutes()).slice(-2);

      dd;mm;yyyy;hh;mins;


      str = str.replace('dd', dd);
      str = str.replace('mm', mm);
      str = str.replace('yyyy', yyyy);
      str = str.replace('hh', hh);
      str = str.replace('mins', mins);

      return str;
    },
    showBoardList(){
      axios.defaults.withCredentials = true;
      axios
        .get(SERVER_API + '?cmd=getBoardList')
        .then(response => {
          // eslint-disable-next-line
          // console.log(response.data);
          if (response.data.status == 'success') {
            // успешно
            this.boards = response.data.boards;
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
        .finally(() => (this.loading = false));
    },
  },
  created(){
    this.showBoardList();
  },
  components: {
    BoardCreate,
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
}
.board--dotted {
  border: 2px dashed #999;
}
.board--dotted:hover {
  border-color: #333;
}
.board___type {
  font-size: 12px;
  /*opacity: .5;*/
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