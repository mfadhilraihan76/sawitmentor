@php
$containerFooter = !empty($containerNav) ? $containerNav : 'container-fluid';
@endphp

<footer class="content-footer footer bg-footer-theme">
    <div class="{{ $containerFooter }}">
        <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
            <div class="text-body">
                &#169; {{ date('Y') }} {{ config('variables.templateName') }}
            </div>
        </div>
    </div>
</footer>
