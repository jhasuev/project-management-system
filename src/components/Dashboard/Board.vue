<!-- https://github.com/SortableJS/Vue.Draggable -->
<template>
  <div class="main">
    <v-card :loading="is_sorting || page_loading" style="height: 4px; overflow: hidden; margin-bottom: -4px;" class="elevation-0"></v-card>
    
    <div
      v-if="cards.length"
      class="overflow-auto  fit-on-parent"
    >
      <draggable
        @change="cardsSorted"
        v-model="cards"
        :class="{'is-sorting' : is_sorting}"
        handle=".handle"
        class="cols"
      >
        <div class="cols__item"
          v-for="(card,i) in getSorteredCards"
          :key="i"
          :draggable="true"
        >
          <div class="handle">
            <v-icon small>mdi-swap-horizontal</v-icon>
          </div>
          
          <div class="remove-card-wrapper">
            <v-icon small @click="removePopup = !removePopup; tmp.cardID = card.id; tmp.localCardID = i">mdi-delete</v-icon>
          </div>

          <Card :card="card" :boardID="id" :tasks="tasks"/>
        </div>
        <div class="cols__item  cols__item--creating">
          <CardCreateForm :boardID="id" @cardsUpdate="loadCards();"/>
        </div>
      </draggable>
    </div>
    <div class="cols" v-else>
      <div class="cols__item  cols__item--creating">
        <CardCreateForm :boardID="id" @cardsUpdate="loadCards();"/>
      </div>
    </div>
    

    <Actions :boardID="id"/>

    <v-dialog
      v-model="removePopup"
      width="500"
    >
      <v-card>
        <v-card-title
          class="headline grey lighten-2 justify-center"
          primary-title
        >
          Удалить карточку?
        </v-card-title>

        <v-card-text class="pt-3" v-if="typeof tmp.localCardID == 'number'">
          Карточка <b>"{{cards[tmp.localCardID].title}}"</b> будет удалена навсегда.
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions class="justify-center">
          <v-btn
            small
            color="warning"
            class="mx-1"
            @click="removeCard(tmp.cardID, tmp.localCardID);"
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

  </div>
</template>
<script>
  import {eventEmitter} from '../../main'
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
        page_loading : true,
        removePopup : false,
        removing_loading : false,
        tmp : {}
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
          this.page_loading = false;
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
      removeCard(cardID, localID){
        this.removing_loading = true;
        this.axios_req('removeCard', {
          data : {
            'boardID' : this.id,
            'cardID' : cardID,
          }
        }, (response) => {
          if (response.data.status == 'success') {
              // успешно
              this.cards.splice(localID, 1);
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
    computed : {
      getSorteredCards(){
        let copied_cards = Object.assign([], this.cards);
        if (copied_cards.length) {
          return copied_cards.sort((a,b) => (a.order - b.order))
        }
        return [];
      }
    },
    components: {
      CardCreateForm,
      Card,
      Actions,
      draggable,
    },

  }
</script>
<style>

.main {
  height: 100%;
}

.fit-on-parent {
  height: 100%;
  width: 100%;

  position: absolute;
  left: 0;
  top: 0;
}

.cols {
  display: flex;
  align-items: flex-start;
}

.cols {
  display: flex;
  align-items: flex-start;
  padding: 16px 0;
  padding-top: 32px;
  /*margin: 0 16px;*/
}
.cols:after {
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
.handle,
.remove-card-wrapper {
  position: absolute;
  bottom: 100%;
  cursor: pointer;
  margin-bottom: 3px;
  opacity: 0;
  transition: .2s;
}
.handle {
  right: 0;
}
.remove-card-wrapper {
  left: 0;
}

.cols__item:hover .handle,
.cols__item:hover .remove-card-wrapper {
  opacity: 1;
}

/*******************************/
.grab-bing {
  overflow: auto;

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