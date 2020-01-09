<template>
  <div>
    <BoardList/>
  </div>
</template>

<script>
import BoardList from './BoardList.vue'
import {eventEmitter} from '../../main'

export default {
  data () {
    return {
      
    }
  },
  methods : {
  },
  components: {
    BoardList
  },
  created(){
    eventEmitter.$emit("change_title", 'Все доски');
  }
}
</script>
