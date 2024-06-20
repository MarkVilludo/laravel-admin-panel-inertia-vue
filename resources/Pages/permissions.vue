<template>

    <Head title="Permissions" />
    <AppLayout page_title="Permissions" page_icon="fas fa-user-lock">
        <div class="container-fluid">

            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">System Modules</a></li>
                    <li class="breadcrumb-item active">Permissions</li>
                </ol>
            </nav>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow mb-4">

                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list-ol"></i> Permissions
                                List</h6>
                        </div>

                        <div class="card-body">

                            <SearchLayout :data="{
                                    routeLink: 'permissions.index',
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
                                        <th scope="col">Module</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in   permissions.data  " :key="item.id">
                                        <td class="text-center">
                                            {{ permissions.from + index }}
                                        </td>
                                        <td>{{ item.name }}</td>
                                        <td>{{ item.module }}</td>
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
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <PaginationLayout :data="{
                                    links: permissions.links,
                                    from: permissions.from,
                                    to: permissions.to,
                                    total: permissions.total,
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
                                {{ editMode ? 'Edit Permissions' : viewMode ? 'View Permissions' : 'New Permissions' }}
                            </h5>
                            <button type="button" class="btn-close" @click.prevent="closeForm"
                                v-tippy="'Close'"></button>
                        </div>
                        <form @submit.prevent="formAction(form, 'all')">
                            <div class="modal-body">
                                <div class="row gx-4" v-if="!viewMode">
                                    <div class="col-lg-12">
                                        <div class="row mb-2">
                                            <label for="agent_id" class="col-sm-4 col-form-label">Agent :</label>
                                            <div class="col-sm-8">
                                                <select class="form-select" aria-label="Default select example"
                                                    id="agent_id" name="agent_id" v-model="form.agent_id">
                                                    <option :value="agent.name" v-for="agent in agents">{{agent.name + ' - ' +agent.app_key}}</option>
                                                </select>
                                                <div class="invalid-feedback" v-if="error_form.status">{{ error_form.status }}</div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="redis_channel_expiry" class="col-sm-4 col-form-label">Channel Expiry ( Redis ) :</label>
                                            <div class="col-sm-8">
                                                <textarea id="redis_channel_expiry" name="redis_channel_expiry" v-model="form.redis_channel_expiry"
                                                    class="form-control" :class="(error_form.redis_channel_expiry) ? 'invalid' : ''"
                                                    placeholder="Enter redis_channel_expiry" autocomplete="off" rows="5"></textarea>
                                                <div class="invalid-feedback" v-if="error_form.redis_channel_expiry">{{ error_form.redis_channel_expiry }}</div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="session_channel_expiry" class="col-sm-4 col-form-label">Channel Expiry ( Session ) :</label>
                                            <div class="col-sm-8">
                                                <textarea id="session_channel_expiry" name="session_channel_expiry" v-model="form.session_channel_expiry"
                                                    class="form-control" :class="(error_form.session_channel_expiry) ? 'invalid' : ''"
                                                    placeholder="Enter session_channel_expiry" autocomplete="off" rows="5"></textarea>
                                                <div class="invalid-feedback" v-if="error_form.session_channel_expiry">{{ error_form.session_channel_expiry }}</div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="check_customer_online" class="col-sm-4 col-form-label">Customer online :</label>
                                            <div class="col-sm-8">
                                                <textarea id="check_customer_online" name="check_customer_online" v-model="form.check_customer_online"
                                                    class="form-control" :class="(error_form.check_customer_online) ? 'invalid' : ''"
                                                    placeholder="Enter check_customer_online" autocomplete="off" rows="5"></textarea>
                                                <div class="invalid-feedback" v-if="error_form.check_customer_online">{{ error_form.check_customer_online }}</div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="is_screenname_faker" class="col-sm-4 col-form-label">Is Screen Name Faker :</label>
                                            <div class="col-sm-8">
                                                <textarea id="is_screenname_faker" name="is_screenname_faker" v-model="form.is_screenname_faker"
                                                    class="form-control" :class="(error_form.is_screenname_faker) ? 'invalid' : ''"
                                                    placeholder="Enter is_screenname_faker" autocomplete="off" rows="5"></textarea>
                                                <div class="invalid-feedback" v-if="error_form.is_screenname_faker">{{ error_form.is_screenname_faker }}</div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="default_profile_pic" class="col-sm-4 col-form-label">Default Profile Pic :</label>
                                            <div class="col-sm-8">
                                                <textarea id="default_profile_pic" name="default_profile_pic" v-model="form.default_profile_pic"
                                                    class="form-control" :class="(error_form.default_profile_pic) ? 'invalid' : ''"
                                                    placeholder="Enter default_profile_pic" autocomplete="off" rows="5"></textarea>
                                                <div class="invalid-feedback" v-if="error_form.default_profile_pic">{{ error_form.default_profile_pic }}</div>
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
                                            <tr v-for="field in Object.keys(form)" :key="field">
                                                <td>{{ field }}</td>
                                                <td>{{ form[field] }}</td>
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
import AppLayout from "@/AdminLayouts/AppLayout.vue";
import SearchLayout from "@/AdminLayouts/SearchLayout.vue";
import PaginationLayout from "@/AdminLayouts/PaginationLayout.vue";
import LoadingLayout from "@/AdminLayouts/LoadingLayout.vue";
import toastr from 'toastr';

export default {
    data() {
        return {
            loading: false,
            viewMode: false,
            editMode: false,
            formShow: false,
            action: "new",
            form: {
                agent_id: this.query_params.agentId,
                message: null,
                status: 1,
            },
            error_form: {},
        }
},
    props: {
        agents: Object,
        permissions: Object,
        filters: Object,
        errors: Object,
        response: null,
        query_params : Array
    },
    components: {
        Head, Link, AppLayout, SearchLayout, PaginationLayout, LoadingLayout,
    },
    methods: {
        closeForm() {
            this.formShow = false;
            this.form = {
                agent_id: null,
                message: null,
                status: 1,
            };
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
                    url = 'permissions.status';
                    ask = 'Are you sure you want to change the status?';
                    message = 'Status has been updated.';
                    break;
                case 'delete':
                    method = 'DELETE';
                    url = 'permissions.destroy';
                    ask = 'Are you sure you want to delete this item?';
                    message = 'Item has been deleted.';
                    break;
                default:
                    method = this.editMode ? 'PUT' : 'POST';
                    url = this.editMode ? 'permissions.update' : 'permissions.store';
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
        },

    }
};

</script>
