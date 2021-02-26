<div>
    <?php 
        $params         = null;
        if (isset(Route::current()->parameters['model_url'])) {
            $params = Route::current()->parameters['model_url']->id;
        }
    ?>
    <div class="row mb-3">
        <div class="col-md-12">
            {{ link_to_route(Route::current()->controller->back_from_form, 'Back', $params,['class' => 'btn btn-warning']) }}
        </div>
    </div>
    <!-- The whole future lies in uncertainty: live immediately. - Seneca -->
    <div class="row">
        <div class="col-md-12">
            {!! Form::open(['route' => [$route_as_name.'.store', $params], 'files' => true]) !!}
                <x-content.FormInput :contents=$contents />
            {!! Form::close() !!}
        </div>
    </div>
</div>