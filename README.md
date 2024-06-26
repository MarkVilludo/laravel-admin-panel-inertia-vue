# Laravel Admin Panel Inertia Vue
Laravel package to create laravel admin panel using inertia vue

## Author for CMS Template (Credits)

- [@fmhorfilla](https://www.github.com/fmhorfilla)
- [@janzcio](https://www.github.com/janzcio)

## Prerequisites

- [Laravel Project | fresh or existing](https://laravel.com/docs/11.x/installation)
- [ Node version is above v20.10.0](https://nodejs.org/en/download/package-manager)

## Installation

Require this package with composer.

```bash
    composer require "mark-villudo/laravel-admin-panel-inertia-vue @dev"
```

### Publish assets and layouts
Register Service Provider in bootstrap/providers.php

```bash
    MarkVilludo\AdminPanelInertiaVue\CMSServicedProvider::class,
```

### Clear cache
```
    php artisan optimize
```

### Publish `resources/assets` and `resources/js/Layouts`

```bash
    php artisan vendor:publish --tag=cms-assets
```

## Setup

## Setup Initial Pages in inertia (Pages, Components, etc)
```bash
    #This command installs Jetstream with server-side rendering support for Inertia.js, enhancing performance and SEO.
    php artisan jetstream:install inertia --ssr

    #This command runs all pending database migrations, creating the necessary tables and columns in the database.
    php artisan migrate
```

## Setup Layouts

### Step 1

Open the `resources/js/app.js` to import the assets and add following VueSweetalert2, VueTippy and Toastr for dependencies
```bash
    import './bootstrap';
    import '@assets/css/bootstrap/bootstrap.min.css';
    import '@assets/css/app.css';
    import '@resources/css/app.css';


    import { createApp, h } from 'vue';
    import { createInertiaApp } from '@inertiajs/vue3';
    import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
    import { ZiggyVue } from '../../vendor/tightenco/ziggy';

    const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

    import VueSweetalert2 from 'vue-sweetalert2';
    import 'sweetalert2/dist/sweetalert2.min.css';

    import { plugin as VueTippy } from 'vue-tippy';
    import 'tippy.js/dist/tippy.css';


    // Toastr initialization
    import toastr from 'toastr';
    import 'toastr/build/toastr.css';

    // Toastr does not have an install method, you might need to configure it differently.
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        showDuration: '300',
        hideDuration: '1000',
        timeOut: '5000',
        extendedTimeOut: '1000',
        showEasing: 'swing',
        hideEasing: 'linear',
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut'
    };

    createInertiaApp({
        title: (title) => `${title} - ${appName}`,
        resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
        setup({ el, App, props, plugin }) {
            const app = createApp({ render: () => h(App, props) });

            app.use(plugin);
            app.use(ZiggyVue);
            app.use(VueSweetalert2); //additional
            app.use(VueTippy); //additional

            // Toastr does not have an install method. It should be configured globally.
            app.config.globalProperties.$toastr = toastr;

            app.mount(el);

            return app;
        },
        progress: {
            color: '#4B5563',
        },
    });
```
### Step 2
update `package.json` for toast in devDependencies block, and dependencies block for `lodash`, `vue-sweetalert2` and `vue-tippy`
```bash
    "devDependencies": {
        "toastr": "^2.1.4",
    },
    "dependencies": {
        "lodash": "^4.17.21",
        "vue-sweetalert2": "^5.0.11",
        "vue-tippy": "^6.4.1"
    }
```

### Step 3
update `vite.config.js` and add/update the ff code
```bash
    import { defineConfig } from 'vite';
    import laravel from 'laravel-vite-plugin';

    //additional
    import vue from "@vitejs/plugin-vue";
    import path from "path";

    export default defineConfig({
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.js'],
                refresh: true,
            }),
            //additionals
            vue(),
        ],
        //additionals
        resolve: {
            alias: {
            
                "@resources": path.resolve(__dirname, "resources"),
                "@assets": path.resolve(__dirname, "resources/assets"),
                "@public": path.resolve(__dirname, "public/assets"),
                "@mixins": path.resolve(__dirname, "resources/js/Pages/Mixins"),
            },
        },
        build: {
            rollupOptions: {
                output: {
                    assetFileNames: 'assets/[name]-[hash].[ext]',
                },
            },
        },
    });
```
### Step 4
Update the `resources/js/Layouts/AppLayout.vue`
```
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
```

### Step 5
Add css and js dependency in `resources/views/app.blade.php`

```bash
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    </head>
    <body class="font-sans antialiased">
        @inertia
        <!-- additionals -->
        @vite('resources/assets/js/jquery-3.7.1.min.js')
        @vite('resources/assets/js/bootstrap/bootstrap.bundle.min.js')
        @vite('resources/assets/js/bootstrap/main.js')
    </body>
```

### Step 6
Open the `resources/js/Pages/Dashboard.vue` and update the AppLayout
```
    from
    <AppLayout title="Dashboard">

    to
    <AppLayout page_title="Dashboard" page_icon="fas fa-layer-group">
```

### Step 7
Run commands
```bash
    npm install //This command installs all dependencies listed in the package.json file of your project. 
    npm run dev //runs a development server or build process specific to your project setup.
    php artisan optimize //clear cache
    php artisan serve //serve laravel application
```

### Step 8
Test it.
![alt text](https://github.com/MarkVilludo/laravel-admin-panel-inertia-vue/blob/main/img/ss.png?raw=true)


## To enable the User Management Modules

### Step 1

Publish all files from Spatie Service Provider

```bash
    php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

Then publish all files from our package for User Management

```bash
    php artisan vendor:publish --tag=cms-usermanagement
```

Then run this command to clear cache for routes and others

```bash
    php artisan optimize:clear
```

### Step 2
Include this in `routes/web.php`

```bash
    // Include user management routes/web.php
    include __DIR__.'/usermanagement.php';
```

### Step 3

Update the `Models/User.php`, add this following code for spatie and guard name

```bash
    use Spatie\Permission\Traits\HasRoles;


    use HasRoles;

    public $guard_name = 'sanctum';
```

### Step 4

Then add this to `database/DatabaseSeeder`

```bash
    $this->call(PermissionSeeder::class);
    $this->call(RoleSeeder::class);
    $this->call(UserSeeder::class);
```

### Step 5
Run migrate refresh and seed initial user, role, and permissions data

```bash
    php artisan optimize:clear
    php artisan migrate:fresh
    php artisan db:seed
```

### Step 6
Update the `app\Http\Middleware\HandleInertiaRequests.php`

```bash
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'user.permissions' => function () use ($request) {
                return ( $request->user() ? $request->user()->getAllPermissions()->pluck('name') : null );
            },
            'user.roles' => function () use ($request) {
                return ( $request->user() ? $request->user()->roles()->pluck('name') : null );
            },
            'user.locale' => function () use ($request) {
                return session('locale');
            },
        ]);
    }
```

### Step 7
Update the `Resources/js/Pages/SidebarLayouts.vue` to include the Users, Roles, and Permissions Module

```bash
    <li class="nav-item" :class="{ 'active': ['user-management.index'].includes($page.component) }" 
        v-if="$page.props.user.permissions.includes('user_management')">
            <Link class="nav-link" :href="'/user-management'">
            <i class="fas fa-layer-group"></i>
            <span>User Management</span></Link>
    </li>
    <li class="nav-item" :class="{ 'active': ['roles.index'].includes($page.component) }" 
        v-if="$page.props.user.permissions.includes('roles')">
            <Link class="nav-link" :href="'/admin/dashboard'">
            <i class="fas fa-layer-group"></i>
            <span>Roles</span></Link>
    </li>
    <li class="nav-item" :class="{ 'active': ['permissions.index'].includes($page.component) }" 
        v-if="$page.props.user.permissions.includes('permissions')">
            <Link class="nav-link" :href="'/admin/dashboard'">
            <i class="fas fa-layer-group"></i>
            <span>Permissions</span></Link>
    </li>
```

### Step 8
Test it.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
