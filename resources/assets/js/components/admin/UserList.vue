<template>
  <div>
    <h2 class="pull-left">Users</h2><h2 class=" pull-right"><router-link class="btn btn-sm btn-default" to="/admin/users/create">New</router-link></h2>
    <table class="table is-striped">
      <tbody>
        <tr v-for="(user, index) in users" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.lastName }}</td>
          <td>{{ user.firstName }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.phone}}</td>
          <td>{{ user.soberDate}}</td>
          <td><button class="btn btn-sm btn-primary" v-on:click="editItem(user.id)">Edit</button> <button class="btn btn-sm btn-danger" v-on:click="deleteItem(user.id)">Delete</button></td>
        </tr>
      </tbody>
      <thead>
        <tr>
          <th>id</th>
          <th>last name</th>
          <th>first name</th>
          <th>email</th>
          <th>phone</th>
          <th>sobriety date</th>
          <th>&nbsp;</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th>id</th>
          <th>last name</th>
          <th>first name</th>
          <th>email</th>
          <th>phone</th>
          <th>sobriety date</th>
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
      console.log(`Editing user: ${id}`);
    },
    deleteItem(id)
    {
      console.log(`Deleting user: ${id}`);
      let uri = `${this.$config.API_URL}/users/${id}`
      window.axios.delete(uri).then(response => {
        this.items.splice(id, 1)
      })
    }
  }
}
</script>
