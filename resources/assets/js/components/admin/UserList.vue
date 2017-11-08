<template>
  <div>
    <h2 class="pull-left">Users</h2><h2 class=" pull-right"><router-link class="btn btn-sm btn-default" to="/admin/users/create">New</router-link></h2>
    <table class="table is-striped">
      <tbody>
        <tr v-for="(user, index) in users" :key="user.id">
          <td class="hidden-xs">{{ user.last_name }}</td>
          <td class="hidden-xs">{{ user.first_name }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.phone}}</td>
          <td class="hidden-xs">{{ user.dob}}</td>
          <td><button class="btn btn-sm btn-primary" v-on:click="editItem(user.id)">Edit</button> <button class="btn btn-sm btn-danger" v-on:click="deleteItem(user.id)">Delete</button></td>
        </tr>
      </tbody>
      <thead>
        <tr>
          <th class="hidden-xs">last name</th>
          <th class="hidden-xs">first name</th>
          <th>email</th>
          <th>phone</th>
          <th class="hidden-xs">DOB</th>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th class="hidden-xs">last name</th>
          <th class="hidden-xs">first name</th>
          <th>email</th>
          <th>phone</th>
          <th class="hidden-xs">DOB</th>
          <th>&nbsp;</th>
        </tr>
      </tfoot>
    </table>    
  </div>
</template>
<script>
export default {
  data() {
    return {
      users: []
    }
  },
  mounted: function() {
    this.$nextTick(function(){
      this.fetchItems()
    })
  },
  methods: {
    fetchItems()
    {
      let uri = this.$config.API_URL + '/users';
      window.axios.get(uri).then(
        response => {
          this.users = response.data.data
        },
        response => {
          console.log(response)
        }
      ).catch(err => {
        console.error(err)
      })
    },
    editItem(id) {
      let user = this.users.find(user => {
        return user.id == id
      })
      
      this.$router.push({path: `/admin/users/${id}`});
    },
    deleteItem(id)
    {
      let uri = `${this.$config.API_URL}/users/${id}`
      let index = this.users.findIndex(element => {
        return element.id == id
      })
      window.axios.delete(uri).then(response => {
        this.$notify({
          type: 'success',
          title: 'User Deleted'
        })
        this.users.splice(index, 1)
      })
    }
  }
}
</script>
