<div>
    <?php 
        $params = $model->id;
        if (isset(Route::current()->parameters['model_url'])) {
            $params = ['model_url' => Route::current()->parameters['model_url']->id, 'model' => $model->id];
        }
        // dd($params);
    ?>
    <!-- The only way to do great work is to love what you do. - Steve Jobs -->
    <div class="row">
        <div class="col-md-12">
            {!! Form::model($model, ['route' => [$route_as_name.'.update', $params], 'method' => 'PUT']) !!}
                <x-content.FormInput :contents=$contents />
            {!! Form::close() !!}
        </div>
    </div>
</div>