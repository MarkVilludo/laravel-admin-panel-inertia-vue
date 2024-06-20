<template>
    <Head title="User Management" />
    <AppLayout page_title="User Management" page_icon="fas fa-users-cog">
        <div class="container-fluid">

            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">System Modules</a></li>
                    <li class="breadcrumb-item active">User Management</li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow mb-4">

                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list-ol"></i> User List</h6>
                            <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                @click.prevent="resetForm"><i class="fas fa-plus-square fa-sm text-white-50"></i> New
                                User</button>
                        </div>

                        <div class="card-body">

                            <SearchLayout :data="{
                                    routeLink: 'user-management.index',
                                    filters: filters,
                                }" />

                            <table class="table table-striped table-sm">
                                <colgroup>
                                    <col width="1%">
                                    <col width="*">
                                    <col width="*">
                                    <col width="1%">
                                    <col width="10%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email Address</th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in   users.data  " :key="item.id">
                                        <td class="text-center">
                                            {{ users.from + index }}
                                        </td>
                                        <td>{{ item.name }}</td>
                                        <td>{{ item.email }}</td>
                                        <td class="list-status-container text-center">
                                            <input class="form-check-input" type="checkbox" :checked="item.status == 1"
                                                @click.prevent="formAction(item, 'status')">
                                        </td>
                                        <td class="list-action-container text-center">
                                            <div class="btn-group">
                                                <button class="btn btn-action dropdown-toggle" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    Select
                                                </button>
                                                <ul class="dropdown-menu drop-action">
                                                    <li @click.prevent="selectAction(item, 'show')"><span
                                                            class="dropdown-item" href="#"><i class="far fa-eye"></i>
                                                            View</span></li>
                                                    <li @click.prevent="selectAction(item, 'edit')"><span
                                                            class="dropdown-item" href="#"><i
                                                                class="fas fa-pencil-alt"></i>
                                                            Edit</span></li>
                                                    <li @click.prevent="selectAction(item, 'delete')"><span
                                                            class="dropdown-item text-danger" href="#"><i
                                                                class="far fa-trash-alt"></i> Delete</span></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <PaginationLayout :data="{
                                    links: users.links,
                                    from: users.from,
                                    to: users.to,
                                    total: users.total,
                                }" />

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <transition name="modal-fade">
            <div class="modal custom-modal" v-if="formShow">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-cpu"></i>
                                {{ editMode ? 'Edit User' : viewMode ? 'View User' : 'New User' }}
                            </h5>
                            <button type="button" class="btn-close" @click.prevent="closeForm"
                                v-tippy="'Close'"></button>
                        </div>
                        <form @submit.prevent="formAction(form, 'all')">
                            <div class="modal-body">
                                <div class="row gx-4" v-if="!viewMode">
                                    <div class="col-lg-12">
                                        <div class="row mb-2">
                                            <label for="name" class="col-sm-4 col-form-label">Name :</label>
                                            <div class="col-sm-8">
                                                <input id="name" name="name" v-model="form.name" type="text"
                                                    class="form-control" :class="(error_form.name) ? 'invalid' : ''"
                                                    placeholder="Enter Name" autocomplete="off" />
                                                <div class="invalid-feedback" v-if="errors.name">{{ errors.name }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="email" class="col-sm-4 col-form-label">Email Address :</label>
                                            <div class="col-sm-8">
                                                <input id="email" name="email" v-model="form.email" type="text"
                                                    class="form-control" :class="(error_form.email) ? 'invalid' : ''"
                                                    placeholder="Enter Email Address" autocomplete="off" />
                                                <div class="invalid-feedback" v-if="errors.email">{{ errors.email
                                                    }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="password" class="col-sm-4 col-form-label">Password :</label>
                                            <div class="col-sm-8">
                                                <input id="password" name="password" v-model="form.password"
                                                    type="password" class="form-control"
                                                    :class="(error_form.password) ? 'invalid' : ''"
                                                    placeholder="Enter Password" autocomplete="off" />
                                                <div class="invalid-feedback" v-if="errors.password">{{
                                    errors.password }}</div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="password_confirmation" class="col-sm-4 col-form-label">Confirm
                                                Password :</label>
                                            <div class="col-sm-8">
                                                <input id="password_confirmation" name="password_confirmation"
                                                    v-model="form.password_confirmation" type="password"
                                                    class="form-control"
                                                    :class="(error_form.password_confirmation) ? 'invalid' : ''"
                                                    placeholder="Enter Confirm Password" autocomplete="off" />
                                                <div class="invalid-feedback" v-if="errors.password_confirmation">{{ errors.password_confirmation }}</div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="role" class="col-sm-4 col-form-label">Role :</label>
                                            <div class="col-sm-8">
                                                <select class="form-select" aria-label="Default select example"
                                                    id="role" name="role" v-model="form.role">
                                                    <option v-for=" item  in  roles " :key="item.id" :value="item.name">
                                                        {{ item.name }}</option>
                                                </select>
                                                <div class="invalid-feedback" v-if="errors.role">{{ errors.role }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="status" class="col-sm-4 col-form-label">Status :</label>
                                            <div class="col-sm-8">
                                                <select class="form-select" aria-label="Default select example"
                                                    id="status" name="status" v-model="form.status">
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                                <div class="invalid-feedback" v-if="errors.status">{{ errors.status
                                                    }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive" v-if="viewMode">
                                    <table class="table table-striped table-bordered">
                                        <colgroup>
                                            <col width="35%">
                                            <col width="*">
                                        </colgroup>
                                        <tbody>
                                            <tr>
                                                <td>Name</td>
                                                <td>{{ form.name }}</td>
                                            </tr>
                                            <tr>
                                                <td>Email Address</td>
                                                <td>{{ form.email }}</td>
                                            </tr>
                                            <tr>
                                                <td>Role</td>
                                                <td>{{ form.role }}</td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>{{ (form.status == 1) ? 'Active' : 'Inactive' }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click.prevent="closeForm">
                                    <i class="far fa-times-circle"></i> Close
                                </button>
                                <button type="submit" class="btn btn-primary" v-if="!viewMode">
                                    <i class="fas fa-save"></i> {{ (editMode) ? 'Update' : 'Save' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </transition>

    </AppLayout>
</template>

<script>
  import { Head, Link, router } from "@inertiajs/vue3";
  import AppLayout from "@/Layouts/AppLayout.vue";
  import SearchLayout from "@/Layouts/SearchLayout.vue";
  import PaginationLayout from "@/Layouts/PaginationLayout.vue";
  import LoadingLayout from "@/Layouts/LoadingLayout.vue";
  import toastr from "toastr";

export default {
    data() {
        return {
            loading: false,
            viewMode: false,
            editMode: false,
            formShow: false,
            action: "new",
            form: {
                name: null,
                email: null,
                password: null,
                password_confirmation: null,
                role: "Administrator",
                status: 1,
            },
            error_form: {},
        }
    },
    props: {
        users: Object,
        roles: Object,
        filters: Object,
        errors: Object,
        response: null,
    },
    components: {
        Head, Link, AppLayout, SearchLayout, PaginationLayout, LoadingLayout,
    },
    methods: {
        closeForm() {
            this.formShow = false;
            this.form = {
                name: null,
                email: null,
                password: null,
                password_confirmation: null,
                role: "Administrator",
                status: 1,
            };
            this.errors.name = null;
            this.errors.email = null;
            this.errors.password = null;
        },
        resetForm() {
            this.viewMode = false,
                this.editMode = false,
                this.formShow = !this.formShow;
        },
        selectAction(data, action) {
            if (action === "delete") {
                this.formAction(data, 'delete');
            } else {
                this.form = { ...data };
                this.form.role = data.roles[0]?.name || '';
                this.formShow = true;
                this.viewMode = action === "show";
                this.editMode = action !== "show";
            }
        },
        formAction(data, type) {
            let method, url, ask, message;
            switch (type) {
                case 'status':
                    method = 'PUT';
                    url = 'user-management.status';
                    ask = 'Are you sure you want to change the status?';
                    message = 'Status has been updated.';
                    break;
                case 'delete':
                    method = 'DELETE';
                    url = 'user-management.destroy';
                    ask = 'Are you sure you want to delete this item?';
                    message = 'Item has been deleted.';
                    break;
                default:
                    method = this.editMode ? 'PUT' : 'POST';
                    url = this.editMode ? 'user-management.update' : 'user-management.store';
                    ask = `Are you sure you want to ${this.editMode ? 'update' : 'save'} this item?`;
                    message = this.editMode ? 'Work has been updated.' : 'Work has been saved.';
            }
            this.$swal({
                text: ask,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: (type !== 'delete') ? '#512da8' : '#D81B60',
                cancelButtonText: 'No <i class="bi bi-hand-thumbs-down"></i>',
                confirmButtonText: '<i class="bi bi-hand-thumbs-up"></i> Yes'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    data._method = method;
                    router.post(route(url, data.id), data, {
                        onBefore: () => {
                            this.loading = true;
                        },
                        onSuccess: (response) => {
                            if (response.props.response == 'success') {
                                this.$swal({
                                    position: 'center',
                                    icon: 'success',
                                    text: message,
                                    showConfirmButton: false,
                                    timer: 2000,
                                });
                                this.formShow = false;
                            }
                        },
                        onFinish: () => {
                            this.loading = false;
                        },
                        onError: (error) => {
                            try {
                                this.error_form = Object.assign(this.error_form, error);
                                Object.entries(error).forEach(([field, message]) => {
                                    toastr.error(`${message}`);
                                });
                            } catch (err) {
                                toastr.error(this.$t('unexpected_error'));
                            }
                        },
                    });
                }
            });
        }
    },
    watch: {
        modalShow: function (oldVal, newVal) {
            this.error_form = {};

            // if (this.permissions.length > 0 && this.form.selectedOptions.length === this.permissions.length) {
            //     this.form.isAll = true;
            // }
        },
        // 'form.selectedOptions': function (oldVal, newVal) {
        //     if (this.permissions.length > 0 && this.form.selectedOptions.length === this.permissions.length) {
        //         this.form.isAll = true;
        //     } else {
        //         this.form.isAll = false;
        //     }
        // },

    }
};

</script>
