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
        <board-create :colors="colors"></board-create>
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
        <div class="board  elevation-12" :style="{'background-color' : board.color.bgcolor, 'color' : board.color.textcolor }">
          <div class="board___type">{{(board.private)?'Приватный':'Публичный'}}</div>
          <h2 class="board___title">{{board.title}}</h2>
          <div class="d-flex  align-center  mt-auto">
            <span class="board___date">{{getStringifyDate(board.created_time)}}</span>
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
      boards : [
        {
          id : 1,
          title : 'Моя доска #1',
          color : {
            'bgcolor' : 'orange',
            'textcolor' : '#000',
          },
          private : true,
          created_time : 1576578897117
        },
        {
          id : 2,
          title : 'Моя доска #2',
          color : {
            'bgcolor' : 'red',
            'textcolor' : '#fff',
          },
          private : true,
          created_time : 1576578817117
        },
        {
          id : 3,
          title : 'Моя доска #3',
          color : {
            'bgcolor' : 'pink',
            'textcolor' : '#000',
          },
          private : false,
          created_time : 1576578897117
        },
        {
          id : 4,
          title : 'Моя доска #4',
          color : {
            'bgcolor' : 'purple',
            'textcolor' : '#fff',
          },
          private : true,
          created_time : 1576578897117
        },
        {
          id : 5,
          title : 'Моя доска #5',
          color : {
            'bgcolor' : 'cyan',
            'textcolor' : '#000',
          },
          private : true,
          created_time : 1576518894117
        },
        {
          id : 6,
          title : 'Моя доска #6',
          color : {
            'bgcolor' : 'teal',
            'textcolor' : '#000',
          },
          private : true,
          created_time : 1576578897117
        },
        {
          id : 7,
          title : 'Моя доска #7',
          color : {
            'bgcolor' : 'lime',
            'textcolor' : '#000',
          },
          private : false,
          created_time : 1571573897117
        },
        {
          id : 8,
          title : 'Моя доска #8',
          color : {
            'bgcolor' : 'lime',
            'textcolor' : '#000',
          },
          private : false,
          created_time : 1571573897117
        },
      ],
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
    }
  },
  components: {
    BoardCreate
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
}
.board___date {
  font-size: 10px;
  opacity: .95;
}

</style>