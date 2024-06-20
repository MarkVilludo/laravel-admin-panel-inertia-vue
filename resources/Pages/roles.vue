<style scoped>
.list-action-container i {
    margin: 0px 5px;
    cursor: pointer;
}

.invalid-feedback {
    font-size: .775em;
}

.permission-card-wrap {
    background: #eee;
    border-radius: 4.1px;
    padding: 5px 5px 5px 5px;
    position: relative;
    border: 1px dashed #ccc;
}

.permission-card-title {
    font-weight: 500;
}

.div-permission-chkbox-container {
    overflow-y: auto;
    max-height: 500px;
}
</style>

<template>

    <Head title="Roles" />
    <AppLayout page_title="Roles" page_icon="fas fa-user-lock">
        <div class="container-fluid">

            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">System Modules</a></li>
                    <li class="breadcrumb-item active">Roles</li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow mb-4">

                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list-ol"></i> Roles
                                List</h6>
                            <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
                                @click.prevent="resetForm"><i class="fas fa-plus-square fa-sm text-white-50"></i> New
                                Role</button>
                        </div>

                        <div class="card-body">

                            <SearchLayout :data="{
                                    routeLink: 'roles.index',
                                    filters: filters,
                                }" />

                            <table class="table table-striped table-sm">
                                <colgroup>
                                    <col width="1%">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Guard Name</th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in roles.data  " :key="item.id">
                                        <td class="text-center">
                                            {{ roles.from + index }}
                                        </td>
                                        <td>{{ item.name }}</td>
                                        <td>{{ item.guard_name }}</td>
                                        <td class="list-status-container text-center">
                                            <input class="form-check-input" type="checkbox" :checked="item.status == 1"
                                                @click.prevent="formAction(item, 'status')">
                                        </td>
                                        <td class="list-action-container text-center">
                                            <i class="fas fa-eye text-info" v-tippy="'View'"
                                                @click.prevent="selectAction(item, 'show', null)"></i>
                                            <i class="fas fa-edit text-success" v-tippy="'Edit'"
                                                @click.prevent="selectAction(item, 'update', 'all')"></i>
                                            <i class="fa fa-trash text-danger" v-tippy="'Delete'"
                                                @click.prevent="selectAction(item, 'delete', null)"></i>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <PaginationLayout :data="{
                                    links: roles.links,
                                    from: roles.from,
                                    to: roles.to,
                                    total: roles.total,
                                }" />

                        </div>
                    </div>
                </div>
            </div>

        </div>

        <transition name="modal-fade">
            <div class="modal custom-modal" v-if="modalShow">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="bi bi-arrow-return-right"></i> {{ (!editMode && !viewMode) ? 'new_role' :
                                    (editMode ? 'Update Role' : 'Show Role')
                                }}
                            </h5>
                            <button type="button" class="btn-close" @click.prevent="closeModal"></button>
                        </div>
                        <form @submit.prevent="formAction(form, 'all')">
                            <div class="modal-body">
                                <div class="row gx-4">
                                    <div class="col-lg-12">
                                        <div class="row mb-2">
                                            <label for="name" class="col-sm-4 col-form-label">{{ 'name' }}:</label>
                                            <div class="col-sm-8">
                                                <input id="name" name="name" v-model="form.name" type="text"
                                                    :class="`form-control ${error_form.name ? 'is-invalid' : ''}`"
                                                    autocomplete="off" />
                                                <div class="invalid-feedback" v-if="error_form.name">
                                                    {{ error_form.name }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="status" class="col-sm-4 col-form-label mh-500">{{ 'status'
                                                }}:</label>
                                            <div class="col-sm-8">
                                                <select class="form-select" aria-label="Default select example"
                                                    id="status" name="status" v-model="form.status">
                                                    <option selected>{{ 'select_status' }}</option>
                                                    <option value="1">{{ 'enable' }}</option>
                                                    <option value="0">{{ 'disable' }}</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="name" class="col-sm-4 col-form-label">{{ 'roles'
                                                }}:</label>
                                            <div class="col-sm-8 div-permission-chkbox-container">
                                                <div class="form-check mb-1">
                                                    <input :id="'id_all'" class="form-check-input" type="checkbox"
                                                        @click="handClickAll(form.isAll)" v-model="form.isAll"
                                                        value="true">
                                                    <label class="form-check-label" :for="'id_all'">
                                                        {{ 'all' }}
                                                    </label>
                                                </div>
                                                <div class="permission-card-wrap mb-2"
                                                    v-for="(moduleName, key) in modules" :key="key">
                                                    <div class="row">
                                                        <div class="col-sm-12 ">
                                                            <div class="row">
                                                                <div
                                                                    class="col-sm-12 d-flex align-items-center gap-2 mb-3">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        v-model="isAll[moduleName]"
                                                                        @click="handClickSubAll(isAll[moduleName], moduleName)"
                                                                        :id="`isAll_${moduleName}`">
                                                                    <label class="mt-2 permission-card-title"
                                                                        :for="`isAll_${moduleName}`"> {{ moduleName }}
                                                                    </label>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-sm-6" v-for="(item) in permissions"
                                                                    :key="item.id"
                                                                    v-show="checkSameModules(item, moduleName)">
                                                                    <div class="form-check">
                                                                        <input :id="'id_' + item.id"
                                                                            class="form-check-input" type="checkbox"
                                                                            v-model="form.selectedOptions"
                                                                            :value="item.name">
                                                                        <label class="form-check-label"
                                                                            :for="'id_' + item.id">
                                                                            {{ item.name }}
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click.prevent="closeModal">
                                    <i class="fas fa-times-circle"></i> {{ 'close' }}
                                </button>
                                <template v-if="action !== 'show'">
                                    <button type="submit" class="btn btn-custom" v-if="action === 'new'">
                                        <i class="far fa-save"></i> {{ 'save' }}
                                    </button>
                                    <button type="submit" class="btn btn-custom" v-if="action === 'update'">
                                        <i class="fas fa-save"></i> {{ 'update' }}
                                    </button>
                                </template>
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
        mixins: [CommonMixin],
        data() {
            return {
                loading: false,
                modalShow: false,
                viewMode: false,
                editMode: false,
                formShow: false,
                action: "new",
                form: {
                    name: null,
                    status: 1,
                    selectedOptions: [],
                    isAll: false
                },

                original_form: {},
                error_form: {},
                modules: [],
                isAll: {},
            }
        },
        props: {
            agents: Object,
            roles: Object,
            permissions: Object,
            filters: Object,
            errors: Object,
            response: null,
            query_params: Array
        },
        components: {
            Head, Link, AppLayout, SearchLayout, PaginationLayout, LoadingLayout,
        },
        created() {
            window.addEventListener('keydown', this.escape);

            this.modules = [...new Set(this.permissions.map((p) => p.module))];
        },
        methods: {
            closeModal() {
                this.modalShow = false;
            },
            resetForm() {
                this.modalShow = !this.modalShow;
                this.action = 'new';
                this.form = {
                    name: null,
                    status: 1,
                    selectedOptions: [],
                }
            },
            selectAction(data, action, type) {
                this.action = action;
                if (this.action == 'delete') {
                    if (type === 'multiple' && data.length <= 0) {
                        toastr.error('unexpected_error');
                        return false;
                    }

                    this.formAction(data, 'delete');
                } else {
                    this.form = Object.assign(this.form, data);
                    this.form.selectedOptions = data.permissions.map(item => item.name)
                    this.original_form = Object.assign({}, data);
                    this.original_form.selectedOptions = data.permissions.map(item => item.name)
                    this.editMode = action === 'update' ? true : false;
                    this.viewMode = action === 'show' ? true : false;
                    this.modalShow = true;
                }
            },
            formAction(data, type) {

                let method, url, ask, message;
                switch (type) {
                    case 'status':
                        method = 'PUT';
                        url = 'roles.status';
                        ask = 'Are you sure you want to change the status?';
                        message = 'Status has been updated.';
                        break;
                    case 'delete':
                        method = 'DELETE';
                        url = 'roles.destroy';
                        ask = 'Are you sure you want to delete this item?';
                        message = 'Item has been deleted.';
                        break;
                    default:
                        method = this.editMode ? 'PUT' : 'POST';
                        url = this.editMode ? 'roles.update' : 'roles.store';
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

                        data.query_params = this.query_params;
                        data.update_type = type;
                        if (this.editMode && type !== 'status') {
                            let isSame = this.compareObjects(data, this.original_form, ['name', 'status', 'selectedOptions']);

                            if (isSame) {
                                toastr.error(`Nothing is changed.`);
                                return false;
                            }
                        }

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
                                    this.modalShow = false;
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
                                    toastr.error('unexpected_error');
                                }
                            },
                        });
                    }
                });
            },
            escape(event) {
                if (event.keyCode === 27) {
                    this.modalShow = false;
                }
            },
            checkSameModules(item, moduleName) {
                return item.module == moduleName;
            },
            handClickAll(isAllVal) {
                console.log(123);
                let currentIsAll = !isAllVal;
                if (currentIsAll === true) {
                    const allNames = [...new Set(this.permissions.map((p) => p.name))];
                    this.form.selectedOptions = allNames;
                } else {
                    this.form.selectedOptions = [];
                }
            },
            handClickSubAll(isSubAll, moduleName) {
                isSubAll = !isSubAll;
                let permissionFilterModule = this.permissions
                    .filter((p) => p.module === moduleName)
                    .map((p) => p.name);

                if (isSubAll) {
                    // console.log(permissionFilterModule, "permissionFilterModuleXXXXXXXXXXXX");
                    this.form.selectedOptions = [...new Set([...this.form.selectedOptions, ...permissionFilterModule])];
                } else {
                    // Remove permissionFilterModule values from selectedOptions
                    this.form.selectedOptions = this.form.selectedOptions.filter(option => !permissionFilterModule.includes(option));
                }
            },
        },
        watch: {
            modalShow: function (oldVal, newVal) {
                this.error_form = {};

                if (this.permissions.length > 0 && this.form.selectedOptions.length === this.permissions.length) {
                    this.form.isAll = true;
                }
            },
            'form.selectedOptions': function (oldVal, newVal) {
                if (this.permissions.length > 0 && this.form.selectedOptions.length === this.permissions.length) {
                    this.form.isAll = true;
                } else {
                    this.form.isAll = false;
                }

                for (const key in this.modules) {
                    let moduleName = this.modules[key];
                    let filter1 = this.permissions
                        .filter((p) => p.module === moduleName)
                        .map((p) => p.name);

                    let allExist = filter1.every(item => this.form.selectedOptions.includes(item));

                    this.isAll[moduleName] = allExist;
                }
            },

        }
    };

</script>
