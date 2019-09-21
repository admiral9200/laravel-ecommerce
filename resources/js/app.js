/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue')

import Layout from 'ant-design-vue/lib/layout'
import Menu from 'ant-design-vue/lib/menu'
import Form from 'ant-design-vue/lib/form'
import Select from 'ant-design-vue/lib/select'
import Breadcrumb from 'ant-design-vue/lib/breadcrumb'
import Dropdown from 'ant-design-vue/lib/dropdown'

Vue.use(Layout)
Vue.use(Menu)
Vue.use(Form)
Vue.use(Select)
Vue.use(Breadcrumb)
Vue.use(Dropdown)

Vue.component('a-checkbox', () => import('ant-design-vue/lib/checkbox'))
Vue.component('a-switch', () => import('ant-design-vue/lib/switch'))
Vue.component('a-input', () => import('ant-design-vue/lib/input'))
Vue.component('a-input-number', () => import('ant-design-vue/lib/input-number'))
Vue.component('a-divider', () => import('ant-design-vue/lib/divider'))
Vue.component('a-avatar', () => import('ant-design-vue/lib/avatar'))
Vue.component('a-tooltip', () => import('ant-design-vue/lib/tooltip'))
Vue.component('a-avatar', () => import('ant-design-vue/lib/avatar'))
Vue.component('a-row', () => import('ant-design-vue/lib/row'))
Vue.component('a-col', () => import('ant-design-vue/lib/col'))
Vue.component('a-card', () => import('ant-design-vue/lib/card'))
Vue.component('a-card', () => import('ant-design-vue/lib/card'))
Vue.component('a-card-meta', () => import('ant-design-vue/lib/card'))
Vue.component('a-button', () => import('ant-design-vue/lib/button'))
Vue.component('a-icon', () => import('ant-design-vue/lib/icon'))
Vue.component('a-carousel', () => import('ant-design-vue/lib/carousel'))
Vue.component('a-upload', () => import('ant-design-vue/lib/upload'))


Vue.component('address-save', () => import('../components/address/AddressSave.vue'))
Vue.component('user-order-table', () => import('../components/account/order/OrderTable.vue'))

Vue.component('account-save', () => import('../components/account/AccountSave.vue'))
Vue.component('account-upload', () => import('../components/account/AccountUpload.vue'))
Vue.component('category-page', () => import('../components/CategoryPage.vue'))
Vue.component('product-page', () => import('../components/ProductPage.vue'))
Vue.component('checkout-page', () => import('../components/CheckoutPage.vue'))
Vue.component('cart-page', () => import('../components/CartPage.vue'))

Vue.component('avored-layout', () => import('../components/layout/Layout.vue'))
Vue.component('login-fields', () => import('../components/auth/LoginFields.vue'))
Vue.component('register-fields', () => import('../components/auth/RegisterFields.vue'))

Vue.component('avored-nav', () => import('../components/AvoRedNav.vue'))

const app = new Vue({
    el: '#app',
})
