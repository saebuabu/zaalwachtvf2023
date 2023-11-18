import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw } from 'vue-router';

const routes: Array<RouteRecordRaw> = [
  {
    path: '',
    redirect: '/Smoelenboek'
  },
  {
    path: '/Smoelenboek',
    component: () => import ('../views/Smoelenboek.vue')
  },
  {
    path: '/Kalender',
    component: () => import ('../views/Kalender.vue')
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router
