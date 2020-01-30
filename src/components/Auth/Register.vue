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
            <v-toolbar-title>Регистрация на сайте</v-toolbar-title>
            
          </v-toolbar>
          <v-card-text>
            <v-form ref="form" lazy-validation>
              <v-text-field
                prepend-icon="mdi-account"
                name="login"
                label="Логин"
                :rules="loginRules"
                v-model="login"
              ></v-text-field>

              <v-text-field
                prepend-icon="mdi-account"
                name="fullName"
                label="Имя и фамилия"
                :rules="fullNameRules"
                v-model="fullName"
              ></v-text-field>

              <v-text-field
                prepend-icon="mdi-email"
                name="email"
                label="E-mail адрес"
                :rules="emailRules"
                v-model="email"
              ></v-text-field>

              <v-text-field
                prepend-icon="mdi-lock"
                name="password"
                label="Пароль"
                type="password"
                :rules="passwordRules"
                v-model="password"
              ></v-text-field>

              <v-text-field
                prepend-icon="---mdi-lock"
                name="passwordConfirm"
                label="Повторите пароль"
                type="password"
                :rules="passwordConfirmRules"
                v-model="passwordConfirm"
                @keydown.enter="onSubmit"
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
            >Зарегистрироваться</v-btn>
          </v-card-actions>
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
      login: '',
      fullName: '',
      email: '',
      password: '',
      passwordConfirm: '',
      messages: [],
      // valid: false,
      loginRules : [
        v => !!v || 'Логин не может быть пустым!',
        v => v.length >= 4 || 'Логин должен быть не меньше 4 символов!',
      ],
      fullNameRules : [
        v => !!v || 'Поле не может быть пустым!',
      ],
      emailRules : [
        v => !!v || 'E-mail не может быть пустым!',
        v => /.+@.+/.test(v) || 'E-mail адрес не корректный!',
      ],
      passwordRules : [
        v => !!v || 'Пароль не может быть пустым!',
        v => v.length >= 6 || 'Пароль должен быть не меньше 6 символов!',
      ],
      passwordConfirmRules : [
        v => !!v || 'Пароль не может быть пустым!',
        v => v.length >= 6 || 'Пароль должен быть не меньше 6 символов!',
        v => (v == this.password) || 'Пароли должны совпадать!',
      ],
    }
  },
  methods : {
    onSubmit () {
      // if (this.$refs.form.validate()) {
      if (this.valid()) {
        this.loading = true;
        this.axios_req('register', {
          data : {
            'login' : this.login.trim(),
            'fullName' : this.fullName.trim(),
            'email' : this.email.trim(),
            'password' : this.password.trim(),
          }
        }, (response) => {
          this.messages = [];
          if (response.data.status == 'success') {
            // успешно
            this.user_authed = true;
            this.$router.push('/');

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
  computed : {
  },
  created(){
    this.redirect('/dashboard', true);

    eventEmitter.$emit("change_title", 'Регистрация');
    
    // SERVER_API;
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