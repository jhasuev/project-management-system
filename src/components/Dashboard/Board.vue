<!-- https://github.com/SortableJS/Vue.Draggable -->
<template>
  <div>
    <!-- <div class="pr-4 pt-4 text-right">
      <v-btn small icon>
        <v-icon small>mdi-settings</v-icon>
      </v-btn>
    </div> -->

    <div
      class="cols overflow-x-auto grab-bing"
      v-dragscroll:nochilddrag
      @dragscrollstart="blurInputs()"
      @click.self="blurInputs()"
      v-if="false"
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
    </div>

    <v-card :loading="is_sorting" style="height: 4px; overflow: hidden; margin-bottom: -4px;" class="elevation-0"></v-card>
    <div class="cols-wrapper  overflow-x-auto">
      <draggable
        @change="cardsSorted"
        v-model="cards"
        :class="{'is-sorting' : is_sorting}"
        handle=".handle"
        v-if="cards.length"
      >
        <transition-group
          class="cols"
        >
          <div class="cols__item"
            v-for="(card,i) in getSorteredCards"
            :key="i"
          >
            <div class="handle">
              <v-icon small>mdi-swap-horizontal</v-icon>
            </div>
            <Card :card="card" :boardID="id" :tasks="tasks"/>
          </div>
        </transition-group>
      </draggable>
      <div class="cols">
        <div class="cols__item  cols__item--creating">
          <CardCreateForm :boardID="id" @cardsUpdate="loadCards();"/>
        </div>
      </div>
    </div>

    <Actions :boardID="id"/>

    <!-- <pre 
      :style="{
        'position' : 'fixed',
        'z-index' : '999999999',
        'background-color' : 'rgba(255,255,255,.9)',
        'top' : '50%',
        'overflow' : 'auto',
        'left' : '16px',
        'right' : '16px',
        'bottom' : '16px',
        'padding' : '10px',
        'border' : '5px solid #999',
      }"
      >{{tasks}}</pre> -->
  </div>
</template>
<script>
  import {eventEmitter} from '../../main'
  import { dragscroll } from 'vue-dragscroll' // vue-dragscroll | https://www.npmjs.com/package/vue-dragscroll
  import CardCreateForm from './CardCreateForm.vue'
  import Card from './Card.vue'
  import Actions from './Actions.vue'
  import draggable from 'vuedraggable'

  export default {
    props: ['id'],
    data () {
      return {
        cards : [],
        tasks : [],
        is_sorting : false,
      }
    },
    created(){
      this.setTitle();
      // this.loadCards();
      // this.loadTasks();

      this.getSorteredCards;

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
        this.axios_req('getBoardTitle', {
          data : {
            'boardID' : this.id,
          }
        }, (response) => {
          if (response.data.status == 'success') {
            // успешно
            eventEmitter.$emit("change_title", response.data.boardTitle);
            this.loadCards();
          } else if (response.data.status == 'fail') {
            // ошибка
          }
        }, ()=>{
          // finally
        });
      },
      loadCards(){
        this.axios_req('getCards', {
          data : {
            'boardID' : this.id,
          }
        }, (response) => {
          if (response.data.status == 'success') {
            // успешно
            this.cards = response.data.cards;
            if (this.cards && this.cards.length) {
              this.loadTasks();
            }
          } else if (response.data.status == 'fail') {
            // ошибка
          }
        }, ()=>{
          // finally
        });
      },
      loadTasks(){
        this.axios_req('getTasks', {
          data : {
            'boardID' : this.id,
          }
        }, (response) => {
          if (response.data.status == 'success') {
            // успешно
            this.tasks = response.data.tasks;
          } else if (response.data.status == 'fail') {
            // ошибка
          }
        }, ()=>{
          // finally
        });
      },

      cardsSorted(){
        // eslint-disable-next-line
        // console.table(this.cards);
        for(let i = 0, len = this.cards.length; i < len; i++){
          this.cards[i].order = i+1;
        }
        
        let new_positions = [];
        for(let i = 0, len = this.cards.length; i < len; i++){
          new_positions.push({
            cardID : this.cards[i].id,
            order : this.cards[i].order,
          });
        }

        this.is_sorting = true
        this.axios_req('sortCards', {
          data : {
            'boardID' : this.id,
            'new_positions' : new_positions,
          }
        }, (response) => {
          if (response.data.status == 'success') {
            // успешно
            
          } else if (response.data.status == 'fail') {
            // ошибка
          }
        }, ()=>{
          // finally
          this.is_sorting = false
        });

      },
      
    },
    computed : {
      getSorteredCards(){
        let copied_cards = Object.assign([], this.cards);
        if (copied_cards.length) {
          return copied_cards.sort((a,b) => (a.order - b.order))
        }
        return [];
      }
    },
    directives: {
      dragscroll
    },
    components: {
      CardCreateForm,
      Card,
      Actions,
      draggable,
    }
  }
</script>
<style>

.cols-wrapper {
  display: flex;
  align-items: flex-start;
  min-height: 100%;
}

.cols {
  display: flex;
  align-items: flex-start;
  padding: 16px 0;
  padding-top: 32px;
  /*margin: 0 16px;*/
}
.cols-wrapper:after {
  content: "";
  width: 16px;
  max-width: 16px;
  min-width: 16px;
  height: 1px;
}

.cols.is-sorting {
  pointer-events: none;
  opacity: .5;
  filter: grayscale(1);
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

  position: relative;
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
.handle {
  position: absolute;
  bottom: 100%;
  right: 0;
  cursor: pointer;
  margin-bottom: 3px;
  opacity: 0;
  transition: .2s;
}

.cols__item:hover .handle {
  opacity: 1;
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