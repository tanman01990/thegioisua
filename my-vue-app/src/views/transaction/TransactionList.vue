<template>
  <div>
    <h2>Danh sách Nhập</h2>
    <div>Số Bản ghi: {{ totalCount }}</div>
    <div>Số lượng hiện tại: {{ totalAmount }}</div>
    <div>
      <div class='wrapper'>
        <p><label>Từ ngày</label>
          <VueDatePicker v-model="fromDate" :format="'dd/MM/yyyy'" placeholder="Chọn ngày bắt đầu" />
          <label>Den ngày</label>
          <VueDatePicker v-model="toDate" :format="'dd/MM/yyyy'" placeholder="Chọn ngày  thúc" />
        </p>
        <button @click="fetchData"> Tìm kiếm </button>
        <button @click="exportExcel"> Xuất file </button>
        <button @click="showDeleteConfirmModal">Xoá dữ liệu </button>

        <img v-if="isLoading" src="@/assets/giphy.webp" alt="Loading..." />
      </div>
    </div>
    <confirmation-modal v-model="isModalVisible" title="Xoá dữ liệu" message="Bạn có chắc chăc chắn muốn xoá không ?"
      @confirm="deleteItem" @cancel="closeModal" />
    <ag-grid-vue class="ag-theme-quartz" :columnDefs="columnDefs" :rowData="items" :getRowId="getRowId"
      :frameworkComponents="frameworkComponents" :pagination="true" :paginationPageSize="pageSize"
      :paginationPageSizeSelector="paginationPageSizeSelector" @grid-ready="onGridReady" :modules="modules"
      @paginationChanged="onPaginationChanged" :defaultColDef="defaultColDef" :getRowClass="getRowClass"
      :getRowStyle="getRowStyle" style="width: 100%; height: 1000px;"></ag-grid-vue>

  </div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted } from 'vue';
import { AgGridVue } from "ag-grid-vue3"; // Vue Data Grid Component
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-quartz.css";
import ConfirmationModal from '@/components/ConfirmationModal.vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { useAlertStore } from '@/stores';
const ColourComponent = {
  template: '<span :style="{color: params.color}">{{params.value}}</span>'
};
const baseURL = import.meta.env.VITE_BASE_API_URL;
const showConfirmation = ref(false);
export default {
  components: {
    'ag-grid-vue': AgGridVue,
    'VueDatePicker': VueDatePicker,
    'confirmation-modal': ConfirmationModal
  },

  data() {

    return {
      fromDate: '',
      isLoading: false,
      isModalVisible: false,
      toDate: '',
      currentPage: 1,
      items: [],
      totalCount: 0,
      totalPages: 0,
      rowSelection: "multiple",
      transactionType: 'Nhap',
      totalAmount: 0,
      paginationPageSize: 50000,
      paginationPageSizeSelector: [500, 1000],
      pagination: {
        currentPage: 1,
        totalPages: 1,
        totalItems: 0,
      },
      activeFilters: {},
      columnDefs: [
        { headerName: 'ID', field: 'id', filter: true, editable: true, filter: true, width: 80 },
        { headerName: 'Tên SP', field: 'product.name', filter: true, editable: true, filter: true, width: 300 },
        { headerName: 'Mã Chính', field: 'product.mainCode', filter: true, editable: true, filter: true, width: 100 },
        { headerName: 'Mã Chính Mở Rộng', field: 'product.code', filter: true, editable: true, filter: true, width: 150 },
        { headerName: 'KH', field: 'customerName', filter: true, editable: true, filter: true, width: 150 },
        { headerName: 'Kho', field: 'inventory.name', filter: true, editable: true, filter: true, width: 100 },
        { headerName: 'SL', field: 'quantity', width: 80 },
        { headerName: 'Giá', field: 'unitPrice', width: 100 },
        { headerName: 'Giao dịch', field: 'transactionType', width: 100, filter: true, editable: true, suppressSizeToFit: true },
        { headerName: 'Thành tiền', field: 'totalPrice', width: 100 },
        {
          headerName: 'Ngày nhập', field: 'transactionDate', filter: true, editable: true,
          filter: 'agTextColumnFilter',
          width: 150, suppressSizeToFit: true
        },
      ],
      frameworkComponents: {
      },
    };
  },
  created() {
    this.setDefaultDates();
    this.fetchData();
  },
  computed: {
    filteredAgeSum() {
      if (!this.gridApi) {
        return 0;
      }
      let sum = 0;
      this.gridApi.forEachNodeAfterFilter(node => {
        sum += node.data.quantity;
      });
      return sum;
    }
  },
  methods: {
    refineDate(dateString) {
      console.log(typeof(dateString));
      if (typeof dateString === 'string') {
        // Split the string by space and take the first part
        dateString = dateString.split(' ')[0];
      } else if (typeof dateString === 'object') {
        dateString = dateString.toISOString().split('T')[0];
      }
      const cleanDateString = dateString.split(' ')[0];
      // Convert to a Date object
      const date = new Date(cleanDateString);
      // Format the date as YYYY-MM-DD
      const formattedDate = date.toISOString().split('T')[0];
      console.log(formattedDate);  // Output: "2024-08-01"
      return formattedDate;
    },
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

    async fetchData() {
      try {
        const from = this.fromDate;
        const to = this.toDate
        const page = this.currentPage;
        const limit = this.paginationPageSize;
        const transactionType = this.transactionType;
        const response = await axios.get(`${baseURL}/transaction/query`, {
          params: { page, limit, from, to, transactionType },
        });
        this.items = response.data.items;
        this.pagination = response.data.pagination;
        this.pageSize = limit;
        this.totalCount = response.data.pagination.totalItems;
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    },
    async exportExcel() {
      try {
        this.isLoading = true;
        this.exportFilteredData();
        this.isLoading = false;
      } catch (error) {
        console.error('Error fetching data:', error);
      } finally {
        this.isLoading = false;
      }
    },
    showDeleteConfirmModal() {
      this.isModalVisible = true;
      showConfirmation.value = true;
    },
    closeModal() {
      this.isModalVisible = false;
    },
    async deleteItem() {
      try {
        const alertStore = useAlertStore()
        let from = this.fromDate;
        from = this.refineDate(from)
        let to = this.toDate
        to = this.refineDate(to)
        console.log("Ngay from ::" + from);
        console.log("Ngay to ::" + to);
        // Call the API to delete the item
        const response = await axios.post(`${baseURL}/delete-transaction`, {
          from: from,
          to: to,
          type: 'Nhap'
        });
        if (response.data) {
          console.log('Số bản ghi đã xoá ::' + response.data);
          alertStore.success('Số bản ghi đã xoá ::' + response.data.data);
        } else {
          console.error('Failed to delete item' );
        }
      } catch (error) {
        console.error('Error:', error);
      } finally {
        this.closeModal(); // Close the modal after deletion
      }
    },
    onGridReady(params) {
      this.gridApi = params.api;
      this.gridColumnApi = params.columnApi;
      // Fetch initial total count
      this.fetchTotalCount();
      this.gridApi.addEventListener('filterChanged', this.onFilterChanged);
    },
    async fetchTotalCount() {
      try {
        // const response = await axios.get('your-api-endpoint/count');
        this.totalRows = 50000;
        this.gridApi.paginationSetPageSize(this.paginationPageSize);
        // this.gridApi.p
      } catch (error) {
        console.error('Error fetching total count:', error);
      }
    },
    updateActiveFilters() {
      this.activeFilters = {};
      const filterModel = this.gridApi.getFilterModel();
      for (const [key, value] of Object.entries(filterModel)) {
        this.activeFilters[key] = value;
      }
    },
    exportFilteredData() {
      const filteredData = [];
      this.gridApi.forEachNodeAfterFilter(node => {
        const temp = [];
        temp.push(node.data.id);
        temp.push(node.data.product.name);
        temp.push(node.data.product.mainCode);
        temp.push(node.data.product.code);
        temp.push(node.data.customerName);
        temp.push(node.data.quantity);
        temp.push(node.data.transactionType);
        temp.push(node.data.transactionDate);
        filteredData.push(temp);
      });
      axios.post(`${baseURL}/small-export`, {
        filters: this.activeFilters,
        data: filteredData,
        type: 'Nhap'
      }, {
        responseType: 'arraybuffer' // Important: This tells axios to expect binary data
      })
        .then(response => {
          // Create a Blob from the Excel data
          const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });

          // Create a link element and trigger the download
          const link = document.createElement('a');
          link.href = window.URL.createObjectURL(blob);
          link.download = 'data-nhap.xlsx';
          link.click();

          // Clean up
          window.URL.revokeObjectURL(link.href);
        })
        .catch(error => {
          console.error('Error exporting Excel:', error);
        });
    },
    onFilterChanged() {
      // Trigger re-computation of filteredAgeSum

      let sum = 0;
      this.gridApi.forEachNodeAfterFilter(node => {
        sum += node.data.quantity;
      });
      this.totalAmount = sum;

      this.$forceUpdate();
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
.wrapper p {
  display: flex;
  align-items: center;
}

.wrapper p>* {
  margin-right: 10px;
}

.ag-theme-alpine {
  width: 100%;
  height: 100%;
}

img {
  display: block;
  margin: 20px auto;
}

.high-price-row {
  background-color: #fdd;
  /* Light red background for high price */
}

.low-price-row {
  background-color: #dfd;
  /* Light green background for low price */
}
</style>
