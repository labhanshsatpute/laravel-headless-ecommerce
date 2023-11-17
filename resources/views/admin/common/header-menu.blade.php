<figure class="px-2 py-1 shadow-lg bg-white rounded-xl border">
    <div class="flex space-x-2 items-center justify-between">
        <div class="gap-2 flex items-center lg:flex-row md:flex-row sm:flex-row-reverse w-full justify-between">
            <div class="flex items-center lg:w-full md:w-full sm:w-full justify-start pl-3 bg-complement rounded-lg overflow-clip">
                <i data-feather="search" class="h-[16px] w-[16px] stroke-gray-500"></i>
                <input type="search" placeholder="Search Here" class="bg-complement p-2.5 outline-none text-sm lg:w-full md:w-full sm:w-[152px]">
            </div>
            <button onclick="toggleSidebar()"
                class="h-[40px] w-[50px] lg:hidden md:hidden hover:bg-complement rounded-lg flex items-center justify-center transition duration-300 ease-in-out hover:ease-in-out border border-gray-200">
                <i data-feather="menu" class="h-4 w-4 stroke-ascent-dark stroke-[3px]"></i>
            </button>
        </div>
        <div class="space-x-2 flex items-center">
            @include('admin.components.notification-dropdown')
            @include('admin.components.profile-dropdown')
        </div>
    </div>
</figure>
