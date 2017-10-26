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
        <input type="text" name="firstName" v-model="user.profile.firstName" class="form-control" />
      </div>
      <div class="form-group">
        <label for="lastName">Last Name</label>
        <input type="text" name="lastName"  v-model="user.profile.lastName" class="form-control" />
      </div>
      <div class="form-group">
        <label for="phone">Mobile Phone</label>
        <input type="number" name="phone" v-model="user.profile.phone" class="form-control" />
      </div>
      <div class="form-group">
        <label for="birthDate">Birth Date</label>
        <input type="date" name="birthDate" v-model="user.profile.birthDate" class="form-control" />
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
    }
  },
  mounted: function() {
    console.log("Create a new user...")
  }
}  
</script>
