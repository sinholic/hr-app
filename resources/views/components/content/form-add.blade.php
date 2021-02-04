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
                {{ Form::token() }}
                <x-content.FormInput :contents=$contents />
                {{ link_to(url()->previous(), 'Cancel', ['class' => 'btn btn-warning']) }}
                {{ Form::submit('Submit', ['class' => 'btn btn-success', 'id' => 'submit']) }}
            {!! Form::close() !!}
        </div>
    </div>
</div>