<div id="kt_app_footer" class="app-footer">
    <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
        <div class="text-dark order-2 order-md-1">
            <span class="text-muted fw-semibold me-1">{{ date('Y') }}©</span>
            <a href="{{ url('/panel/dashboard') }}" class="text-gray-800 text-hover-primary">
                {{ $data['identitas']->apps_name ?? 'Online CCTV' }}
            </a>
        </div>
        <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
            <li class="menu-item">
                <span class="menu-link px-2 text-muted">Online CCTV Management System</span>
            </li>
        </ul>
    </div>
</div>
