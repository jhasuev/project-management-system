<template>
  <v-container>
    <v-row
      align="center"
      justify="center"
    >
      <v-col
        sm="8"
        md="5"
        lg="4"
      >
        <v-card class="elevation-1">
          <v-toolbar
            color="orange"
            dark
            flat
          >
            <v-toolbar-title>Мой профиль</v-toolbar-title>
            
          </v-toolbar>
          <v-card-text>
            <v-simple-table>
              <tbody>
                <tr>
                  <td>Логин</td>
                  <td>
                    {{getUser.login}}
                  </td>
                </tr>
                <tr>
                  <td>Имя и фамилия</td>
                  <td>
                    {{getUser.fullName}}
                  </td>
                </tr>
                <tr>
                  <td>E-mail адрес</td>
                  <td>
                    {{getUser.email}}
                  </td>
                </tr>
              </tbody>
            </v-simple-table>
            <pre>{{getUser}}</pre>
            <div class="text-center pt-2">
              <!-- <router-link :to="'/logout'">Выйти из аккаунта</router-link> -->
              <a @click="logout()">Выйти из аккаунта</a>
            </div>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import {eventEmitter} from '../../main'
import {SERVER_API} from '../../main'
import axios from 'axios'
export default {
  data () {
    return {}
  },
  methods : {
    logout(){
      // eslint-disable-next-line
      // console.log(111);
      axios.defaults.withCredentials = true;
      axios
        .get(SERVER_API + '?cmd=logout')
        .then(response => {
          // eslint-disable-next-line
          // console.log(response.data);
          if (response.data.status == 'success') {
            // успешно
            this.$router.push('/login');
          } else {
            // eslint-disable-next-line
            console.log(response);
          }
        })
        .catch(error => {
          // eslint-disable-next-line
          console.log(error);
        })
        .finally(() => (this.loading = false));
    }
  },
  computed : {
    getUser(){
      // this.$store.commit('loginEdit', 'new_login');
      return this.$store.getters.user;
    },
  },
  created(){
    eventEmitter.$emit("change_title", 'Профиль');
  }
}
</script>

<style>

</style>