<template>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
            <SidebarLayout />
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                    <HeaderLayout :page_title="page_title" :page_icon="page_icon" />
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                    <slot />
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white shadow">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; IMSupport 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
        </a>

</template>

<script>

import HeaderLayout from "./HeaderLayout.vue";
import SidebarLayout from "./SidebarLayout.vue";

export default {
    props: {
        page_title: String,
        page_icon: String
    },
    components: {
        HeaderLayout,
        SidebarLayout,
    },
};
</script>
