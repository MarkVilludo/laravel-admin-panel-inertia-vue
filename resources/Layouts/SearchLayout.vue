<template>
    <div class="row search-row">
        <div class="col-lg-4">
            <div class="input-group input-group-sm mb-2">
                <input type="text" class="form-control" placeholder="Search" v-model="data.filters.term"
                    @keyup.enter="searchItem">
                <button class="btn btn-primary btn-search" type="button" id="button-addon2" v-tippy="'Search'"
                    @click.prevent="searchItem">&nbsp;<i class="fas fa-search"></i>&nbsp;</button>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="show-items-container"> Show
                <select class="show-items" v-model="show" @change="searchItem">
                    <option value="15">15</option>
                    <option value="30">30</option>
                    <option value="45">45</option>
                    <option value="60">60</option>
                    <option value="75">75</option>
                    <option value="90">90</option>
                    <option value="">All</option>
                </select> Entries
            </div>
        </div>
    </div>

    <LoadingLayout v-if="loading" />
</template>

<script>
import { router } from "@inertiajs/vue3";
import LoadingLayout from "./LoadingLayout.vue";
import _ from 'lodash';

export default {
    data() {
        return {
            loading: false,
            show: (this.data.filters.show == null) ? 15 : this.data.filters.show,
        }
    },
    props: {
        data: Object,
    },
    components: {
        LoadingLayout,
    },
    methods: {
        searchItem: _.throttle(function (data) {
            const routeParams = {
                term: this.data.filters.term,
                show: this.show
            };

            if (this.data.filters.id) {
                routeParams.id = this.data.filters.id;
            }

            const finalUrl = this.route(this.data.routeLink, routeParams);

            router.get(finalUrl, data, {
                onStart: () => {
                    this.loading = true;
                },
                onFinish: () => {
                    this.loading = false; // Corrected loading state to false when request finishes
                },
            });
        }, 200),
    },
};

</script>
