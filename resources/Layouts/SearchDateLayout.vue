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
        <div class="col-lg-6">
            <div class="row">
                <div class="col-lg-5">
                    <div class="input-group">
                    <span class="input-group-text date-group-text" id="basic-addon1">Start Date</span>
                    <input type="date" v-model="start" class="form-control date-range-input" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="input-group">
                    <span class="input-group-text date-group-text" id="basic-addon1">End Date</span>
                    <input type="date" v-model="end" class="form-control date-range-input" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                </div>
                <div class="col-lg-2">
                    <button class="btn btn-primary btn-sm date-range-search" type="button" @click.prevent="searchItem">
                        <i class="fas fa-calendar-check"></i> Select
                    </button>
                </div>
            </div>
        </div>
        <div class="col-lg-2">
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
            start: (this.data.filters.start == null) ? '2023-01-01' : this.data.filters.start,
            end: (this.data.filters.start == null) ? new Date().toISOString().split('T')[0] : this.data.filters.end,
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
            router.get(this.route(this.data.routeLink, { term: this.data.filters.term, show: this.show, start: this.start, end: this.end }), data, {
                onStart: () => {
                    this.loading = true;
                },
                onFinish: () => {
                    this.loading = true;
                },
            });
        }, 200),
    },
};

</script>
