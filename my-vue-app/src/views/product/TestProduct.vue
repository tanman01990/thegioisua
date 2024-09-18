<template>
    <!-- The AG Grid component -->
    <ag-grid-vue
      :rowData="rowData"
      :columnDefs="colDefs"
      :pagination="true"
      :rowSelection="rowSelection"
      :paginationPageSize="paginationPageSize"
      :paginationPageSizeSelector="paginationPageSizeSelector"
      style="height: 500px"
      class="ag-theme-quartz"
    >
    </ag-grid-vue>

</template>

<script>
import { ref } from 'vue';
import "ag-grid-community/styles/ag-grid.css"; // Mandatory CSS required by the grid
import "ag-grid-community/styles/ag-theme-quartz.css"; // Optional Theme applied to the grid
import { AgGridVue } from "ag-grid-vue3"; // Vue Data Grid Component

export default {
    name: "App",
    components: {
        AgGridVue, // Add Vue Data Grid component
    },
    setup() {
        // Row Data: The data to be displayed.
        const rowData = ref([{
          make: "Tesla",
          model: "Model Y",
          price: 64950,
          electric: true,
          month: "June",
        },
        {
          make: "Ford",
          model: "F-Series",
          price: 33850,
          electric: false,
          month: "October",
        },
        {
          make: "Toyota",
          model: "Corolla",
          price: 29600,
          electric: false,
          month: "August",
        },
        {
          make: "Mercedes",
          model: "EQA",
          price: 48890,
          electric: true,
          month: "February",
        },
        {
          make: "Fiat",
          model: "500",
          price: 15774,
          electric: false,
          month: "January",
        },
        {
          make: "Nissan",
          model: "Juke",
          price: 20675,
          electric: false,
          month: "March",
        },
        {
          make: "Vauxhall",
          model: "Corsa",
          price: 18460,
          electric: false,
          month: "July",
        },
        {
          make: "Volvo",
          model: "EX30",
          price: 33795,
          electric: true,
          month: "September",
        },
        {
          make: "Mercedes",
          model: "Maybach",
          price: 175720,
          electric: false,
          month: "December",
        },
        {
          make: "Vauxhall",
          model: "Astra",
          price: 25795,
          electric: false,
          month: "April",
        },
        {
          make: "Fiat",
          model: "Panda",
          price: 13724,
          electric: false,
          month: "November",
        },
        {
          make: "Jaguar",
          model: "I-PACE",
          price: 69425,
          electric: true,
          month: "May",
        },
        {
          make: "Tesla",
          model: "Model Y",
          price: 64950,
          electric: true,
          month: "June",
        },
        {
          make: "Ford",
          model: "F-Series",
          price: 33850,
          electric: false,
          month: "October",
        },
        {
          make: "Toyota",
          model: "Corolla",
          price: 29600,
          electric: false,
          month: "August",
        },
        {
          make: "Mercedes",
          model: "EQA",
          price: 48890,
          electric: true,
          month: "February",
        },
        {
          make: "Fiat",
          model: "500",
          price: 15774,
          electric: false,
          month: "January",
        },
        {
          make: "Nissan",
          model: "Juke",
          price: 20675,
          electric: false,
          month: "March",
        },
        {
          make: "Vauxhall",
          model: "Corsa",
          price: 18460,
          electric: false,
          month: "July",
        },
        {
          make: "Volvo",
          model: "EX30",
          price: 33795,
          electric: true,
          month: "September",
        },
        {
          make: "Mercedes",
          model: "Maybach",
          price: 175720,
          electric: false,
          month: "December",
        },
        {
          make: "Vauxhall",
          model: "Astra",
          price: 25795,
          electric: false,
          month: "April",
        },
        {
          make: "Fiat",
          model: "Panda",
          price: 13724,
          electric: false,
          month: "November",
        },
        {
          make: "Jaguar",
          model: "I-PACE",
          price: 69425,
          electric: true,
          month: "May",
        },
        {
          make: "Tesla",
          model: "Model Y",
          price: 64950,
          electric: true,
          month: "June",
        },
        {
          make: "Ford",
          model: "F-Series",
          price: 33850,
          electric: false,
          month: "October",
        },
        {
          make: "Toyota",
          model: "Corolla",
          price: 29600,
          electric: false,
          month: "August",
        },
        {
          make: "Mercedes",
          model: "EQA",
          price: 48890,
          electric: true,
          month: "February",
        },
        {
          make: "Fiat",
          model: "500",
          price: 15774,
          electric: false,
          month: "January",
        },
        {
          make: "Nissan",
          model: "Juke",
          price: 20675,
          electric: false,
          month: "March",
        },
        {
          make: "Vauxhall",
          model: "Corsa",
          price: 18460,
          electric: false,
          month: "July",
        },
        {
          make: "Volvo",
          model: "EX30",
          price: 33795,
          electric: true,
          month: "September",
        },
        {
          make: "Mercedes",
          model: "Maybach",
          price: 175720,
          electric: false,
          month: "December",
        },
        {
          make: "Vauxhall",
          model: "Astra",
          price: 25795,
          electric: false,
          month: "April",
        },
        {
          make: "Fiat",
          model: "Panda",
          price: 13724,
          electric: false,
          month: "November",
        },
        {
          make: "Jaguar",
          model: "I-PACE",
          price: 69425,
          electric: true,
          month: "May",
        }]);
        const rowSelection = "multiple";
        const paginationPageSize = 10;
        const paginationPageSizeSelector = [10, 25, 50];

        // Column Definitions: Defines the columns to be displayed.
        const colDefs = ref([
            { field: "make", editable: true,
        cellEditor: 'agSelectCellEditor',
        cellEditorParams: {
            values: ['Tesla', 'Ford', 'Toyota'],
        }, },
            { field: "model" },
            { field: "price" },
            { field: "electric" }
        ]);

        return {
            rowData,
            colDefs,
            rowSelection,
            paginationPageSize,
            paginationPageSizeSelector,
        };
    },
};
</script>