import { Layout, TransactionList , TransactionExport, OutTransactionList} from '@/views/transaction';

export default {
    path: '/transaction',
    component: Layout,
    children: [
        { path: '', component: TransactionList },
        { path: 'export', component: TransactionExport },
        { path: 'out', component: OutTransactionList  },
    ]
};