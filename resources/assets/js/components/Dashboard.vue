<template>
  <div>
    <h1>Dashboard</h1>
    <pre>{{ this.user.profile }}</pre>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: this.$parent.user
    }
  }
}
</script>

