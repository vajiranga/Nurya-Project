const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') },
      { path: 'product/:id', component: () => import('pages/ProductPage.vue') },
      { path: 'cart', component: () => import('pages/CartPage.vue') },
      { path: 'wishlist', component: () => import('pages/WishlistPage.vue') },
      { path: 'contact', component: () => import('pages/ContactUsPage.vue') },
      { path: 'vouchers', component: () => import('pages/VouchersPage.vue') }
    ],
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue'),
  },
]

export default routes
