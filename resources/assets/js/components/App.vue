<template>
  <div class="container">
    <nav class="navbar navbar-default">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">5TG</a>
      </div>
      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
            <li><router-link to="/">Home</router-link></li>
            <li v-show="this.user.authenticated"><router-link to="/admin">Admin</router-link></li>
          </ul>
          <ul class="nav navbar-nav navbar-right" v-show="!this.user.authenticated">
            <li><router-link to="/register">Register</router-link></li>
          </ul>
          <ul class="nav navbar-nav navbar-right" v-show="!this.user.authenticated">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Login <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li class="padded">
                    <form v-on:submit="login" v-if="!success">
                      <div class="form-group">
                        <label for="email">Email</label><br />
                        <input name="email" v-model="email" type="email" required />
                      </div>
                      <div class="form-group">
                        <label for="password">Password</label><br />
                        <input name="password" v-model="password" type="password" required />
                      </div>
                      <button class="btn btn-sm btn-default" type="submit">Login</button>
                    </form>
                </li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right" v-show="this.user.profile != null">
            <li><router-link to="/dashboard">Welcome, {{ this.user.profile.email }}</router-link></li>
          </ul>
      </div>
    </nav>

    <transition name="fade">
      <router-view></router-view>
    </transition>
  </div>
</template>

<style>
  .fade-enter-active, .fade-leave-active {
    transition: opacity .5s
  }
  .fade-enter, .fade-leave-active {
    opacity: 0
  }
  .padded {
    padding-left: 10px;
    padding-right: 10px;
  }
</style>

<script>
  export default {
    data() {
      return {
        success: false,
        error: false,
        user: {
          profile: {
            first_name: "",
            email: ""
          },
          authenticated: false
        },
        email: null,
        password: null
      }
    },
    methods: {
      login(event) {
        event.preventDefault()
        let uri = `${this.$config.API_URL}/auth/login`

        window.axios.post(uri, {
          email: this.email,
          password: this.password
        }).then(
          response => {
            this.error = false
            this.success = true
            // Save the token to local storage where it can be used to make future requests
            window.localStorage.setItem('id_token', response.data.meta.token)
            window.axios.defaults.headers.common['Authorization'] = `Bearer ${window.localStorage.getItem('id_token')}`
            // Authenticate the user
            this.user.authenticated = true
            // Save the user's profile
            this.user.profile = response.data.data.user

            this.$router.push({
              name: 'Dashboard'
            })
          }, 
          response => {
            this.error = true
          }
        )
      },
      logout() {
        localStorage.removeItem('id_token')
        this.user.authenticated = false
        this.user.profile = null

        this.$router.push({
          name: 'home'
        })
      }
    },
    mounted: function() {
      let uri = `http://localhost:8080/api/user`

      window.axios.get(uri).then(
        response => {
          this.user.authenticated = true
          this.user.profile = response.data.data
        },
        response => {
          this.user.authenticated = false
          this.user.profile = null
        }
      )
    }
  }
</script>