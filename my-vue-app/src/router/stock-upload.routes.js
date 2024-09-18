import { Layout, ImportStockUpload } from '@/views/upload';

export default {
    path: '/stockupload',
    component: Layout,
    children: [
        { path: '', component: ImportStockUpload },
    ]
};