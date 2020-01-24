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
      this.axios_req('logout', {}, (response) => {
        if (response.data.status == 'success') {
          // успешно
          // eventEmitter.$emit('user_logout');
          this.user_authed = false;
          this.$router.push('/login');
        } else {
          // eslint-disable-next-line
          console.log(response);
        }
      }, ()=>{
        // finally
        this.loading = false
      });
    }
  },
  created(){
    this.redirect('/login', false);

    eventEmitter.$emit("change_title", 'Профиль');
    this.loading = true;

    this.axios_req('getProfileData', {}, (response) => {
      if (response.data.status == 'success') {
          // успешно
          this.login = response.data.user.login;
          this.fullName = response.data.user.fullName;
          this.email = response.data.user.email;
        } else {
          // eslint-disable-next-line
          console.log(response);
        }
    }, ()=>{
      // finally
      this.loading = false
    });
    
  }
}
</script>

<style>

</style>