<div>
    <!-- People find pleasure in different ways. I find it in keeping my mind clear. - Marcus Aurelius -->
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Filter the data</h6>
            <div class="row">
                <div class="col-md-8">
                    {!! Form::open(['route' => [$route->action['as'],null], 'method' => 'GET', 'files' => true]) !!}
                        <x-content.FormInput :contents=$filters />
                    {!! Form::close() !!}
                </div>
            </div>        
        </div>
    </div>
</div>