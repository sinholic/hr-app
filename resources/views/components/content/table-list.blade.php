<div>
    <?php 
        $back_button = isset(Route::current()->parameters['model_url']) ? true : false;
    ?>
    <!-- Simplicity is the ultimate sophistication. - Leonardo da Vinci -->
    @if(!isset($options['enable_add']) || $options['enable_add'])
        <div class="row mb-3">
            <div class="col-md-12">
                @if($back_button)
                    {{ link_to(url()->previous(), 'Back', ['class' => 'btn btn-warning']) }}
                @endif
                @if(is_array($options['enable_add']))
                    <?php $show_add = isset($options['enable_add']['roles']) ? false : true; ?>
                    @if(isset($options['enable_add']['roles']))
                        @hasanyrole($options['enable_add']['roles'])
                            <?php $show_add = true; ?>
                        @endhasanyrole
                    @endif
                    @if($show_add)
                        <a href="{{ route($options['enable_add']['action'], ($options['enable_add']['params'] ?? null)) }}" class="btn btn-primary">Add</a>
                    @endif
                @else
                    <a href="{{ route($route_as_name.'.create') }}" class="btn btn-primary">Add</a>
                @endif
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <table id="myTable" class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>No</th>
                        @foreach($contents as $content)
                            @if(is_array($content))
                                @if(isset($content['label']))
                                    <th>{{ $content['label'] }}</th>
                                @else
                                    <th>{{ ucfirst(str_replace("_", " ", $content['field'])) }}</th>
                                @endif
                            @else
                                <th>{{ ucfirst($content) }}</th>
                            @endif
                        @endforeach
                        @if(!isset($options['enable_action']) || $options['enable_action'])
                        <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($datas as $data)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        @foreach($contents as $content)
                            @if(is_array($content))
                                <?php $field = $content['field'] ?> 
                                @if(isset($content['key']))
                                    <?php $key = $content['key'] ?>
                                    <td>{{ $data->$field->$key ?? '' }}</td>
                                @else
                                    @if(isset($content['type']))
                                        @if($content['type'] == 'count' )
                                            <td>{{ $data->$field()->count() }}</td>
                                        @elseif($content['type'] == 'download')
                                            <td>
                                                <a href="{{ asset('storage/uploads/cv/'.$data->$field) }}" target="_blank">
                                                    <i class="fa fa-download" aria-hidden="true"></i>
                                                </a>
                                            </td>
                                        @endif
                                    @else
                                        <td>{{ $data->$field ?? '' }}</td>
                                    @endif
                                @endif
                            @else
                                <td>{{ $data->$content ?? '' }}</td>
                            @endif
                        @endforeach
                        @if(!isset($options['enable_action']) || $options['enable_action'])
                            <td>
                                @if(!isset($options['enable_edit']) || $options['enable_edit'])
                                    <a href="{{ route($route_as_name.'.edit', $data->id) }}" class="btn btn-block btn-sm btn-warning">Edit</a>
                                @endif
                                @if(!isset($options['enable_delete']) || $options['enable_delete'])
                                    <a href="{{ route($route_as_name.'.destroy', $data->id) }}" class="btn btn-block btn-sm btn-danger">Delete</a>
                                @endif
                                @if(isset($options['button_extends']))
                                    @foreach($options['button_extends'] as $button_extend)
                                        <?php 
                                            $params         = $button_extend['params'] ?? 'id'; 
                                            $when           = $button_extend['when'] ?? '';
                                            $when_key       = $button_extend['when_key'] ?? '';
                                            $when_value     = $button_extend['when_value'] ?? '';
                                            $skip_when      = true;
                                            $state_show     = false;
                                            $route_button   = null
                                        ?>
                                        @if(isset($button_extend['roles']))
                                            @hasanyrole($button_extend['roles'])
                                                <?php $state_show = false; $skip_when = ($when == '' ? true : false); ?>
                                            @endhasanyrole
                                        @endif
                                        @if($when != ''  && !$skip_when)
                                            @if($when_key != '')
                                                @if(is_array($when))
                                                    <?php $state_true = true; ?>
                                                    @foreach($when as $key => $value)
                                                        <?php 
                                                            $check_key      = $when_key[$key]; 
                                                            $check_value    = $when_value[$key];
                                                            $state_show     = $data->$value->$check_key == $check_value ? true : false;
                                                            if ($state_true && $state_show) {
                                                                $state_true = $state_show;
                                                                $state_show = true;
                                                            }else{
                                                                $state_true = $state_show;
                                                                $state_show = false;
                                                            }
                                                        ?>
                                                    @endforeach
                                                @else
                                                    <?php $state_show = $data->$when->$when_key == $when_value ? true : false ?>
                                                @endif
                                            @else
                                                <?php $state_show = $data->$when == $when_value ? true : false ?>
                                            @endif
                                        @endif
                                        @if($state_show)
                                            @if(is_array($params))
                                                <?php 
                                                    $params['model']    = $data->id;
                                                    $route_button       = route($button_extend['action'], $params)
                                                ?>
                                            @else
                                                <?php
                                                    $route_button       = route($button_extend['action'], $data->$params);
                                                ?>
                                            @endif
                                            <a href="{{ $route_button }}" class="btn btn-block btn-sm btn-{{ $button_extend['class'] ?? 'primary' }}">{{ ucwords($button_extend['label']) }}</a>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>