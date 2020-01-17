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
        <v-card class="elevation-1" :loading="loading">
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
                    {{login}}
                  </td>
                </tr>
                <tr>
                  <td>Имя и фамилия</td>
                  <td>
                    {{fullName}}
                  </td>
                </tr>
                <tr>
                  <td>E-mail адрес</td>
                  <td>
                    {{email}}
                  </td>
                </tr>
              </tbody>
            </v-simple-table>
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
    return {
      loading : false,
      login : '',
      fullName : '',
      email : '',
    }
  },
  methods : {
    logout(){
      // eslint-disable-next-line
      // console.log(111);
      this.loading = true;
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
    
  },
  created(){
    eventEmitter.$emit("change_title", 'Профиль');
    
    this.loading = true;
    axios.defaults.withCredentials = true;
    axios
      .get(SERVER_API + '?cmd=getProfileData')
      .then(response => {
        // eslint-disable-next-line
        // console.log(response.data);
        if (response.data.status == 'success') {
          // успешно
          // this.$router.push('/login');
          // eslint-disable-next-line

          this.login = response.data.user.login;
          this.fullName = response.data.user.fullName;
          this.email = response.data.user.email;
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
}
</script>

<style>

</style>