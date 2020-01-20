<template>
  <div>
    <v-card
      class="mx-auto"
      v-if="is_component_loaded"
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
            <BoardCommentsForm/>
          </v-list-item-content>

        </v-list-item>
        
        <v-divider></v-divider>

        <v-list-item>
          <v-list-item-content>
            <v-list-item-title>Vasya Pupkin</v-list-item-title>
            <v-list-item-subtitle>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore voluptate quidem autem delectus, quasi dignissimos minus ipsum harum saepe aut.</v-list-item-subtitle>
            <v-list-item-subtitle class="d-flex pt-2">
              <v-spacer></v-spacer>
              <span>02:01:2020 17:55</span>
            </v-list-item-subtitle>
          </v-list-item-content>

        </v-list-item>
        <v-divider></v-divider>
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title>Vasya Pupkin</v-list-item-title>
            <v-list-item-subtitle>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore voluptate quidem autem delectus, quasi dignissimos minus ipsum harum saepe aut.</v-list-item-subtitle>
            <v-list-item-subtitle class="d-flex pt-2">
              <v-spacer></v-spacer>
              <span>02:01:2020 17:55</span>
            </v-list-item-subtitle>
          </v-list-item-content>

        </v-list-item>
        <v-divider></v-divider>
        <v-list-item>
          <v-list-item-content>
            <v-list-item-title>Vasya Pupkin</v-list-item-title>
            <v-list-item-subtitle>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Labore voluptate quidem autem delectus, quasi dignissimos minus ipsum harum saepe aut.</v-list-item-subtitle>
            <v-list-item-subtitle class="d-flex pt-2">
              <v-spacer></v-spacer>
              <span>02:01:2020 17:55</span>
            </v-list-item-subtitle>
          </v-list-item-content>

        </v-list-item>

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
    data(){
      return {
        is_component_loaded : false,
      }
    },
    created(){
      setTimeout(()=>{
        this.is_component_loaded = true;
      }, 2000);
    },
    components: {
      BoardCommentsForm
    }
  }
</script>