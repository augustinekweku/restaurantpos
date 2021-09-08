<template>
    <div class="mx-3 items-inventory-page animate__animated animate__fadeIn mb-4">
        <h2 class="text-center my-3">Inventory Records</h2>
        <div class>
            <div class="categories_table">
                <vue-good-table
                :columns="columns1"
                    :rows="inventoryRecords"
                    :line-numbers="true"
                    :search-options="{ enabled: true }"
                    :pagination-options="{
                            enabled: true
                        }"
                    >
                </vue-good-table>
            </div>
        </div>

    </div>
</template>
<script>

export default {

    data() {
        return {
            inventoryRecords: [],
                columns1: [
                {
                    label: "Item",
                    field: this.item
                },
                {
                    label: "Old Stock",
                    field: "old_stock"
                },
                {
                    label: "New Stock",
                    field: "new_stock",
                },
                {
                    label: "Old Qty",
                    field: "old_qty"
                },
                {
                    label: "Qty Added",
                    field: "qty_added"
                },
                {
                    label: "New Qty Left",
                    field: "new_qty_left"
                },
                {
                    label: "Date",
                    field: "date"
                },
                {
                    label: "User",
                    field: this.user
                },
            ]
        };
    },
    methods: {
        item(RowObj){
            return RowObj.item.item_name
        },
        user(RowObj){
            return RowObj.user.firstName
        },
        async getInventoryRecords(){
    
            const getRecords = await this.callApi("get", "app/get_inventory_records");
                if ((getRecords.status = 201)) {
                this.inventoryRecords = getRecords.data;
                console.log(getRecords.data);
                } else {
                this.swr();
                }
            
        }
    },

    async created() {
        this.getInventoryRecords()
        }


}



</script>

<style scoped>


</style>
