<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    {{ $title }}
                    @if($subtitle)
                        <small>- {{ $subtitle }}</small>
                    @endif
                </h1>
            </div>
            
            @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        @foreach($breadcrumbs as $breadcrumb)
                            <li class="breadcrumb-item {{ $breadcrumb['active'] ?? false ? 'active' : '' }}">
                                @if(isset($breadcrumb['route']) && !($breadcrumb['active'] ?? false))
                                    <a href="{{ route($breadcrumb['route']) }}">{{ $breadcrumb['label'] }}</a>
                                @else
                                    {{ $breadcrumb['label'] }}
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </div>
            @endif
        </div>
    </div>
</div>
