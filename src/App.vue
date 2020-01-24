<template>
  <v-app id="inspire">
    <v-app-bar
      app
      clipped-right
      color="orange"
      dark
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" class="d-block  d-sm-none"/>
      <v-toolbar-title class="page-title pl-0" ref='doc_title'>
          {{page_title}}
      </v-toolbar-title>

      <v-spacer></v-spacer>


      <v-toolbar-items 
        class="d-none d-sm-flex"
      >
        <v-btn text :to="menu.url" v-for="(menu, index) in ((user_authed)?menu_links_authed:menu_links_non_authed)" :key="index">
          <v-icon class="mr-3">{{menu.icon}}</v-icon>
          {{menu.title}}
        </v-btn>
      </v-toolbar-items>
    </v-app-bar>

    <v-navigation-drawer
      v-model="drawer"
      app
      temporary
    >
      <v-list dense>
        <v-list-item :to="menu.url" v-for="(menu, index) in ((user_authed)?menu_links_authed:menu_links_non_authed)" :key="index">
          <v-list-item-action>
            <v-icon>{{menu.icon}}</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>{{menu.title}}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-content>
      <router-view></router-view>
    </v-content>

    <v-footer
      app
      color="orange"
      class="white--text  text-center"
    >
      <v-spacer />
      <span>
        user_authed: {{user_authed}}
        <br>
        &copy; 2019
      </span>
      <v-spacer />
    </v-footer>
  </v-app>
</template>

<script>
  import {eventEmitter} from './main'
  import {DOC_TITLE} from './main'
  export default {
    props: {
      source: String,
    },
    data: () => ({
      drawer: null,
      page_title : DOC_TITLE,
      menu_links_non_authed : [
        {
          'url'   : '/',
          'icon'  : 'mdi-home',
          'title' : 'Главная'
        },
        {
          'url'   : '/login',
          'icon'  : 'mdi-account',
          'title' : 'Вход'
        },
        {
          'url'   : '/register',
          'icon'  : 'mdi-account-multiple-plus',
          'title' : 'Регистрация'
        },
      ],
      menu_links_authed : [
        {
          'url'   : '/dashboard',
          'icon'  : 'mdi-home',
          'title' : 'Все доски'
        },
        {
          'url'   : '/profile',
          'icon'  : 'mdi-account',
          'title' : 'Профиль'
        },
      ],
    }),
    created(){
      eventEmitter.$on('change_title', (page_title) => {
        this.page_title = page_title;
        document.title = page_title + ' | ' + DOC_TITLE;
      });

      this.setAuth();
      // eslint-disable-next-line
      console.log('App.vue:created()');

    }
  }
</script>