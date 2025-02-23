<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @foreach ($menu as $item)
                    @if (isset($item['header']))
                        <li class="nav-header">{{ $item['header'] }}</li>
                    @else
                        <li class="nav-item">
                            <a href="{{ $item['url'] }}" class="nav-link">
                                <i class="nav-icon {{ $item['icon'] ?? 'fas fa-circle' }}"></i>
                                <p>{{ $item['text'] }}</p>
                            </a>
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
    </div>
</aside>