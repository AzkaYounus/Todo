import { createRouter, createWebHistory } from 'vue-router';
import Home from './views/Home.vue';
import PlanTask from './views/PlanTask.vue';
import Test from './views/Test.vue';
import Login from './views/Login.vue';
import Signup from './views/Signup.vue'
import {token} from './store/auth';

const routes = [
  { path: '/', component: Home },
  { path: '/plantask', component: PlanTask
    , meta: { requiresAuth: true } 
  },
  { path: '/test', component: Test },
  {path:'/login',component:Login},
  {path:'/signup',component:Signup},
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});



router.beforeEach((to, from, next) => {
  const isLoggedIn = token?.value !== null; 

  if (to.meta.requiresAuth && !isLoggedIn) {
    next('/login'); 
  } else {
    next(); 
  }
});
export default router;