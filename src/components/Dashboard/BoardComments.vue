<template>
  <div>
    <v-card
      class="mx-auto"
      v-show="is_component_loaded"
      :loading="loading"
    >
      <v-toolbar
        dark
      >
        <v-toolbar-title>Комментарии:</v-toolbar-title>

        <v-spacer></v-spacer>

      </v-toolbar>

      <v-list>
        
        <v-list-item>
          <v-list-item-content>
            <BoardCommentsForm :taskID="taskID" @loadComments="loadComments()"/>
          </v-list-item-content>

        </v-list-item>

        <template v-for="(comment, i) in comments">
          <v-divider :key="i + '_1'"></v-divider>
          <v-list-item :key="i + '_2'">
            <v-list-item-content>
              <v-list-item-title>{{comment.fullName}}</v-list-item-title>
              <v-list-item-subtitle class="pre-line  pt-1">{{comment.comment}}</v-list-item-subtitle>
              <v-list-item-subtitle class="d-flex pt-2">
                <v-spacer></v-spacer>
                <span>{{getStringifyDate(comment.time * 1000)}}</span>
              </v-list-item-subtitle>
            </v-list-item-content>
          </v-list-item>
          
        </template>

      </v-list>
    </v-card>
    <div
      class="loaders"
      v-if="!is_component_loaded"
    >
      <v-skeleton-loader
        class="mx-auto"
        type="heading"
      ></v-skeleton-loader>

      <v-skeleton-loader
        class="mx-auto"
        type="list-item"
        style="margin-left: -16px;"
      ></v-skeleton-loader>

      <v-skeleton-loader
        class="mx-auto"
        style="margin-left: -16px;"
        type="list-item-two-line"
      ></v-skeleton-loader>
      <v-skeleton-loader
        class="mx-auto"
        style="margin-left: -16px;"
        type="list-item-two-line"
      ></v-skeleton-loader>
      <v-skeleton-loader
        class="mx-auto"
        style="margin-left: -16px;"
        type="list-item-two-line"
      ></v-skeleton-loader>
    </div>
  </div>
</template>
<script>
  import BoardCommentsForm from './BoardCommentsForm.vue'
  import {SERVER_API} from '../../main'
  import axios from 'axios'
  export default {
    props: ['taskID'],
    data(){
      return {
        loading : false,
        is_component_loaded : false,
        comments : [],
      }
    },
    created(){
      this.is_component_loaded = true;
      this.loadComments();
    },
    methods : {
      loadComments(){
        this.loading = true;

        axios.defaults.withCredentials = true;
        axios
          .get(SERVER_API + '?cmd=loadComments&data=' + encodeURIComponent(JSON.stringify({
              'taskID' : this.taskID,
            })))
          .then(response => {
            // eslint-disable-next-line
            console.log(response.data);
            if (response.data.status == 'success') {
              // успешно
              this.comments = response.data.comments;
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
          .finally(() => {
            this.loading = false
            this.is_component_loaded = true;
          });
      },
      getStringifyDate(ts){
        let date = new Date(ts);
        let str = 'dd.mm.yyyy в hh:mins';

        let dd = ("0" + date.getDate()).slice(-2);
        let mm = ("0" + (date.getMonth() + 1)).slice(-2);
        let yyyy = date.getFullYear();
        let hh = ("0" + date.getHours()).slice(-2);
        let mins = ("0" + date.getMinutes()).slice(-2);


        str = str.replace('dd', dd);
        str = str.replace('mm', mm);
        str = str.replace('yyyy', yyyy);
        str = str.replace('hh', hh);
        str = str.replace('mins', mins);

        return str;
      },
    },
    components: {
      BoardCommentsForm
    }
  }
</script>

<style scoped>
  .pre-line {
    white-space: pre-line;
  }
</style>