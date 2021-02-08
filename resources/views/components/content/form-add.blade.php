<div>
    <?php 
        $params = null;
        if (Route::current()->parameters) {
            $params = Route::current()->parameters['model']->id;
        }
    ?>
    <!-- The whole future lies in uncertainty: live immediately. - Seneca -->
    <div class="row">
        <div class="col-md-12">
            {!! Form::open(['route' => [$route_as_name.'.store', $params], 'files' => true]) !!}
                <x-content.FormInput :contents=$contents />
            {!! Form::close() !!}
        </div>
    </div>
</div>