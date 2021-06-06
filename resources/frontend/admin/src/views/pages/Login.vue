<template>
  <v-container
    id="login"
    class="fill-height"
    tag="section"
  >
    <v-row
      justify="center"
      align="center"
    >
      <v-slide-y-transition appear>
        <base-material-card
          light
          background-color="#fff"
          color="success"
          max-width="400"
          width="100%"
          icon="mdi-account-check"
          title="Авторизация"
          class="pt-4"
        >
          <v-form
            ref="form"
            class="px-3"
            lazy-validation
            @submit.prevent="auth"
          >
            <v-text-field
              id="login"
              v-model="userLogin"
              :rules="loginRules"
              label="Ваш логин"
            />
            <v-text-field
              id="password"
              v-model="password"
              :type="showPassword ? 'text' : 'password'"
              :rules="passwordRules"
              label="Пароль"
              :append-icon="showPassword ? 'mdi-eye-off' : 'mdi-eye'"
              @click:append="() => (showPassword = !showPassword)"
            />
            <!--            <v-checkbox-->
            <!--              v-model="rememberMe"-->
            <!--              label="Запомнить меня"-->
            <!--            />-->
            <v-card-actions>
              <v-spacer />
              <v-btn
                :loading="loading"
                color="success"
                default
                type="submit"
                x-large
              >
                Войти
              </v-btn>
              <v-spacer />
            </v-card-actions>
            <base-material-alert
              v-show="error"
              align="center"
              :color="color"
            >
              {{ message }}
            </base-material-alert>
          </v-form>
        </base-material-card>
      </v-slide-y-transition>
    </v-row>
  </v-container>
</template>

<script>
  import { mapActions } from 'vuex'

  export default {
    name: 'PagesLogin',

    data () {
      return {
        userLogin: '',
        password: '',
        showPassword: false,
        rememberMe: false,
        loginRules: [v => !!v || 'Пожалуйста, введите значение'],
        passwordRules: [v => !!v || 'Пожалуйста, введите значение'],
        scope: 'login-form',
        loading: false,
        message: '',
        error: false,
      }
    },
    computed: {
      color () {
        return this.error ? 'error' : 'success'
      },
    },
    methods: {
      ...mapActions('user', ['login']),
      auth () {
        this.error = false
        const data = {
          login: this.userLogin,
          password: this.password,
          remember: true,
        }
        if (this.$refs.form.validate()) {
          this.loading = true

          this.login(data)
            .then(() => {
              this.loading = false
              this.$router.push({ name: 'home' })
            })
            .catch(({ response }) => {
              console.error(response)
              this.loading = false
              this.error = true
              this.message = response.data.error
            })
        }
      },
    },
  }
</script>
