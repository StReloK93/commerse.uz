<template>
	<ag-grid-vue
		style="height: 500px;"
		class="ag-theme-alpine"
		:defaultColDef="defaultColDef"
		:columnDefs="columnDefs"
		:rowData="rowData"
		@grid-ready="onGridReady"
		@cellValueChanged="changed"
		@firstDataRendered="onFirstDataRendered"
		:enableRangeSelection="enableRangeSelection"
		:enableFillHandle="enableFillHandle"
		:undoRedoCellEditing="undoRedoCellEditing"
		:undoRedoCellEditingLimit="undoRedoCellEditingLimit"
		:enableCellChangeFlash="enableCellChangeFlash"
	></ag-grid-vue>
</template>
<script>
import { AgGridVue } from "ag-grid-vue3";

export default {
	name: "App",
	data() {
		return {
			enableRangeSelection: true,
			enableFillHandle: true,
			undoRedoCellEditing: true,
			undoRedoCellEditingLimit: null,
			enableCellChangeFlash: true,
			columnDefs: null,
			rowData: null,
			defaultColDef: {
				flex: 1,
				Width: 100,
				filter: true,
				// sortable: true,
				floatingFilter: true,
			},
		};
	},
	components: {
		AgGridVue,
	},
	beforeMount() {
		this.undoRedoCellEditingLimit = 5;
		this.columnDefs = [
			{
				headerName: "Number",
				field: "id",
				editable: false,
				suppressMenu: true,
				filter: false,
			},
			{ field: "email", editable: true, suppressMenu: true },
			{ field: "name", editable: true, suppressMenu: true },
		];
	},
	methods: {
		onGridReady(params) {
			axios.get("/api/users").then((res) => {
				this.rowData = res.data;
			});
		},
		changed(params) {
			console.log(params.data);
			axios.post("/api/users/edit", params.data).then((res) => {
				console.log(res);
			});
		},
	},
};
</script>

<style>
	@import "../assets/ag-grid.css";
  	@import "../assets/ag-theme-alpine.css";
</style>
