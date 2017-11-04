<template>
<div>
  <h2>Edit User</h2>
  <form id="user-edit">
    <div class="form-group">
      <label for="firstName">First Name</label>
      <input name="firstName" class="form-control" type="text" v-model="user.first_name" placeholder="First name..." />
    </div>
    <div class="form-group">
      <label for="lastName">Last Name</label>
      <input name="lastName" class="form-control" type="text" v-model="user.last_name" placeholder="Last name..." />
    </div>
    <div class="form-group">
      <label for="email">Email</label>
      <input name="email" class="form-control" type="email" v-model="user.email" placeholder="Email..." />
    </div>
    <div class="form-group">
      <label for="phone">Mobile Phone</label>
      <input name="phone" class="form-control" type="number" v-model="user.phone" placeholder="Mobile Phone..." />
    </div>
    <div>
      <button class="btn btn-sm btn-default" v-on:click="saveUser()" type="button">Save</button>
    </div>
  </form>
</div>
</template>

<script>
export default {
  data() {
    return {
      user: {
        id: null,
        first_name: null,
        last_name: null,
        email: null,
        phone: null,
        dob: null
      }
    }
  },
  methods: {
    fetchUser: function(id) {
      let uri = this.$config.API_URL + `/users/${id}`;
      window.axios.get(uri).then(
        response => {
          this.user = response.data
        },
        response => {
          console.log(response)
        }
      ).catch(err => {
        console.error(err)
      })
    },
    saveUser: function(event) {
      let id = this.user.id
      let uri = this.$config.API_URL + `/users/${id}`;
      window.axios.patch(uri, this.user).then(
        response => {
          console.log('user updated')
        },
        response => {

        }
      )
    }
  },
  mounted: function() {
    this.$nextTick(function(){
      this.fetchUser(this.$route.params.id)
    })
  }
}
</script>