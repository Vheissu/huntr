import { IRouteableComponent, IRoute } from "@aurelia/router";

import 'bootstrap/dist/css/bootstrap.css';
import 'izitoast/dist/css/iziToast.min.css';
import './variables.css';

const AppRoutes: IRoute[] = [
    {
        component: () => import('./components/home/home-page'),
        path: '',
        title: 'Home',
        //redirectTo: '/home'
    },
    {
        component: () => import('./components/home/home-page'),
        path: 'home',
        title: 'Home',
    },
    {
        id: 'about',
        component: import('./components/about/about-page'),
        path: 'about',
        title: 'About'
    },
    {
        id: 'products',
        component: () => import('./components/products/products-page'),
        path: 'products/:product',
        title: 'Product'
    },
    {
        id: 'topics',
        component: () => import('./components/topics/topics-page'),
        path: ['topics', 'topics/:topic'],
        title: 'Topics'
    },
    {
        id: 'topics',
        component: () => import('./components/collections/collections-page'),
        path: ['collections', 'collections/:topic'],
        title: 'Collections'
    },
    {
        id: 'login',
        component: () => import('./components/auth/login-page'),
        path: 'login',
        title: 'Login',
        data: {
            public: true
        }
    },
    {
        id: 'register',
        component: () => import('./components/auth/register-page'),
        path: 'register',
        title: 'Register',
        data: {
            public: true
        }
    },
    {
        id: 'my-profile',
        component: () => import('./components/profile/profile-page'),
        path: 'my/profile',
        title: 'Profile',
        data: {
            isAuth: true
        }
    },
];

export class MyApp implements IRouteableComponent {

    static routes: IRoute[] = AppRoutes;

}
