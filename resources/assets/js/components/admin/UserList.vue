<template>
  <div>
    <h2>Users</h2>
    <table class="table is-striped">
      <tbody>
        <tr v-for="(user, index) in users" :key="user.id">
          <td>{{ user.id }}</td>
          <td>{{ user.lastName }}</td>
          <td>{{ user.firstName }}</td>
          <td>{{ user.email }}</td>
          <td>{{ user.phone}}</td>
          <td>{{ user.soberDate}}</td>
          <td>edit delete</td>
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
    deleteItem(id)
    {
      let uri = `${this.$config.API_URL}/users/${id}`
      this.axios.delete(uri).then(response => {
        this.items.splice(id, 1)
      })
    }
  }
}
</script>
