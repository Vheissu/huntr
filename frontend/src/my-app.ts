import { IRouteableComponent, IRoute } from "@aurelia/router";

export class MyApp implements IRouteableComponent {

    static routes: IRoute[] = [
        {
            id: 'home',
            component: import('./components/home/home-page'),
            path: '',
            title: 'Home'
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
        }
    ];

}
