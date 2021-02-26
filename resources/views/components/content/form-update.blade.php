<div>
    <?php 
        $params             =   $model->id;
        $params_back        =   null;
        if (isset(Route::current()->parameters['model_url'])) {
            $params         =   ['model_url' => Route::current()->parameters['model_url']->id, 'model' => $model->id];
            $params_back    =   Route::current()->parameters['model_url']->id;
        }
    ?>
    <!-- The only way to do great work is to love what you do. - Steve Jobs -->
    <div class="row mb-3">
        <div class="col-md-12">
            {{ link_to_route(Route::current()->controller->back_from_form, 'Back', $params_back,['class' => 'btn btn-warning']) }}
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {!! Form::model($model, ['route' => [$route_as_name.'.update', $params], 'method' => 'PUT', 'files' => true]) !!}
                <x-content.FormInput 
                    :contents=$contents 
                    :logs=$logs
                />
            {!! Form::close() !!}
        </div>
    </div>
</div>