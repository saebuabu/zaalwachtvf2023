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
    path: '/Diensten',
    component: () => import ('../views/Diensten.vue')
  },
  {
    path: '/Google',
    component: () => import ('../views/GoogleCalendar.vue')
  },
  {
    path: '/Exit',
    component: {
      template: '<div></div>',
      mounted: () => {
        window.close();
      }
    }
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

export default router
