import { createRouter, createWebHistory } from 'vue-router'
import TopDashboard from './components/TopDashboard.vue'
import MakeMenu from './components/MakeMenu.vue'
import GenerateMenu from './components/GenerateMenu.vue'
import ShowMenu from './components/ShowMenu.vue'
import ShowCooking from './components/ShowCooking.vue'
import DetailDish from './components/DetailDish.vue'

const routes = [
    {
        path: '/',
        name: 'TopDashboard',
        component: TopDashboard
    },
    {
        path: '/make-menu',
        name: 'MakeMenu',
        component: MakeMenu
    },
    {
        path: '/generate-menu',
        name: 'GenerateMenu',
        component: GenerateMenu
    },
    {
        path: '/show-menu',
        name: 'ShowMenu',
        component: ShowMenu
    },
    {
        path: '/show-cooking',
        name: 'ShowCooking',
        component: ShowCooking
    },
    {
        path: '/dish/:dish',
        name: 'DetailDish',
        component: DetailDish
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
