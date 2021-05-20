@extends('admin-dashboard::layout')

@section('content')
<h1 class="page-header">
    <a href="/{{ config('admin-notifications.route') }}">
        @lang('admin-notifications::admin.list-title')
    </a>
</h1>

<div class="sm:rounded-lg bg-white shadow mb-10">
    <div class="py-5 px-4 border-b border-gray-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="mr-4 text-blue-500 inline-block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span class="text-xl leading-6 font-medium text-gray-900">
            @lang('admin-notifications::admin.create-title')
        </span>
    </div>
</div>

<div class="my-4 mx-2 md:mx-10 pl-10">
	<form action="/{{ config('admin-notifications.route') }}" method="POST">
		@csrf
        <div class="form-group @error('message') has-error @enderror">
            <textarea name="message" rows="10" id="read">{{ old('message') }}</textarea>
            @error('message')
                <div class="error-description">
                    @lang('admin-notifications::admin.errors.'.$message)
                </div>
            @enderror
        </div>
        <div class="form-group text-center">
            <button type="submit" class="button">@lang('admin-notifications::admin.send')</button>
        </div>
	</form>
</div>

@endsection
