import Vue from 'vue';
import Router from 'vue-router';

import Employees from './pages/employees/EmployeesList.vue'
import EmployeeCreate from './pages/employees/EmployeeCreate.vue'
import Projects from './pages/projects/ProjectsList.vue'

Vue.use(Router);

// Define routes
const routes = [
    { path: '/employees', component: Employees },
    { path: '/employees/create', component: EmployeeCreate },
    { path: '/projects', component: Projects },
];

const router = new Router({
  mode: 'history',
  routes,
});

export default router;