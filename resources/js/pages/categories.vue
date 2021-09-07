<template>
    <div class="categories animate__animated animate__fadeIn">
        <div class="d-flex justify-content-end ">
            <div class="add_button">
                <Button class="m-2 shadow" type="warning" @click="addModal = true">Add</Button>
            </div>
        </div>

        <div class>
            <div class="categories_table">
                <vue-good-table
                :columns="columns1"
                @on-row-dblclick="onRowDoubleClick"
                    :rows="categories"
                    :line-numbers="true"
                    >
                </vue-good-table>
            </div>
        </div>
        <!-- CATEGORY adding modal -->
        <Modal
            v-model="addModal"
            title="Add Category"
            :mask-closable="false"
            :closable="false"
        >
            <div class="space">
                <Input
                    type="text"
                    v-model="form.category_name"
                    placeholder="Category Name"
                />
            </div>
            <div class="space">
                <Input
                    type="text"
                    v-model="form.desc"
                    placeholder="Description"
                />
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
                <Button type="error" @click="addModal = false">Close</Button>
                <Button
                    type="primary"
                    @click="addCategory"
                    :disabled="isAdding"
                    :loading="isAdding"
                    >{{ isAdding ? "Adding..." : "Add Category" }}</Button
                >
            </div>
        </Modal>

    <!--  CATEGORY EDITING MODAL -->
        <Modal
            v-model="editModal"
            title="Edit Category"
            :mask-closable="false"
            :closable="false"
        >
            <div class="space">
                <Input
                    type="text"
                    v-model="editData.category_name"
                    placeholder="First Name"
                />
            </div>
            <div class="space">
                <Input
                    readonly
                    type="text"
                    v-model="editData.desc"
                    placeholder="Description"
                />
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
                                @click="deleteImage(false)"
                            ></Icon>
                        </div>
                    </div>
            <div class="space"></div>

            <div slot="footer">
                <Button type="error" @click="closeEditModal">Close</Button>
                <Button
                    type="primary"
                    @click="editCategory"
                    :disabled="isEditing"
                    :loading="isEditing"
                    >{{ isEditing ? "Editing..." : "Edit Category" }}</Button
                >
                                <div class="text-center more_actions my-2">
<Collapse v-model="value1">
        <Panel name="1">
            More Actions
            <p slot="content">
                <Button type="error" @click="showDeletingModal">Delete Category</Button>
            </p>
        </Panel>
    </Collapse>
    </div>
            </div>
        </Modal>
    <!-- END OF CATEGORY EDITING MODAL -->


        <deleteModal></deleteModal>
    </div>
</template>
<script>
import deleteModal from "../components/deleteModal.vue";
import { mapGetters } from "vuex";

export default {
    components: {
        deleteModal
    },
    data() {
        return {
            value1: '',
            addModal: false,
            isAdding: false,
            token: '',
            form: {
                category_name: "",
                desc: "",
                image: ""
            },
            editData: {
                category_name: "",
                desc: "",
                image: ""
            },
            columns1: [
                {
                    label: "Name",
                    field: "category_name"
                },
                {
                    label: "Image",
                    field: this.ImgFn,
                    html: true,
                    tdClass: 'category_image',
                },
                {
                    label: "Description",
                    field: "desc"
                },
            ],
            categories: [],
            selectedRow: [],
            selectedIndex: 0,
            editModal: false,
            isEditing: false,
            isEditingItem: false
        };
    },
    methods: {
        closeEditModal(){
            this.editModal = false
            this.isEditingItem = false
            this.isEditing =false
        },
        ImgFn(RowObj) {
            return `<img height="40px"  style="width:50px; object-fit:cover " src="${RowObj.image}" />`
        },
        onRowDoubleClick(params) {
            // params.row - row object
            // params.pageIndex - index of this row on the current page.
            // params.selected - if selection is enabled this argument
            // indicates selected or not
            // params.event - click event
            let obj = {
        				id : params.row.id,
                        category_name: params.row.category_name,
                        desc: params.row.desc,
                        image: params.row.image,

                    }
        			this.editData = obj
                    this.editModal = true
                    this.selectedIndex = params.row.pageIndex
                    this.selectedRow = params.row
                    this.isEditingItem = true

          },
        showDeletingModal() {
            const deleteModalObj = {
                showDeleteModal: true,
                deleteUrl: "app/delete_category",
                data: this.selectedRow,
                isDeleted: false,
                msg: "Are you sure you want to delete this category?",
                successMsg: "Category deleted successfully"
            };
            this.$store.commit("setDeletingModalObj", deleteModalObj);
            //console.log('delete method called ')
        },
        async editCategory() {
            if (this.editData.category_name.trim() == "")
                return this.error("Category name is required");
            if (this.editData.desc.trim() == "")
                return this.error("Description is required");
            if (this.editData.image.trim() == "")
                return this.error("Imgae is required");

            this.isEditing = true;
            this.$Progress.start();
            const res = await this.callApi(
                "post",
                "app/edit_category",
                this.editData
            );
            if (res.status === 200) {
                console.log(this.editData);
                this.getCategories();
                this.isEditing = false;
                this.$Progress.finish();
                this.success("Category edited successfully");
                //console.log(this.editData)
                this.editModal = false;
                this.isEditingItem = false
            } else {
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
        async addCategory() {
            if (this.form.category_name.trim() == "")
                return this.error("Category name is required");
            if (this.form.desc.trim() == "")
                return this.error("Description is required");
            if (this.form.image.trim() == "")
                return this.error("Image is required");

            this.isAdding = true;
            this.$Progress.start();
            const res = await this.callApi(
                "post",
                "app/create_category",
                this.form
            );
            if (res.status === 201) {
                this.isAdding = false;
                this.$Progress.finish();
                this.success("Category added successfully");
                this.categories.unshift(res.data);
                this.addModal = false;
                this.form.category_name = "";
                this.form.desc = "";
                this.form.image = "";
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
            const getCategories = await this.callApi("get", "app/get_categories");
            if ((getCategories.status = 201)) {
                this.categories = getCategories.data;
                //console.log(getCategories.data);
            } else {
                this.swr();
            }
        }
    },
    async created() {
        //setting the CSRF token
        this.token = window.Laravel.csrfToken;
        const getCategories = await this.callApi("get", "app/get_categories");
        if ((getCategories.status = 201)) {
            this.categories = getCategories.data;
            console.log(getCategories.data);
        } else {
            this.swr();
        }
    },
    computed: {
        ...mapGetters(["getDeleteModalObj"])
    },
    watch: {
        getDeleteModalObj(obj) {
            console.log(obj);
            if (obj.isDeleted) {
                console.log(obj);
                this.getCategories();
                this.editModal = false
            }
        }
    }
};
</script>

<style scoped>

.category_image{
    width: 50px;
}
img{
    width: auto !important;
}
.categories {
    margin: 0;
    padding: 15px;
}
.add_button {
}
.space {
    margin: 10px 0px;
}

</style>
