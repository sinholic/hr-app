<div>
    <!-- Simplicity is an acquired taste. - Katharine Gerould -->
    {{ Form::token() }}
    @foreach($contents as $content)
        <?php 
            $type   = $content['type'];
            $state  = $content['state'] ?? '';
        ?>
        @if($type != 'hidden') 
            <div class="form-group">
                <label for="{{ $content['field'] }}">
                    @if(isset($content['label']))
                        {{ $content['label'] }}
                    @else
                        <th>{{ ucfirst(str_replace("_", " ", $content['field'])) }}</th>
                    @endif
                </label>
                @switch($type)
                    @case('select2')
                        {{ Form::select($content['field'], $content['data'], null, ['class' => 'form-control js-example-basic-single', $state]) }}
                        @break

                    @case('textarea')
                    @case('text')
                    @case('number')
                        {{ Form::$type($content['field'], null, ['class' => 'form-control', $state]) }}
                        @break

                    @case('wsywig')
                        {{ Form::textarea($content['field'], null, ['class' => 'form-control', 'rows' => '5', 'style' => 'height:auto', $state]) }}
                        @break

                    @case('currency')
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup{{ $content['field'] }}">IDR</span>
                            </div>
                            {{ Form::text($content['field'], null, [
                                'class' => 'form-control masking', 
                                'id' => 'inputGroup'.$content['field'], 
                                'aria-describedby' => 'inputGroup'.$content['field'],
                                'data-input-mask' => 'money', 
                                $state
                            ]) }}
                        </div>
                        @break

                    @case('file')
                        {{ Form::file($content['field'], ['class' => 'form-control-file', $state]) }}
                        @break

                    @case('date')
                        {{ Form::text($content['field'], null, ['class' => 'form-control single-date-picker', $state]) }}
                        @break
                    @default
                @endswitch
                @error($content['field'])<p class="form-text text-danger">{{ $message }}</p>@enderror
            </div>
        @else
            {{ Form::hidden($content['field'], $content['value'], ['class' => 'form-control']) }}
        @endif
    @endforeach
    {{ link_to(url()->previous(), 'Cancel', ['class' => 'btn btn-warning']) }}
    {{ Form::submit('Submit', ['class' => 'btn btn-success', 'id' => 'submit']) }}
</div>