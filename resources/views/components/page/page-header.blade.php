<div>
    <!-- Simplicity is an acquired taste. - Katharine Gerould -->
    <div class="page-header">
        <div class="container-fluid d-sm-flex justify-content-between">
            <h4>{{ $headerPrefix ?? '' }}{{ $title }}{{  isset($subTitle) ? ' - '.$subTitle : '' }}</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    @foreach($breadcrumbs as $breadcrumb)
                    <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                        {{ $breadcrumb }}
                    </li>
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>
</div>