<template>
    <div class=" mt-3 items_page container mx-3 animate__animated animate__fadeIn mb-5">
        <Button @click="addModal=true" class="add_fab shadow m-3" type="warning" size="large" icon="md-add" shape="circle"></Button>
        <div class="row gx-5 gy-2">
            <h2 class="text-center mb-3">Meal Items</h2>
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                <Select not-found-text="No categries creeated" placeholder="Select Category" width="40px">
                    <Option
                        v-for="(c, i) in categories"
                        :value="c.id"
                        :key="i"
                        >{{ c.category_name }}</Option
                    >
                </Select>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                <Button type="warning"
                    >Filter <Icon type="md-options" />
                </Button>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                <Button type="warning"
                    >Show All <Icon type="md-medical" />
                </Button>
            </div>
        </div>
        <div class=" my-4 row ">
                <div v-for="(item, i) in items.data" :key="item.id"
                    class="col-sm-12 col-md-6 col-lg-3 col-xl-3 animate__animated animate__bounceIn">
                            <div class>
                                    <!-- start of item card  -->
                                        <div class="item-card">
                                            <div
                                                v-bind:style="{
                                                    backgroundImage:
                                                        'url(' +
                                                        item.image +
                                                        ')'
                                                }"
                                                class="item-card__image"
                                            ></div>
                                            <div
                                                class="item-card__title"
                                            >
                                                {{ item.item_name }}
                                            </div>
                                            <div
                                                v-html="item.item_description"
                                                class="item-card__description"
                                            >
                                            </div>
                                            <p class="item-card__description"> <b> Stock:</b> {{item.stock}}</p>
                                            <p class="item-card__description"> <b> Qty left:</b> {{item.qty_left}}</p>

                                            <div
                                                class="item-card__footer px-3 py-2 d-flex justify-content-between"
                                            >
                                                <div class="one-fourth">
                                                    <div >
                                                        ₵ {{ item.price }}
                                                    </div>
                                                </div>

                                                <div class="one-fourth">
                                                    <div >
                                                        <Tooltip content="Edit">
                                                            <Icon
                                                                @click="
                                                                    showEditItem(
                                                                        item,
                                                                        i
                                                                    )
                                                                "
                                                                type="md-create"
                                                            />
                                                        </Tooltip>
                                                    </div>
                                                </div>

                                                <div class="one-fourth">
                                                    <div >
                                                        <Tooltip content="Add to stock">
                                                            <Icon
                                                                @click="
                                                                    showAddStockModal(
                                                                        item,
                                                                        i
                                                                    )
                                                                "
                                                                type="md-add"
                                                            />
                                                        </Tooltip>
                                                    </div>
                                                </div>

                                                <div
                                                    class="one-fourth no-border"
                                                >
                                                    <div >
                                                        <Tooltip
                                                            content="Delete"
                                                        >
                                                            <Icon
                                                                @click="
                                                                    showDeletingModal(item, i)
                                                                "
                                                                type="ios-trash"
                                                            />
                                                        </Tooltip>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end item-card-->
                                    </div>
                                    <!-- end wrapper -->
                </div>
                <pagination class="mb-2 mt-5" align="center" :data="items" @pagination-change-page="list"></pagination>
        </div>

                <!-- START OF ITEM ADDING MODAL -->
        <Modal
            v-model="addModal"
            title="Add Item"
            :mask-closable="false"
            :closable="false"
        >
            <div class="space">
                <Input
                    type="text"
                    v-model="form.item_name"
                    placeholder="Item Name"
                />
            </div>
            <div class="space"></div>
            <div class="space">
                <Input
                    type="text"
                    v-model="form.item_description"
                    placeholder="Description"
                />
            </div>
            <div class="space">
                <Input
                    type="number"
                    min="1"
                    v-model="form.stock"
                    placeholder="Stock"
                
                />
            </div>
            <div class="space">
                <Select not-found-text="No categories available" v-model="form.category_id" placeholder="Select Category" width="40px">
                    <Option 
                        v-for="(c, i) in categories"
                        :value="c.id"
                        :key="i"
                        >{{ c.category_name }}</Option
                    >
                </Select>
            <div class="space">
                <Input
                    type="text"
                    v-model="form.price"
                    placeholder="Price"
                />
            </div>
            </div>
                    <div class="space"></div>
                    <Upload 
                        ref="uploads"
                        :headers="{
                            'x-csrf-token': token,
                            'X-Requested-With': 'XMLHttpRequest'
                        }"
                        :on-success="handleSuccess"
                        :on-error="handleError"
                        :format="['jpg', 'jpeg', 'png']"
                        :max-size="2048"
                        :on-format-error="handleFormatError"
                        :on-exceeded-size="handleMaxSize"
                        type="drag"
                        action="/app/upload"
                    >
                        <div style="padding: 20px 0">
                            <Icon
                                type="ios-cloud-upload"
                                size="52"
                                style="color: #3399ff"
                            ></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>
                    <div class="demo-upload-list" v-if="form.image">
                        <img :src="`${form.image}`" />

                        <div class="demo-upload-list-cover">
                            <Icon
                                type="ios-trash-outline"
                                @click="deleteImage"
                            ></Icon>
                        </div>
                    </div>
            <div class="space"></div>

            <div slot="footer">
                <Button type="error" @click="closeModal">Close</Button>
                <Button
                    type="primary"
                    @click="addItem"
                    :disabled="isAdding"
                    :loading="isAdding"
                    >{{ isAdding ? "Adding..." : "Add Item" }}</Button
                >
            </div>
        </Modal>
        <!-- END OF ADDING  MODAL -->


                <!-- START OF ITEM EDITING MODAL -->
        <Modal
            v-model="editModal"
            title="Edit Item"
            :mask-closable="false"
            :closable="false"
        >
            <div class="space">
                <Input
                    type="text"
                    v-model="editData.item_name"
                    placeholder="Item Name"
                />
            </div>
            <div class="space"></div>
            <div class="space">
                <Input
                    type="text"
                    v-model="editData.item_description"
                    placeholder="Description"
                />
            </div>
            <div class="space">
                <Select not-found-text="No categories available" v-model="editData.category_id" placeholder="Select Category" width="40px">
                    <Option 
                        v-for="(c, i) in categories"
                        :value="c.id"
                        :key="i"
                        >{{ c.category_name }}</Option
                    >
                </Select>
            <div class="space">
                <Input
                    type="text"
                    v-model="editData.price"
                    placeholder="Price"
                />
            </div>
            </div>
                    <div class="space"></div>
                    <Upload 
                        ref="editDataUploads"
                        :headers="{
                            'x-csrf-token': token,
                            'X-Requested-With': 'XMLHttpRequest'
                        }"
                        :on-success="handleSuccess"
                        :on-error="handleError"
                        :format="['jpg', 'jpeg', 'png']"
                        :max-size="2048"
                        :on-format-error="handleFormatError"
                        :on-exceeded-size="handleMaxSize"
                        type="drag"
                        action="/app/upload"
                    >
                        <div style="padding: 20px 0">
                            <Icon
                                type="ios-cloud-upload"
                                size="52"
                                style="color: #3399ff"
                            ></Icon>
                            <p>Click or drag files here to upload</p>
                        </div>
                    </Upload>
                    <div class="demo-upload-list" v-if="editData.image">
                        <img :src="`${editData.image}`" />

                        <div class="demo-upload-list-cover">
                            <Icon
                                type="ios-trash-outline"
                                @click="deleteImage"
                            ></Icon>
                        </div>
                    </div>
            <div class="space"></div>

            <div slot="footer">
                <Button type="error" @click="closeEditModal">Close</Button>
                <Button
                    type="primary"
                    @click="editItem"
                    :disabled="isEditing"
                    :loading="isEditing"
                    >{{ isEditing ? "Editing..." : "Edit" }}</Button
                >
            </div>
        </Modal>
        <!-- END OF EDITING  MODAL -->

        <!-- ADD STOCK MODAL -->

            <Modal
        v-model="AddStockModal"
        :title="addStockObj.item_name"
        @on-ok="addStock"
        @on-cancel="closeStockModal">
            <div class="space">
                <Input
                    type="number"
                    v-model="addStockObj.quantity"
                    placeholder="Add to Stock here"
                />
            </div>
                        <div slot="footer">
                <Button type="error" @click="AddStockModal = false">Close</Button>
                <Button
                    type="primary"
                    @click="addStock"
                    >Submit</Button
                >
            </div>
    </Modal>
        <!-- END OF ADD STOCK MODAL -->

            <deleteModal></deleteModal>

    </div>
</template>

<script>
import deleteModal from "../components/deleteModal.vue";
import { mapGetters } from "vuex";
import pagination from 'laravel-vue-pagination'
export default {
    components: {
        deleteModal,
        pagination
    },
    data() {
        return {
            bgColor: "orange",
            position: "top-right",
            categories: [],
            items:{},
            addModal:false,
            AddStockModal: false,
            itemStockIndex: -1,
            addStockObj:{
                item_id: null,
                item_name: "",
                quantity: null
            },
            form:{
                item_name: "",
                item_description: "",
                price: null,
                image: "",
                category_id: null,
                stock: null,
            },
            editData: {
                item_name: "",
                item_description: "",
                price: null,
                image: "",
                category_id: null,
            },
            token: "",
            isAdding: false,
            isEditing: false ,
            editModal: false,
            deletingIndex: -1
        };
    },
        mounted(){
        this.list()
    },
    methods: {
        async list(page=1){
                await axios.get(`/app/get_items?page=${page}`).then(({data})=>{
                    this.items = data
                }).catch(({ response })=>{
                    console.error(response)
                })
            },
        showAddStockModal(item, i){
            this.AddStockModal = true
            let obj ={
                item_id: item.id,
                item_name: item.item_name,
                stock: item.stock,
                old_qty_left: item.qty_left
            }
            this.addStockObj = obj
            this.itemStockIndex = i
        },
        async addStock(){
            if (!this.addStockObj.quantity)
                return this.error("Quantity is required");
            this.addStockObj.quantity = parseInt(this.addStockObj.quantity)
            const res = await this.callApi(
                "post",
                "app/add_stock",
                this.addStockObj
            )
            if (res.status == 200) {
                console.log(res)
                this.AddStockModal = false
                this.items.data[this.itemStockIndex].stock = res.data.new_stock
                this.items.data[this.itemStockIndex].qty_left = res.data.new_qty_left
                this.success("Stock updated successfully")
            }        
        },
        closeStockModal(){
            this.AddStockModal = false
        },
        showDeletingModal(item, i) {
            console.log(item)
            this.deletingIndex = i;
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: "app/delete_item",
                data: item,
                deletingIndex: i,
                msg: "Are you sure you want to delete this item?",
                successMsg: "Item deleted successfully"
            };
            this.$store.commit("setDeletingModalObj", deleteModalObj);
            //console.log('delete method called ')
        },
        closeEditModal(){
            this.editModal = false
            this.isEditing = false
            this.isEditing =false
        },
        async editItem() {
            if (this.editData.item_name.trim() == "")
                return this.error("Item name is required");
            if (this.editData.item_description.trim() == "")
                return this.error("Item description is required");
            if (this.editData.image.trim() == "")
                return this.error("Image is required");
            if (!this.editData.price)
                return this.error("Price is required");
            if (!this.editData.category_id)
                return this.error("Category is required");

            this.isEditing = true;
            this.$Progress.start();
            const res = await this.callApi(
                "post",
                "app/edit_item",
                this.editData
            );
            if (res.status === 200) {
                this.items.data[this.editingIndex] = this.editData;
                this.success("Item edited successfully");
                this.editModal = false;
                this.$Progress.finish();
                this.isEditing = false;
                this.$refs.editDataUploads.clearFiles();
            }  else {
                if (res.status === 422) {
                    this.isEditing = false;
                    this.$Progress.fail();
                    for (let i in res.data.errors) {
                        this.error(res.data.errors[i][0]);
                    }
                } else {
                    this.isEditing = false;
                    this.$Progress.fail();
                    this.swr();
                }
            }
        },
        showEditItem(item, i){
                        let obj = {
        				id : item.id,
                        item_name: item.item_name,
                        item_description: item.item_description,
                        image: item.image,
                        price: item.price,
                        category_id: item.category_id,

                    }
                this.editData = obj;
                this.editModal = true;
                this.editingIndex = i;
        },
        async addItem(){
            if (this.form.item_name.trim() == "")
                return this.error("Item name is required");
            if (this.form.item_description.trim() == "")
                return this.error("Item description is required");
            if (this.form.image.trim() == "")
                return this.error("Image is required");
            if (!this.form.price)
                return this.error("Price is required");
            if (!this.form.category_id)
                return this.error("Category is required");

            this.isAdding = true;
            this.$Progress.start();
            const res = await this.callApi(
                "post",
                "app/create_item",
                this.form
            );
            if (res.status === 201) {
                this.isAdding = false;
                this.$Progress.finish();
                this.success("Food Item added successfully");
                this.items.data.unshift(res.data);
                this.addModal = false;
                this.form.item_name = "";
                this.form.item_description = "";
                this.form.image = "";
                this.form.price = "";
                this.form.category_id = "";
            } else {
                if (res.status === 422) {
                    this.isAdding = false;
                    this.$Progress.fail();
                    for (let i in res.data.errors) {
                        this.error(res.data.errors[i][0]);
                    }
                } else if (res.status === 500) {
                    this.isAdding = false;
                    //alert(res.status)
                    console.log(res.data);
                    this.$Progress.fail();
                    this.error(res.data.message);
                } else {
                    this.isAdding = false;
                    this.$Progress.fail();
                    this.swr();
                }
            }
        },
        closeModal(){
            if (this.form.image) {
                this.error("Delete the image first before canceling")
            }else{
            this.addModal = false
            }
        },
        handleSuccess(res, file) {
            res = `/uploads/${res}`
            if (this.isEditingItem) {
                return this.editData.image = res
            }
            this.form.image = res;
        },
        handleError(res, file) {
            this.$Notice.warning({
                title: "The file format is incorrect",
                desc: `${
                    file.errors.file.length
                        ? file.errors.file[0]
                        : "Something went wrong"
                }`
            });
        },
        handleFormatError(file) {
            this.$Notice.warning({
                title: "The file format is incorrect",
                desc:
                    "File format of " +
                    file.name +
                    " is incorrect, please select jpg or png."
            });
        },
        handleMaxSize(file) {
            this.$Notice.warning({
                title: "Exceeding file size limit",
                desc: "File  " + file.name + " is too large, no more than 2M."
            });
        },
        async deleteImage(isAdd=true) {
            let image
            if (!isAdd) { // for editing
                this.isIconImageNew = true
                image = this.editData.image;
                this.editData.image = "";
                this.$refs.editDataUploads.clearFiles(); 
            }else{
            image = this.form.image;
            this.form.image = "";
            this.$refs.uploads.clearFiles();
            }
            if (!isAdd) {
                
            }
            const res = await this.callApi("post", "app/delete_image", {
                image: image
            });
            if (res.status != 200) {
                this.form.image = image;
                this.swr();
            }
            console.log(this.form.image);
        },
        async getCategories() {
            const getCategories = await this.callApi(
                "get",
                "app/get_categories"
            );
            if ((getCategories.status = 201)) {
                this.categories = getCategories.data;
                console.log(getCategories.data);
            } else {
                this.swr();
            }
        },
        async getItems() {
            const getItems = await this.callApi(
                "get",
                "app/get_items"
            );
            if ((getItems.status = 200)) {
                this.items = getItems.data;
                console.log(getItems.data);
            } else {
                this.swr();
            }
        }
    },
    created() {
        this.token = window.Laravel.csrfToken;
        this.getCategories();
        this.getItems();
    },

    computed: {
        ...mapGetters(["getDeleteModalObj"])
    },
    watch: {
        getDeleteModalObj(obj) {
            console.log(obj);
            if (obj.isDeleted) {
                console.log(obj);
                this.editModal = false
                this.items.data.splice(this.deletingIndex, 1);
            }
        }
    }
};
</script>

<style scoped>

.active {
    z-index: 3;
    color: #fff;
    background-color: orange !important;
    border-color: orange !important;
}
</style>
