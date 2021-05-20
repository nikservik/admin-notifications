        <div class="user-card">
            <div class="status-square bg-indigo-400">
                <svg class="fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20"><path class="heroicon-ui" d="M4 4h16a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2zm16 3.38V6H4v1.38l8 4 8-4zm0 2.24l-7.55 3.77a1 1 0 0 1-.9 0L4 9.62V18h16V9.62z"/></svg>
            </div>
            <div class="">
                <div class="text-sm mt-1 hover:no-underline">
                    {!! $notification->message !!}
                </div>
                <div class="text-gray-500 text-sm mt-1">
                    @lang('admin-notifications::admin.sent')
                    {{ $notification->created_at->addHours(3)->format('d.m.Y H:i') }}
                    @lang('admin-notifications::admin.moscow-time')
                </div>
                <div class="text-gray-500 text-sm mt-1">
                    @lang('admin-notifications::admin.read-count')
                    {{ $notification->read }}
                    @lang('admin-notifications::admin.read-count-times')
                </div>
            </div>
            <div class="ml-auto">
                <a href="/{{ config('admin-notifications.route') }}/{{ $notification->id }}">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" class="inline-block fill-current text-gray-500"><path class="heroicon-ui" d="M6.3 12.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H7a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM8 16h2.59l9-9L17 4.41l-9 9V16zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h6a1 1 0 0 1 0 2H4v14h14v-6z"/></svg></a>
            </div>
            <div class="ml-1">
                <a href="javascript:document.notification_{{ $notification->id }}_delete.submit()" onclick="return confirm('@lang('admin-notifications::admin.confirm-delete')')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" class="inline-block fill-current text-gray-500"><path class="heroicon-ui" d="M8 6V4c0-1.1.9-2 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8H3a1 1 0 1 1 0-2h5zM6 8v12h12V8H6zm8-2V4h-4v2h4zm-4 4a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1z"/></svg></a>
                <form name="notification_{{ $notification->id }}_delete" action="/{{ config('admin-notifications.route') }}/{{ $notification->id }}" method="POST">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
