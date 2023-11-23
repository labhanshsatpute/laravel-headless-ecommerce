<div class="relative" 
    x-data="{
        open: false,
        toggle() {
            if (this.open) {
                return this.close()
            }
            this.$refs.button.focus()
            this.open = true
        },
        close(focusAfter) {
            if (!this.open) return
            this.open = false
            focusAfter && focusAfter.focus()
        }
    }" 
    x-on:keydown.escape.prevent.stop="close($refs.button)"
    x-on:focusin.window="! $refs.panel.contains($event.target) && close()" 
    x-id="['notification-dropdown-button']">
    <div>
        <button 
            x-ref="button" 
            x-on:click="toggle()" 
            type="button"
            :class="open && 'ring-ascent ring-4'"
            class="h-[40px] w-[40px] hover:bg-complement rounded-lg flex items-center justify-center transition duration-300 ease-in-out hover:ease-in-out border border-gray-200">
            <i data-feather="bell" class="h-4 w-4 stroke-ascent-dark stroke-[3px]"></i>
        </button>
    </div>
    <div 
        x-ref="panel" 
        x-show="open" 
        x-transition.origin.top.right x-on:click.outside="close($refs.button)"
        :id="$id('notification-dropdown-button')" 
        style="display: none;"
        class="absolute lg:-right-2 md:-right-2 sm:-right-14 z-10 mt-4 md:w-auto sm:w-fit origin-top-right rounded-xl bg-white shadow-lg overflow-clip text-left border">
        <div class="border-b p-5 flex items-center space-x-12 justify-between">
            <div>
                <h1 class="text-base font-semibold">Notifications</h1>
                <p class="text-xs text-slate-500 whitespace-nowrap" id="notification-status-message"></p>
            </div>
            <div>
                <button onclick="markAllNotificationAsRead()" id="notification-mark-as-read-button" class="font-medium text-sm whitespace-nowrap text-ascent hover:text-ascent-dark">Mark all as read</button>
            </div>
        </div>
        <div class="max-h-[400px] overflow-y-auto" id="notification-list">
            
        </div>
        <div class="p-5 flex items-center space-x-12 justify-between">
            <a href="{{--route('vendor.view.notification.list')--}}" class="link text-sm flex items-start justify-center w-fit space-x-1">
                <span>View all notifications</span>
                <i data-feather="external-link" class="h-3.5 w-3.5 mt-0.5"></i>
            </a>
        </div>
    </div>
</div>