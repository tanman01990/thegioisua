import { Layout, ProductList } from '@/views/product';

export default {
    path: '/product',
    component: Layout,
    children: [
        { path: '', component: ProductList },
    ]
};