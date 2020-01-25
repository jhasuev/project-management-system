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
            <BoardCommentsForm :boardID="boardID" :taskID="taskID" @loadComments="loadComments()"/>
          </v-list-item-content>

        </v-list-item>

        <template v-for="(comment, i) in comments">
          <v-divider :key="i + '_1'"></v-divider>
          <v-list-item :key="i + '_2'">
            <v-list-item-content>
              <v-list-item-title  class="d-flex align-center">
                <v-icon class="mr-2">mdi-account</v-icon>
                {{comment.fullName}}
                (<span class="grey--text">@{{comment.login}}</span>)
              </v-list-item-title>
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

  export default {
    props: ['boardID', 'taskID'],
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

        this.axios_req('loadComments', {
          data : {
            'taskID' : this.taskID,
            'boardID' : this.boardID,
          }
        }, (response) => {
          // eslint-disable-next-line
          console.log(response);
          if (response.data.status == 'success') {
            // успешно
            this.comments = response.data.comments;
          } else if (response.data.status == 'fail') {
            // ошибка
            // eslint-disable-next-line
            console.log(response);
          }
        }, ()=>{
          // finally
          this.loading = false
          this.is_component_loaded = true;
        });
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