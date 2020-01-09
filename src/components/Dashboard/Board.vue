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
    >
      <CardTask/>
    </div>

    <div class="cols__item  cols__item--creating"
      @click.self="blurInputs()"
    >
      <CardCreateForm/>
    </div>

    <TaskSingleInfo :taskID="2"/>
  </div>
</template>
<script>
  import {eventEmitter} from '../../main'
  import { dragscroll } from 'vue-dragscroll' // vue-dragscroll | https://www.npmjs.com/package/vue-dragscroll
  import CardCreateForm from './CardCreateForm.vue'
  import CardTask from './CardTask.vue'
  import TaskSingleInfo from './TaskSingleInfo.vue'

  export default {
    props: ['id'],
    data () {
      return {
        
      }
    },
    created(){
      eventEmitter.$emit("change_title", 'Доска номер ' + this.id);
    },
    methods: {
      blurInputs(){

        document.querySelectorAll("input").forEach(function(input) {
          // eslint-disable-next-line
          // console.log(i, x);
          input.blur();
        })

      }
    },
    directives: {
      'dragscroll': dragscroll
    },
    components: {
      CardCreateForm,
      CardTask,
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