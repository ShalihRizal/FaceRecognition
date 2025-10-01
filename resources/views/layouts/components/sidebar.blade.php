<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div>
        <div class="brand-logo d-flex align-items-center justify-content-center">
            <a href="./index.html" class="text-nowrap logo-img">
            <img src="{{asset('assets')}}/images/logos/seodashlogo.png" alt="" />
            </a>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar mt-5" data-simplebar="">
            <ul id="sidebarnav">
                @include('components.menudash')
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>