@extends('admin.layouts.app')

@section('panel-header')
    <div>
        <ul class="breadcrumb">
            <li><a href="{{route('admin.view.dashboard')}}">Admin</a></li>
            <li><i data-feather="chevron-right"></i></li>
            <li><a href="{{route('admin.view.setting')}}">Settings</a></li>
        </ul>
        <h1 class="panel-title">Settings</h1>
    </div>
@endsection


@section('panel-body')
<div class="grid 2xl:grid-cols-4 lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 lg:gap-7 sm:gap-5">

    <figure class="panel-card">
        <div class="panel-card-body">
            <div class="space-y-3">
                <div>
                    <div class="h-[50px] w-[50px] bg-complement rounded-full flex items-center justify-center text-ascent">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1.5em" width="1.5em" xmlns="http://www.w3.org/2000/svg"><path d="M12 2a5 5 0 1 0 5 5 5 5 0 0 0-5-5zm0 8a3 3 0 1 1 3-3 3 3 0 0 1-3 3zm9 11v-1a7 7 0 0 0-7-7h-4a7 7 0 0 0-7 7v1h2v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1z"></path></svg>
                    </div>
                </div>
                <div>
                    <h1 class="title-lg">Account Settings</h1>
                    <p class="description">Manage your account information</p>
                </div>
                <div>
                    <a href="{{route('admin.view.setting.account')}}" class="link text-sm flex items-center space-x-2">
                        <span>Edit Information</span>    
                        <i data-feather="edit" class="h-3 w-3 stroke-[2.5px]"></i>
                    </a>
                </div>
            </div>
        </div>
    </figure>

    <figure class="panel-card">
        <div class="panel-card-body">
            <div class="space-y-3">
                <div>
                    <div class="h-[50px] w-[50px] bg-complement rounded-full flex items-center justify-center text-ascent">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1.5em" width="1.5em" xmlns="http://www.w3.org/2000/svg"><path d="M16 12h2v4h-2z"></path><path d="M20 7V5c0-1.103-.897-2-2-2H5C3.346 3 2 4.346 2 6v12c0 2.201 1.794 3 3 3h15c1.103 0 2-.897 2-2V9c0-1.103-.897-2-2-2zM5 5h13v2H5a1.001 1.001 0 0 1 0-2zm15 14H5.012C4.55 18.988 4 18.805 4 18V8.815c.314.113.647.185 1 .185h15v10z"></path></svg>
                    </div>
                </div>
                <div>
                    <h1 class="title-lg">Payment Gateways</h1>
                    <p class="description">Manage the payment gateways in the system</p>
                </div>
                <div>
                    <a href="#" class="link text-sm flex items-center space-x-2">
                        <span>Edit Information</span>    
                        <i data-feather="edit" class="h-3 w-3 stroke-[2.5px]"></i>
                    </a>
                </div>
            </div>
        </div>
    </figure>

    <figure class="panel-card">
        <div class="panel-card-body">
            <div class="space-y-3">
                <div>
                    <div class="h-[50px] w-[50px] bg-complement rounded-full flex items-center justify-center text-ascent">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1.5em" width="1.5em" xmlns="http://www.w3.org/2000/svg"><path d="M7 17a5.007 5.007 0 0 0 4.898-4H14v2h2v-2h2v3h2v-3h1v-2h-9.102A5.007 5.007 0 0 0 7 7c-2.757 0-5 2.243-5 5s2.243 5 5 5zm0-8c1.654 0 3 1.346 3 3s-1.346 3-3 3-3-1.346-3-3 1.346-3 3-3z"></path></svg>
                    </div>
                </div>
                <div>
                    <h1 class="title-lg">Update Password</h1>
                    <p class="description">Change your account password</p>
                </div>
                <div>
                    <a href="{{route('admin.view.setting.password')}}" class="link text-sm flex items-center space-x-2">
                        <span>Update Password</span>    
                        <i data-feather="edit" class="h-3 w-3 stroke-[2.5px]"></i>
                    </a>
                </div>
            </div>
        </div>
    </figure>

    <figure class="panel-card">
        <div class="panel-card-body">
            <div class="space-y-3">
                <div>
                    <div class="h-[50px] w-[50px] bg-complement rounded-full flex items-center justify-center text-ascent">
                        <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1.5em" width="1.5em" xmlns="http://www.w3.org/2000/svg"><path d="M19 13.586V10c0-3.217-2.185-5.927-5.145-6.742C13.562 2.52 12.846 2 12 2s-1.562.52-1.855 1.258C7.185 4.074 5 6.783 5 10v3.586l-1.707 1.707A.996.996 0 0 0 3 16v2a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-2a.996.996 0 0 0-.293-.707L19 13.586zM19 17H5v-.586l1.707-1.707A.996.996 0 0 0 7 14v-4c0-2.757 2.243-5 5-5s5 2.243 5 5v4c0 .266.105.52.293.707L19 16.414V17zm-7 5a2.98 2.98 0 0 0 2.818-2H9.182A2.98 2.98 0 0 0 12 22z"></path></svg>
                    </div>
                </div>
                <div>
                    <h1 class="title-lg">Notification Email</h1>
                    <p class="description">Change notification email credentials</p>
                </div>
                <div>
                    <a href="#" class="link text-sm flex items-center space-x-2">
                        <span>Edit Information</span>    
                        <i data-feather="edit" class="h-3 w-3 stroke-[2.5px]"></i>
                    </a>
                </div>
            </div>
        </div>
    </figure>
    

</div>
@endsection

@section('panel-script')
<script>
    document.getElementById('setting-tab').classList.add('active');
</script>
@endsection