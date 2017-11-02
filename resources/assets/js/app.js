
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap')

import App from './components/App.vue'
import Register from './components/auth/Register.vue'
import Dashboard from './components/Dashboard.vue'
import Admin from './components/admin/Admin.vue'
import AdminUsers from './components/admin/UserList.vue'
import AdminUserCreate from './components/admin/UserCreate.vue'
import Home from './components/Home.vue'
import Router from 'vue-router'

const vueConfig = require('vue-config')

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const configs = {
  API_URL: 'http://localhost:8181/api'
}

Vue.use(vueConfig, configs)
Vue.use(Router)

const router = new Router({
  routes: [
    {
      name: 'Home',
      path: '/',
      component: Home

    },
    {
      name: 'Register',
      path: '/register',
      component: Register

    },
    {
      name: 'Dashboard',
      path: '/dashboard',
      component: Dashboard
    },
    {
      name: 'Admin',
      path: '/admin',
      component: Admin,
      meta: {
        requiresAuth: true
      },
      children: [
        {
          path: 'users',
          component: AdminUsers
        },
        {
          path: 'users/create',
          component: AdminUserCreate
        }
      ]
    }
  ]
})

const app = new Vue(
    Vue.util.extend({router},
        App
)
).$mount('#app')
