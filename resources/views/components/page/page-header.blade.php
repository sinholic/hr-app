<div>
    <!-- Simplicity is an acquired taste. - Katharine Gerould -->
    <div class="page-header">
        <div class="container-fluid d-sm-flex justify-content-between">
            <h4>{{ $title }}{{ isset($subTitle) ? ' - '.ucwords(str_replace('_', ' ', $subTitle)) : '' }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @foreach($breadcrumbs as $breadcrumb)
                    <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                        {{ ucwords(str_replace('_', ' ', $breadcrumb)) }}
                    </li>
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>
</div>