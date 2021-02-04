<div>
    <!-- The only way to do great work is to love what you do. - Steve Jobs -->
    <div class="row">
        <div class="col-md-12">
            {!! Form::model($model, ['route' => [$route_as_name.'.update', $model->id], 'method' => 'PUT']) !!}
                {{ Form::token() }}
                <x-content.FormInput :contents=$contents />
                {{ link_to(url()->previous(), 'Cancel', ['class' => 'btn btn-warning']) }}
                {{ Form::submit('Submit', ['class' => 'btn btn-success', 'id' => 'submit']) }}
            {!! Form::close() !!}
        </div>
    </div>
</div>