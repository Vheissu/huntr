import { IRouteableComponent, IRoute, ReloadBehavior } from "@aurelia/router";

export class MyApp implements IRouteableComponent {

    static routes: IRoute[] = [
        {
            id: 'home',
            component: import('./components/home/home-page'),
            path: '',
            title: 'Home',
            reloadBehavior: ReloadBehavior.reload
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
            title: 'Login'
        }
    ];

}
