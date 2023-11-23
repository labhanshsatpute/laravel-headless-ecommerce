<aside id="sidebar">
    <div class="sidebar">
        <div class="space-y-7">
            <div class="flex items-center justify-between relative">
                <div class="md:text-center sm:text-left space-y-2 pt-10 px-10 w-full">
                    <div>
                        <h1 class="text-ascent text-lg font-semibold">{{config('app.name')}}</h1>
                    </div>
                    <p class="text-xs text-slate-500">Administrator Panel</p>
                </div>
            </div>
            <div class="absolute top-3 right-0 lg:hidden md:hidden sm:block">
                <button onclick="toggleSidebar()"
                    class="h-[40px] w-[40px] bg-ascent rounded-l-lg border-r-0 flex items-center justify-center transition duration-300 ease-in-out hover:ease-in-out">
                    <i data-feather="chevron-left" class="h-4 w-4 stroke-white stroke-[3px]"></i>
                </button>
            </div>
            <hr class="border-complement">
            <ul class="flex flex-col">

                <li class="sidebar-tab" id="dashboard-tab">
                    <a href="{{route('admin.view.dashboard')}}">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M12 4C6.486 4 2 8.486 2 14a9.89 9.89 0 0 0 1.051 4.445c.17.34.516.555.895.555h16.107c.379 0 .726-.215.896-.555A9.89 9.89 0 0 0 22 14c0-5.514-4.486-10-10-10zm7.41 13H4.59A7.875 7.875 0 0 1 4 14c0-4.411 3.589-8 8-8s8 3.589 8 8a7.875 7.875 0 0 1-.59 3z"></path><path d="M10.939 12.939a1.53 1.53 0 0 0 0 2.561 1.53 1.53 0 0 0 2.121-.44l3.962-6.038a.034.034 0 0 0 0-.035.033.033 0 0 0-.045-.01l-6.038 3.962z"></path></svg>
                        <span>Dashboard</span>
                    </a>
                </li>
        
                <li class="sidebar-tab" id="access-tab">
                    <a href="{{route('admin.view.access.list')}}">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M20.995 6.9a.998.998 0 0 0-.548-.795l-8-4a1 1 0 0 0-.895 0l-8 4a1.002 1.002 0 0 0-.547.795c-.011.107-.961 10.767 8.589 15.014a.987.987 0 0 0 .812 0c9.55-4.247 8.6-14.906 8.589-15.014zM12 19.897V12H5.51a15.473 15.473 0 0 1-.544-4.365L12 4.118V12h6.46c-.759 2.74-2.498 5.979-6.46 7.897z"></path></svg>
                        <span>Admin Access</span>
                    </a>
                </li>

                <li class="sidebar-tab" id="coupon-tab">
                    <a href="{{route('admin.view.coupon.list')}}">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M20.995 6.9a.998.998 0 0 0-.548-.795l-8-4a1 1 0 0 0-.895 0l-8 4a1.002 1.002 0 0 0-.547.795c-.011.107-.961 10.767 8.589 15.014a.987.987 0 0 0 .812 0c9.55-4.247 8.6-14.906 8.589-15.014zM12 19.897V12H5.51a15.473 15.473 0 0 1-.544-4.365L12 4.118V12h6.46c-.759 2.74-2.498 5.979-6.46 7.897z"></path></svg>
                        <span>Coupons</span>
                    </a>
                </li>

                <li class="sidebar-tab" id="setting-tab">
                    <a href="{{--route('admin.view.setting')--}}">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M12 16c2.206 0 4-1.794 4-4s-1.794-4-4-4-4 1.794-4 4 1.794 4 4 4zm0-6c1.084 0 2 .916 2 2s-.916 2-2 2-2-.916-2-2 .916-2 2-2z"></path><path d="m2.845 16.136 1 1.73c.531.917 1.809 1.261 2.73.73l.529-.306A8.1 8.1 0 0 0 9 19.402V20c0 1.103.897 2 2 2h2c1.103 0 2-.897 2-2v-.598a8.132 8.132 0 0 0 1.896-1.111l.529.306c.923.53 2.198.188 2.731-.731l.999-1.729a2.001 2.001 0 0 0-.731-2.732l-.505-.292a7.718 7.718 0 0 0 0-2.224l.505-.292a2.002 2.002 0 0 0 .731-2.732l-.999-1.729c-.531-.92-1.808-1.265-2.731-.732l-.529.306A8.1 8.1 0 0 0 15 4.598V4c0-1.103-.897-2-2-2h-2c-1.103 0-2 .897-2 2v.598a8.132 8.132 0 0 0-1.896 1.111l-.529-.306c-.924-.531-2.2-.187-2.731.732l-.999 1.729a2.001 2.001 0 0 0 .731 2.732l.505.292a7.683 7.683 0 0 0 0 2.223l-.505.292a2.003 2.003 0 0 0-.731 2.733zm3.326-2.758A5.703 5.703 0 0 1 6 12c0-.462.058-.926.17-1.378a.999.999 0 0 0-.47-1.108l-1.123-.65.998-1.729 1.145.662a.997.997 0 0 0 1.188-.142 6.071 6.071 0 0 1 2.384-1.399A1 1 0 0 0 11 5.3V4h2v1.3a1 1 0 0 0 .708.956 6.083 6.083 0 0 1 2.384 1.399.999.999 0 0 0 1.188.142l1.144-.661 1 1.729-1.124.649a1 1 0 0 0-.47 1.108c.112.452.17.916.17 1.378 0 .461-.058.925-.171 1.378a1 1 0 0 0 .471 1.108l1.123.649-.998 1.729-1.145-.661a.996.996 0 0 0-1.188.142 6.071 6.071 0 0 1-2.384 1.399A1 1 0 0 0 13 18.7l.002 1.3H11v-1.3a1 1 0 0 0-.708-.956 6.083 6.083 0 0 1-2.384-1.399.992.992 0 0 0-1.188-.141l-1.144.662-1-1.729 1.124-.651a1 1 0 0 0 .471-1.108z"></path></svg>
                        <span>Settings</span>
                    </a>
                </li>
        
            </ul>
        </div>
    </div>
    <div class="sidebar-overlay">

    </div>
</aside>