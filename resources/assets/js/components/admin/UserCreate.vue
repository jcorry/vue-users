<template>
  <div>
    <h2>New User</h2>
    <form v-on:submit="createUser">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" v-model="user.profile.email" class="form-control" />
      </div>
      <div class="form-group">
        <label for="firstName">First Name</label>
        <input type="text" name="firstName" v-model="user.profile.first_name" class="form-control" />
      </div>
      <div class="form-group">
        <label for="lastName">Last Name</label>
        <input type="text" name="lastName"  v-model="user.profile.last_name" class="form-control" />
      </div>
      <div class="form-group">
        <label for="phone">Mobile Phone</label>
        <input type="number" name="phone" v-model="user.profile.phone" class="form-control" />
      </div>
      <div class="form-group">
        <label for="birthDate">Birth Date</label>
        <input type="date" name="birthDate" v-model="user.profile.dob" class="form-control" />
      </div>
      <button class="btn btn-default" type="submit">Create</button>
    </form>  
  </div>
</template>

<script>
export default {
  data: function() {
    return {
      user: {
        profile: {}
      }
    }
  },
  methods: {
    createUser: function(event) {
      event.preventDefault()
      console.log(this.user.profile)
      let uri = this.$config.API_URL + `/users`;
      window.axios.post(uri, this.user.profile).then(
        response => {
            // Notify user
            this.$notify({
              type: 'success',
              title: "Success!",
              text: `User ${this.user.profile.first_name} ${this.user.profile.last_name} created`,
            })
            // Clear form
            this.user.profile = {};
            this.$router.push({path: `/admin/users`})
        },
        response => {

        }
      ).catch(response => {

      })
    }
  },
  mounted: function() {
    console.log("Create a new user...")
  }
}  
</script>
