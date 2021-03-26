/**
 * AutoImporting components
 * @param path
 * @returns {function(): *}
 */
const page = path => () => import(/* webpackChunkName: '' */ `./components/${path}`).then(m => m.default || m)

export const routes = [
    {
        path: '/books',
        name: 'Books',
        component: page('BookList'),
    },
    {
        path: '/books/:id',
        name: 'Show Book',
        component: page('BookView'),
        hidden: true
    }
]
