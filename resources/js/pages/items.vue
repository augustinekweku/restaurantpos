<template>
    <div class="items_page container">
        <Button @click="addModal=true" class="add_fab" size="large" icon="ios-add" shape="circle"></Button>
        <div class="row gx-5 gy-2">
            <h2 class="text-center mb-3">Meal Items</h2>
            <div class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                <Select placeholder="Select Category" width="40px">
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
        <div class=" mt-2 row gx-5">
                <div v-for="(item, i) in items" :key="item.id" class="col-sm-12 col-md-4 col-lg-4 col-xl-4">
                            <div class>
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
                                            ></div>

                                            <div
                                                class="item-card__footer px-3 py-2 d-flex justify-content-between"
                                            >
                                                <div class="one-third">
                                                    <div >
                                                        ₵ {{ item.price }}
                                                    </div>
                                                </div>

                                                <div class="one-third">
                                                    <div >
                                                        <Tooltip content="Edit">
                                                            <Icon
                                                                @click="
                                                                    showEditItem(
                                                                        mealpackage,
                                                                        i
                                                                    )
                                                                "
                                                                type="md-create"
                                                            />
                                                        </Tooltip>
                                                    </div>
                                                </div>

                                                <div
                                                    class="one-third no-border"
                                                >
                                                    <div >
                                                        <Tooltip
                                                            content="Delete"
                                                        >
                                                            <Icon
                                                                @click="
                                                                    showDeletingModal(
                                                                        mealpackage,
                                                                        i
                                                                    )
                                                                "
                                                                type="ios-trash"
                                                            />
                                                        </Tooltip>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- end clash-card barbarian-->
                                    </div>
                                    <!-- end wrapper -->
                </div>
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
                    >{{ isAdding ? "Adding..." : "Add Category" }}</Button
                >
            </div>
        </Modal>
        <!-- END OF ADDING  MODAL -->
    </div>
</template>

<script>
import fab from "vue-fab";
export default {
    components: {
        fab
    },
    data() {
        return {
            bgColor: "orange",
            position: "top-right",
            categories: [],
            items:[],
            addModal:false,
            form:{
                item_name: "",
                item_description: "",
                price: null,
                image: "",
                category_id: null,
            },
            token: "",
            isAdding: false
        };
    },
    methods: {
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
                this.categories.unshift(res.data);
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
            if ((getItems.status = 201)) {
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
    }
};
</script>

<style scoped>
.add_fab {
    top: 140px !important;
    right:50px;
    background: orange;
    float: right;
}
</style>
