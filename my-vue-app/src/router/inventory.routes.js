import { Layout, MainInventory } from '@/views/inventory';

export default {
    path: '/inventory',
    component: Layout,
    children: [
        { path: '', component: MainInventory },
    ]
};