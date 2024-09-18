<template>
  <div>
    <h2>Danh sách Xuất</h2>
    <div>Số Bản ghi: {{ totalCount }}</div>
    <div>
      <div class='wrapper'>
        Từ ngày <input type="date" v-model="fromDate"> Đến ngày <input type="date" v-model="toDate">
        <button @click="fetchData">Tìm kiếm</button>
        <button @click="exportExcel">Xuất file</button>
        <img v-if="isLoading" src="@/assets/giphy.webp" alt="Loading..." />
      </div>
    </div>
    <ag-grid-vue class="ag-theme-quartz" :columnDefs="columnDefs" :rowData="items" :getRowId="getRowId"
      :frameworkComponents="frameworkComponents" :pagination="true" :paginationPageSize="pageSize"
      :paginationPageSizeSelector="paginationPageSizeSelector" @grid-ready="onGridReady" :modules="modules"
      @paginationChanged="onPaginationChanged" :defaultColDef="defaultColDef"
      :getRowClass="getRowClass"
      :getRowStyle="getRowStyle"
      style="width: 100%; height: 1000px;"></ag-grid-vue>
    <div class="custom-pagination">
      <button @click="onBtPrevious">Previous</button>
      <span>Page {{ currentPage }} of {{ totalPages }}</span>
      <button @click="onBtNext">Next</button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { AgGridVue } from "ag-grid-vue3"; // Vue Data Grid Component
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-quartz.css";
import { DatePickerComponent as EjsDatepicker } from '@syncfusion/ej2-vue-calendars';
import Datepicker from 'vue-datepicker-next'
import 'vue-datepicker-next/index.css'
const ColourComponent = {
  template: '<span :style="{color: params.color}">{{params.value}}</span>'
};
const baseURL = import.meta.env.VITE_BASE_API_URL;

export default {
  components: {
    'ag-grid-vue': AgGridVue,
    EjsDatepicker
  },

  data() {

    return {
      fromDate: '',
      isLoading: false,
      toDate: '',
      currentPage: 1,
      items: [],
      totalCount: 0,
      totalPages: 0,
      rowSelection: "multiple",
      paginationPageSize: 50000,
      transactionType: 'Xuat',
      paginationPageSizeSelector: [500, 1000],
      pagination: {
        currentPage: 1,
        totalPages: 1,
        totalItems: 0,
      },
      columnDefs: [
        { headerName: 'ID', field: 'id', filter: true, editable: true, filter: true, width: 80  },
        { headerName: 'Tên SP', field: 'product.name', filter: true, editable: true, filter: true, width: 300  },
        { headerName: 'Mã Chính', field: 'product.mainCode', filter: true, editable: true, filter: true, width: 100 },
        { headerName: 'Mã Chính Mở Rộng', field: 'product.code', filter: true, editable: true, filter: true,  width: 150 },
        { headerName: 'KH', field: 'customerName', filter: true, editable: true, filter: true ,width: 150},
        { headerName: 'Kho', field: 'inventory.name', filter: true, editable: true, filter: true ,width: 100},
        { headerName: 'SL', field: 'quantity',width: 80 },
        { headerName: 'Giá', field: 'unitPrice', width: 100 },
        { headerName: 'Giao dịch', field: 'transactionType',width: 100, filter: true, editable: true, suppressSizeToFit: true },
        { headerName: 'Thành tiền', field: 'totalPrice', width: 100 },
        { headerName: 'Ngày nhập', field: 'transactionDate' ,filter: true, editable: true,  width: 150, suppressSizeToFit: true},
      ],
      frameworkComponents: {
      },
    };
  },
  created() {
    this.setDefaultDates();
    this.fetchData();
  },
  methods: {
    getRowClass(params) {
      if (params.data.id % 2) {
        return 'high-price-row';
      } else {
        return 'low-price-row';
      }
    },
    getRowStyle(params) {
      if (params.data.id % 2) {
        return { backgroundColor: '#ECEFF1' }; // Light red background for high price
      } else {
        return { backgroundColor: '#F5F5F5' }; // Light green background for low price
      }
    },
    getFirstDayOfMonth(date) {
      return new Date(date.getFullYear(), date.getMonth(), 1).toISOString().split('T')[0];
    },
    getLastDayOfMonth(date) {
      return new Date(date.getFullYear(), date.getMonth() + 1, 0).toISOString().split('T')[0];
    },
    setDefaultDates() {
      const now = new Date();
      this.fromDate = this.getFirstDayOfMonth(now);
      this.toDate = this.getLastDayOfMonth(now);
    },
    async exportExcel() {
      try {
        this.isLoading = true;
        const from = this.fromDate;
        const to = this.toDate
        // const page = this.currentPage;
        // const limit = 50;
        const page = this.currentPage;
        const limit = this.paginationPageSize;
        const response = await axios.get(`${baseURL}/export`, {
          params: { page, limit, from, to },
          responseType: 'blob'
        });

        const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
        const link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.download = 'export.xlsx';
        link.click();
      } catch (error) {
        console.error('Error fetching data:', error);
      } finally {
        this.isLoading = false;
      }
    },
    async fetchData() {
      try {
        const from = this.fromDate;
        const to = this.toDate
        // const page = this.currentPage;
        // const limit = 50;
        const page = this.currentPage;
        const limit = this.paginationPageSize;
        const transactionType = this.transactionType;
        const response = await axios.get(`${baseURL}/transaction/query`, {
          params: { page, limit, from, to , transactionType},
        });
        this.items = response.data.items;
        this.pagination = response.data.pagination;
        this.pageSize = limit;
        this.totalCount = response.data.pagination.totalItems;
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    },
    onGridReady(params) {
      this.gridApi = params.api;
      this.gridColumnApi = params.columnApi;
      // Fetch initial total count
      this.fetchTotalCount();
    },
    async fetchTotalCount() {
      try {
        // const response = await axios.get('your-api-endpoint/count');
        this.totalRows = 1200;
        this.gridApi.paginationSetPageSize(this.paginationPageSize);
        // this.gridApi.p
      } catch (error) {
        console.error('Error fetching total count:', error);
      }
    },
    onBtPrevious() {
      this.currentPage = this.currentPage - 1;
      if ($this.currentPage <= 1) {
        $this.currentPage = 1;
      }
      this.fetchData();
    },
    onBtNext() {
      const parts = this.pagination.totalItems / this.paginationPageSize;
      if (this.currentPage <= parts) {
        this.currentPage = this.currentPage + 1;
        this.fetchData();
      }
    },
    paginationNumberFormatter(params) {
      return '[' + params.value.toLocaleString() + ']';
    },
    onPaginationChanged() {
      if (this.gridApi) {
        this.currentPage = this.gridApi.paginationGetCurrentPage() + 1;
        if (this.currentPage !== this.pagination.currentPage) {
        }
      }
    },
  },
};
</script>

<style scoped>
.ag-theme-alpine {
  width: 100%;
  height: 100%;
}
img {
  display: block;
  margin: 20px auto;
}
.high-price-row {
  background-color: #fdd; /* Light red background for high price */
}

.low-price-row {
  background-color: #dfd; /* Light green background for low price */
}
</style>
