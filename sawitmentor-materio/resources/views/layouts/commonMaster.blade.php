<!DOCTYPE html>
<html lang="id" class="layout-menu-fixed layout-compact" data-assets-path="{{ asset('/assets') . '/' }}" dir="ltr" data-skin="default" data-base-url="{{ url('/') }}" data-framework="laravel" data-bs-theme="light" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title') | {{ config('variables.templateName') }}</title>
    <meta name="description" content="{{ config('variables.templateDescription') }}" />
    <meta name="robots" content="noindex, nofollow" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    @include('layouts/sections/styles')
    @include('layouts/sections/scriptsIncludes')
</head>

<body>
    @yield('layoutContent')

    @include('layouts/sections/scripts')
</body>

</html>
