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
            <v-toolbar-title>Вход на сайт</v-toolbar-title>
            
          </v-toolbar>
          <v-card-text>
            <v-form ref="form" lazy-validation @submit.prevent="onSubmit">
              <v-text-field
                prepend-icon="mdi-account"
                name="login"
                label="Логин"
                :rules="loginRules"
                v-model="login"
              ></v-text-field>

              <v-text-field
                prepend-icon="mdi-lock"
                name="password"
                label="Пароль"
                type="password"
                :rules="passwordRules"
                v-model="password"
              ></v-text-field>
              <div class="message" v-if="messages.length">
                <div class="message__item" v-for="(msg, i) in messages" :key="i">{{msg}}</div>
              </div>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <div class="flex-grow-1"></div>
            <v-btn 
              text
              color="orange"
              @click="onSubmit"
              :disabled="!valid()"
              :loading="loading"
            >Войти</v-btn>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import {eventEmitter} from '../../main'
export default {
  props: ['use_in_main'],
  data () {
    return {
      login: '',
      password: '',
      // valid: false,
      loginRules : [
        v => !!v || 'Логин не может быть пустым!',
        v => v.length >= 4 || 'Логин должен быть не меньше 4 символов!',
      ],
      passwordRules : [
        v => !!v || 'Пароль не может быть пустым!',
        v => v.length >= 6 || 'Пароль должен быть не меньше 6 символов!',
      ],

      loading : false,
      messages: [],
    }
  },
  methods : {
    onSubmit () {
      // if (this.$refs.form.validate()) {
      if (this.valid()) {
        this.loading = true;
        this.axios_req('login', {
          data : {
            'login' : this.login.trim(),
            'password' : this.password.trim(),
          }
        }, (response) => {
          this.messages = [];
          if (response.data.status == 'success') {
            // успешно
            this.user_authed = true;
            this.$router.push('/dashboard');
          } else if (response.data.status == 'fail') {
            // ошибка
            this.messages = response.data.messages;
          }
        }, ()=>{
          // finally
          this.loading = false
        });
      }
    },
    valid () {
      if (this.$refs.form)
        return this.$refs.form.validate()
    }
  },
  computed : {},
  created(){

    if (!this.use_in_main) {
      this.redirect('/dashboard', true);
      eventEmitter.$emit("change_title", 'Войти');
    }
  }
}
</script>

<style>
  /*.container.fill-height > .row {
    max-width: none;
  }*/
  .message {
    padding-left: 35px;
  }
  .message__item {
    color: #f33;
    display: flex;
  }
  .message__item:before {
    content: "–";
    margin-right: 5px;
  }
</style>