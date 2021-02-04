<div>
    <!-- Simplicity is the ultimate sophistication. - Leonardo da Vinci -->
    @if(!isset($options['enable_add']) || $options['enable_add'])
        <div class="row mb-3">
            <div class="col-md-12">
                @if(is_array($options['enable_add']))
                    <a href="{{ route($options['enable_add']['action'], ($options['enable_add']['params'] ?? null)) }}" class="btn btn-primary">Add</a>
                @else
                    <a href="{{ route($route_as_name.'.create') }}" class="btn btn-primary">Add</a>
                @endif
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped table-responsive-stack">
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
                                    <td>{{ $data->$field->$key }}</td>
                                @else
                                    @if(isset($content['type']))
                                        @if($content['type'] == 'count' )
                                            <td>{{ $data->$field()->count() }}</td>
                                        @endif
                                    @else
                                        <td>{{ $data->$field }}</td>
                                    @endif
                                @endif
                            @else
                                <td>{{ $data->$content }}</td>
                            @endif
                        @endforeach
                        @if(!isset($options['enable_action']) || $options['enable_action'])
                            <td>
                                @if(!isset($options['enable_edit']) || $options['enable_edit'])
                                    <a href="{{ route($route_as_name.'.edit', $data->id) }}" class="btn btn-warning">Edit</a>
                                @endif
                                @if(!isset($options['enable_delete']) || $options['enable_delete'])
                                    <a href="{{ route($route_as_name.'.destroy', $data->id) }}" class="btn btn-danger">Delete</a>
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
                                            // dd($data->$when->$when_key == $when_value ? 'YES' : 'NO');
                                        ?>
                                        @if(isset($button_extend['roles']))
                                            @hasanyrole($button_extend['roles'])
                                                <?php $state_show = false; $skip_when = false; ?>
                                            @endhasanyrole
                                        @endif
                                        @if($when != ''  && !$skip_when)
                                            @if($when_key != '')
                                                <?php $state_show = $data->$when->$when_key == $when_value ? true : false ?>
                                            @else
                                                <?php $state_show = $data->$when == $when_value ? true : false ?>
                                            @endif
                                        @endif
                                        @if($state_show)
                                            <a href="{{ route($button_extend['action'], $data->$params) }}" class="btn btn-{{ $button_extend['class'] ?? 'primary' }}">{{ ucwords($button_extend['label']) }}</a>
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