import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home.vue'
import ProductList from '../components/Products/ProductList.vue'
import ProductShow from '../components/Products/ProductShow.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/products',
    name: 'products',
    component: ProductList
  },
  {
    path: '/products/:id',
    name: 'product-show',
    component: ProductShow
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
