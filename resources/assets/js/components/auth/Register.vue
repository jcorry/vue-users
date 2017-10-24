<template>
<div>
  <div class="alert alert-danger" v-if="error && !success">
    <p>There was an error, unable to complete registration.</p>
  </div>
  <div class="alert alert-success" v-if="success">
    <p>Registration completed. You can now sign in.</p>
  </div>
  <form v-on:submit="register" v-if="!success">
    <div class="form-group" v-bind:class="{'has-error': error && response.email}">
      <label for="email">Email</label>
      <input type="email" id="email" class="form-control" v-model="email" required />
      <span class="help-block" v-if="error && response.email">{{ response.email }}</span>
    </div>
    <div class="form-group" v-bind:class="{'has-error': error && response.password}">
      <label for="password">Password</label>
      <input type="password" id="password" class="form-control" v-model="password" required />
      <span class="help-block" v-if="error && response.password">{{ response.password }}</span>
    </div>
    <div class="form-group" v-bind:class="{'has-error': error && response.password}">
      <label for="confirmPassword">Confirm Password</label>
      <input type="password" id="confirmPassword" class="form-control" v-model="confirmPassword" required />
      <span class="help-block" v-if="error && response.password">{{ response.password }}</span>
    </div>
    <button type="submit" class="btn btn-default">Register</button>
  </form>
 </div>
</template>

<<script>
export default {
  data() {
    return {
      email: '',
      password: '',
      confirmPassword: '',
      success: false,
      error: false,
      response: null
    }
  },
  computed: {
    
  },
  methods: {
    register(event) {
      event.preventDefault()
      let uri = `${this.$config.API_URL}/auth/register`

      window.axios.post(uri, {
        email: this.email,
        password: this.password
      }).then(response => {
        this.success = true
        this.response = response.data
      }, response => {
        this.response = {email: "error", password: "error"}
        this.error = true
      })
    }
  }
}
</script>

