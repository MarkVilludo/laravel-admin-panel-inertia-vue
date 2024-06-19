<template>
    <div class="row pagination-row">
        <div class="col-lg-6">
            <p class="show-entries" v-show="data.total > 0">
                Showing {{ data.from }} to {{ data.to }} of {{ data.total }} items
            </p>
        </div>
        <div class="col-lg-6">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm justify-content-end">
                    <template v-for="(link, k) in data.links" :key="k">
                        <li v-if="link.url === null" class="page-item">
                            <a class="page-link">
                                <i class="fas fa-angle-double-left" v-if="link.label.includes('Previous')"></i>
                                <i class="fas fa-angle-double-right" v-else-if="link.label.includes('Next')"></i>
                            </a>
                        </li>
                        <li v-else class="page-item" :class="{ active: link.active }">
                            <Link :href="link.url" class="page-link">
                            <i class="fas fa-angle-double-left" v-if="link.label.includes('Previous')"></i>
                            <i class="fas fa-angle-double-right" v-else-if="link.label.includes('Next')"></i>
                            <span v-else v-html="link.label"></span>
                            </Link>
                        </li>
                    </template>
                </ul>
            </nav>
        </div>
    </div>
</template>

<script>
import { Link } from "@inertiajs/vue3";

export default {
    props: {
        data: Object,
    },
    components: {
        Link,
    },
};
</script>
