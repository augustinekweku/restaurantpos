import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)


import readyOrders from './pages/ready-orders.vue';
import posPage from './pages/pos-page.vue';
import adminUsers from './pages/admin-users.vue';
import categories from './pages/categories.vue';
import items from './pages/items.vue';
import tables from './pages/tables.vue';
import orders from './pages/orders.vue';
import roles from './pages/roles.vue';



const routes = [
    //admin routes
    {
        path:  '/ready-orders',
        component: readyOrders,
        name: 'readyOrders'
    },
    {
        path:  '/pos-page',
        component: posPage,
        name: 'posPage'
    },
    {
        path:  '/admin-users',
        component: adminUsers,
        name: 'adminUsers'
    },
    {
        path:  '/categories',
        component: categories,
        name: 'categories'
    },
    {
        path:  '/items',
        component: items,
        name: 'items'
    },
    {
        path:  '/orders',
        component: orders,
        name: 'orders'
    },
    {
        path:  '/tables',
        component: tables,
        name: 'tables'
    },
    {
        path:  '/roles',
        component: roles,
        name: 'roles'
    },

    
]

export default new Router ({
    mode: 'history',
    routes,
})