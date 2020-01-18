<!-- https://github.com/SortableJS/Vue.Draggable -->
<template>
  <div
    class="cols overflow-x-auto grab-bing"
    v-dragscroll:nochilddrag
    @dragscrollstart="blurInputs()"
    @click.self="blurInputs()"
  >
    <div class="cols__item"
      @click.self="blurInputs()"
      v-for="(card,i) in cards" :key="i"
    >
      <Card :card="card" :boardID="id" :tasks="tasks"/>
    </div>

    <div class="cols__item  cols__item--creating"
      @click.self="blurInputs()"
    >
      <CardCreateForm :boardID="id" @cardsUpdate="loadCards();"/>
    </div>

    <TaskSingleInfo :taskID="2" v-if="false"/>
  </div>
</template>
<script>
  import {eventEmitter} from '../../main'
  import { dragscroll } from 'vue-dragscroll' // vue-dragscroll | https://www.npmjs.com/package/vue-dragscroll
  import CardCreateForm from './CardCreateForm.vue'
  import Card from './Card.vue'
  import TaskSingleInfo from './TaskSingleInfo.vue'
  import {SERVER_API} from '../../main'
  import axios from 'axios'

  export default {
    props: ['id'],
    data () {
      return {
        cards : [],
        tasks : [],
      }
    },
    created(){

      this.setTitle();
      this.loadCards();
      this.loadTasks();

      // tasksUpdate
      eventEmitter.$on('tasksUpdate', this.loadTasks);
    },
    methods: {
      blurInputs(){

        document.querySelectorAll("input").forEach(function(input) {
          // eslint-disable-next-line
          // console.log(i, x);
          input.blur();
        })
      },
      setTitle(){
        axios.defaults.withCredentials = true;
        axios
          .get(SERVER_API + '?cmd=getBoardTitle&data=' + JSON.stringify({
            'boardID' : this.id,
          }))
          .then(response => {
            // eslint-disable-next-line
            console.log('getBoardTitle', response.data);
            if (response.data.status == 'success') {
              // успешно
              eventEmitter.$emit("change_title", response.data.boardTitle);
            } else if (response.data.status == 'fail') {
              // ошибка
            }
          })
          .catch(error => {
            // eslint-disable-next-line
            console.log(error);
          });
      },
      loadCards(){
        axios.defaults.withCredentials = true;
        axios
          .get(SERVER_API + '?cmd=getCards&data=' + JSON.stringify({
            'boardID' : this.id,
          }))
          .then(response => {
            // eslint-disable-next-line
            console.log('getCards', response.data);
            if (response.data.status == 'success') {
              // успешно
              this.cards = response.data.cards;
            } else if (response.data.status == 'fail') {
              // ошибка
            }
          })
          .catch(error => {
            // eslint-disable-next-line
            console.log(error);
          });
      },
      loadTasks(){
        axios.defaults.withCredentials = true;
        axios
          .get(SERVER_API + '?cmd=getTasks&data=' + JSON.stringify({
            'boardID' : this.id,
          }))
          .then(response => {
            // eslint-disable-next-line
            console.log('getTasks', response.data);
            if (response.data.status == 'success') {
              // успешно
              this.tasks = response.data.tasks;
            } else if (response.data.status == 'fail') {
              // ошибка
            }
          })
          .catch(error => {
            // eslint-disable-next-line
            console.log(error);
          });
      },
    },
    directives: {
      'dragscroll': dragscroll
    },
    components: {
      CardCreateForm,
      Card,
      TaskSingleInfo,
    }
  }
</script>
<style>

.cols {
  display: flex;
  align-items: flex-start;
  min-height: 100%;
  padding: 16px 0;
  /*margin: 0 16px;*/
}
.cols:after {
  content: "";
  width: 16px;
  max-width: 16px;
  min-width: 16px;
  height: 1px;
}

.cols__item {
  padding: 10px;
  border: 1px solid #ddd;
  background-color: #eee;

  min-width: 290px;
  max-width: 290px;
  border-radius: 3px;

  margin-left: 16px;
  cursor: auto;
}
.cols__item:last-child {
  /*margin-left: 16px;*/
}
.cols__item--creating {
  padding: 0;
  border-style: dashed;
  border-width: 2px;
  background-color: transparent;
}

/*******************************/
.grab-bing {
  cursor : -webkit-grab;
  cursor : -moz-grab;
  cursor : -o-grab;
  cursor : grab;
}
.grab-bing:active {
  cursor : -webkit-grabbing;
  cursor : -moz-grabbing;
  cursor : -o-grabbing;
  cursor : grabbing;
}


/*scroll styling*/
.grab-bing::-webkit-scrollbar-track {
  background-color: transparent;
}

.grab-bing::-webkit-scrollbar {
  width: 2px;
  height: 2px;
  background-color: transparent;
}

.grab-bing::-webkit-scrollbar-thumb {
  background-color: #666;
}

</style>