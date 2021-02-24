<div>
    <!-- People find pleasure in different ways. I find it in keeping my mind clear. - Marcus Aurelius -->
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Filter the data</h6>
            <div class="row">
                <div class="col-md-8">
                    {!! Form::open(['route' => [$route->action['as'],null], 'method' => 'GET', 'files' => true]) !!}
                        @foreach($filters as $content)
                            <?php 
                                $type   = $content['type'];
                                $state  = $content['state'] ?? '';
                                $label  = isset($content['label']) ? $content['label'] : ucwords(str_replace("_", " ", $content['field']));
                            ?>
                            @if($type != 'hidden') 
                                <div class="form-group">
                                    <label for="{{ $content['field'] }}">
                                        {{ $label }}
                                    </label>
                                    @switch($type)
                                        @case('role_select2')
                                            {{ Form::select($content['field'], $content['data'], $content['value'] ?? NULL, ['placeholder'=> $label, 'class' => 'form-control js-example-basic-single', $state]) }}
                                            @break

                                        @case('select2')
                                            {{ Form::select($content['field'], $content['data'], $content['value'] ?? NULL, ['placeholder'=> $label, 'class' => 'form-control js-example-basic-single', $state]) }}
                                            @break

                                        @case('password')
                                            {{ Form::$type($content['field'], ['placeholder'=> $label, 'class' => 'form-control', $state]) }}
                                            @break

                                        @case('textarea')
                                        @case('text')
                                        @case('number')
                                            {{ Form::$type($content['field'], $content['value'] ?? NULL, ['placeholder'=> $label, 'class' => 'form-control', $state]) }}
                                            @break

                                        @case('wsywig')
                                            {{ Form::textarea($content['field'], $content['value'] ?? NULL, ['placeholder'=> $label, 'class' => 'form-control', 'rows' => '5', 'style' => 'height:auto', $state]) }}
                                            @break

                                        @case('currency')
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroup{{ $content['field'] }}">IDR</span>
                                                </div>
                                                {{ Form::text($content['field'], $content['value'] ?? NULL, [
                                                    'placeholder'=> $label, 'class' => 'form-control masking', 
                                                    'id' => 'inputGroup'.$content['field'], 
                                                    'aria-describedby' => 'inputGroup'.$content['field'],
                                                    'data-input-mask' => 'money', 
                                                    $state
                                                ]) }}
                                            </div>
                                            @break

                                        @case('file')
                                            {{ Form::file($content['field'], ['placeholder'=> $label, 'class' => 'form-control-file', $state]) }}
                                            @break

                                        @case('selectMonth')
                                            {{ Form::selectMonth($content['field'], $content['value'] ?? NULL, ['placeholder'=> $label, 'class' => 'form-control date-range-picker', $state]) }}
                                            @break

                                        @case('date')
                                            {{ Form::text($content['field'], $content['value'] ?? NULL, ['placeholder'=> $label, 'class' => 'form-control single-date-picker', $state]) }}
                                            @break

                                        @case('daterange')
                                            {{ Form::text($content['field'], $content['value'] ?? NULL, ['placeholder'=> $label, 'class' => 'form-control date-range-picker', $state]) }}
                                            @break

                                        @case('datetime')
                                            {{ Form::text($content['field'], $content['value'] ?? NULL, ['placeholder'=> $label, 'class' => 'form-control single-datetime-picker', $state]) }}
                                            @break
                                        
                                        @default
                                    @endswitch
                                    @error($content['field'])<p class="form-text text-danger">{{ $message }}</p>@enderror
                                </div>
                            @else
                                {{ Form::hidden($content['field'], $content['value'] ?? NULL, ['class' => 'form-control']) }}
                            @endif
                        @endforeach
                        {{ link_to(url()->previous(), 'Cancel', ['class' => 'btn btn-warning']) }}
                        {{ Form::submit('Submit', ['class' => 'btn btn-success', 'id' => 'submit']) }}
                    {!! Form::close() !!}
                </div>
            </div>        
        </div>
    </div>
</div>