<template>
  <div>
    <h2>Product List</h2>
    <div>Total Products: {{ totalCount }}</div>
    <ag-grid-vue class="ag-theme-quartz" :columnDefs="columnDefs" :rowData="products" :getRowId="getRowId"
      :frameworkComponents="frameworkComponents" :pagination="true" :paginationPageSize="pageSize"
      :paginationPageSizeSelector="paginationPageSizeSelector" @grid-ready="onGridReady" :modules="modules"
      @paginationChanged="onPaginationChanged" :defaultColDef="defaultColDef"
       @row-clicked="onRowClicked"
        :singleClickEdit="true"
      style="width: 100%; height: 1000px;"></ag-grid-vue>
    <!-- <TestProduct /> -->
  </div>
</template>

<script>
import axios from 'axios';
import { AgGridVue } from "ag-grid-vue3"; // Vue Data Grid Component
import "ag-grid-community/styles/ag-grid.css";
import "ag-grid-community/styles/ag-theme-quartz.css";
import { EditButton, DeleteButton, DynamicButton } from '@/components';
import TestProduct from "./TestProduct.vue";
import ActionButtonRenderer from '@/components/ActionButtonRenderer.vue';
import { ClientSideRowModelModule } from "@ag-grid-community/client-side-row-model";
import { ModuleRegistry } from "@ag-grid-community/core";
const ColourComponent = {
  template: '<span :style="{color: params.color}">{{params.value}}</span>'
};
const baseURL = import.meta.env.VITE_BASE_API_URL;
export default {
  components: {
    'ag-grid-vue': AgGridVue,
    ActionButtonRenderer,
    ColourComponent,
    TestProduct,
  },

  data() {
    return {
      products: [],
      totalCount: 0,
      rowSelection: "multiple",
      paginationPageSize: 30,
      paginationPageSizeSelector: [10, 25, 50],
      pagination: {
        currentPage: 1,
        totalPages: 1,
        totalItems: 0,
      },
      columnDefs: [
        { headerName: 'Code', field: 'code', filter: true, editable: true, filter: true },
        { headerName: 'Name', field: 'name' },
        { headerName: 'Price', field: 'price' },
        {
          headerName: 'Unit', field: 'unit',
          cellRenderer: params => {
            // put the value in bold
            return  params.value ;
          }
        },
        {
          headerName: "Actions",
          cellRenderer: ActionButtonRenderer,
          cellRendererParams: {
            editAction: () => {
              console.log('Edit action clicked');
            },
            deleteAction: this.handleDeleteAction,
          },
        },
        {
          headerName: "Colour 2",
          field: "unit",
          cellRenderer: ColourComponent,
          cellRendererParams: {
            color: 'irishGreen'
          }
        },
        {
          field: "image",
          headerName: "Success",
          cellRenderer: "MissionResultRenderer",
        }
      ],
      frameworkComponents: {
        ActionButtonRenderer: ActionButtonRenderer,
        ColourComponent: ColourComponent
      },
      gridOptions: {
        defaultColDef: {
          editable: true, // make columns editable
        },
        singleClickEdit: true,
        onCellValueChanged: this.onCellValueChanged,
      },
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    async fetchData(page = 1, limit = 30) {
      try {
        const response = await axios.get(`${baseURL}/product`, {
          params: { page, limit },
        });
        this.products = response.data.items;
        this.pagination = response.data.pagination;
        this.totalCount = response.data.pagination.totalItems;
      } catch (error) {
        console.error('Error fetching data:', error);
      }
    },
    onGridReady(params) {
      this.gridApi = params.api;
      this.gridColumnApi = params.columnApi;
      updateData = (data) => (rowData.value = data);
      console.log(updateData);
    },
    onPaginationChanged() {
      console.log("onPaginationChanged");
      if (this.gridApi) {
        const currentPage = this.gridApi.paginationGetCurrentPage() + 1;
        console.log('Current page:', currentPage);
        if (currentPage !== this.pagination.currentPage) {
          this.fetchData(currentPage, 30);
        }
      }
    },
    onRowClicked(event) {
      console.log('Row clicked', event.data);
      // You can do more here, like changing state or calling other methods
    },
    async editProduct(product) {
      // Handle edit action, for example, open a modal with the product data
      console.log('Edit product', product);
    },
    async deleteProduct(product) {
      // Handle delete action, for example, send a delete request to the server
      try {
        await axios.delete(`${baseURL}/product/${product.id}`);
        this.fetchData(this.pagination.currentPage);
      } catch (error) {
        console.error('Error deleting product:', error);
      }
    },
    handleEditAction(params) {
      console.log("Edit action clicked for row:", params.data);
      // Implement your edit logic here
    },
    handleDeleteAction(params) {
      console.log("Delete action clicked for row:", params.data);
      // Implement your delete logic here
    },
    onCellValueChanged(event) {
      console.log('Data after change:', event.data);
      // You might want to update your backend with the new data here
    }
  },
};
</script>

<style scoped>
.ag-theme-alpine {
  width: 100%;
  height: 500px;
}
</style>
