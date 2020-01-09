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
            <v-form ref="form" lazy-validation>
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
            </v-form>
          </v-card-text>
          <v-card-actions>
            <div class="flex-grow-1"></div>
            <v-btn 
              text
              color="orange"
              @click="onSubmit"
              :disabled="!valid()"
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
  data () {
    return {
      login: '',
      password: '',
      // valid: false,
      loginRules : [
        v => !!v || 'Введите логин',
      ],
      passwordRules : [
        v => !!v || 'Введите пароль',
      ],
    }
  },
  methods : {
    onSubmit () {
      // if (this.$refs.form.validate()) {
      if (this.valid()) {
        alert(1)
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
    eventEmitter.$emit("change_title", 'Войти');
  }
}
</script>

<style>
  .container.fill-height > .row {
    max-width: none;
  }
</style>