import { Layout, ExportStockUpload } from '@/views/upload';

export default {
    path: '/upload',
    component: Layout,
    children: [
        { path: '', component: ExportStockUpload },
    ]
};