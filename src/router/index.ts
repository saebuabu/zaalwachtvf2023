import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw } from 'vue-router';

const routes: Array<RouteRecordRaw> = [
  {
    path: '',
    redirect: '/Diensten'
  },
  {
    path: '/Smoelenboek',
    component: () => import ('../views/Smoelenboek.vue')
  },
  {
    path: '/Smoel/:slug',
    component: () => import ('../views/Smoel.vue')
  },
  {
    path: '/Voorstelling/:slug',
    component: () => import ('../views/Voorstelling.vue')
  },
  {
    path: '/Diensten',
    component: () => import ('../views/Diensten.vue')
  },
  {
    path: '/Google',
    component: () => import ('../views/GoogleCalendar.vue')
  },
  {
    path: '/Agenda',
    component: () => import ('../views/Agenda.vue')
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router
