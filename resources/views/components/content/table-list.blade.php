<div>
    <?php 
        // Check if we in a nested table view with model_url or not
        $back_button = isset(Route::current()->parameters['model_url']) ? true : false;
    ?>
    <!-- Simplicity is the ultimate sophistication. - Leonardo da Vinci -->
    <?php  //Check if we add the options for enble add and the enable add is true ?>
    @if(isset($options['enable_add']) && $options['enable_add'])
        <div class="row mb-3">
            <div class="col-md-12">
                <?php
                // 
                ?>
                @if($back_button)
                    <?php $model_url = Route::current()->parameters['model_url']; ?>
                    {{ link_to_route(Route::current()->controller->back_from_list, 'Back', null,['class' => 'btn btn-warning']) }}
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
                        @if(isset($options['enable_action']) && $options['enable_action'])
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
                                <?php 
                                    $field      =   $content['field'];
                                    $key        =   $content['key'] ?? NULL;
                                    $rel        =   $content['rel'] ?? NULL;
                                    $rel_key    =   $content['rel_key'] ?? NULL;
                                    $rel_val    =   $content['rel_val'] ?? NULL;
                                ?> 
                                @if(isset($key))
                                    <?php  ?>
                                    <td>{{ $data->$field->$key ?? '' }}</td>
                                @else
                                    @if(isset($content['type']))
                                        @switch($content['type'])
                                            @case('count')
                                                <td>{{ $data->$field()->count() }}</td>
                                                @break

                                            @case('rel_where_count')
                                                <td>
                                                    {{ 
                                                        $data->$field()->with($rel)->whereHas($rel, function($query) use($rel_key, $rel_val){
                                                            return $query->whereIn($rel_key, $rel_val);
                                                        })->count()
                                                    }}
                                                </td>
                                                @break

                                            @case('download')
                                                <td>
                                                    @if($data->$field)
                                                    <a href="{{ asset('storage/uploads/cv/'.$data->$field) }}" target="_blank">
                                                        <i class="fa fa-download" aria-hidden="true"></i>
                                                    </a>
                                                    @else
                                                    <span>CV not uploaded yet</span>
                                                    @endif
                                                </td>
                                                @break

                                            @case('roles_name')
                                                <td>{!! implode('<br/>', $data->getRoleNames()->toArray()) !!}</td>
                                                @break

                                            @case('permission_name')
                                                <td>{!! implode('<br/>', $data->getPermissionNames()->toArray()) !!}</td>
                                                @break

                                            @case('date')
                                                <td>{{ $data->$field->format($content['format']) }}</td>
                                                @break
                                        @endswitch
                                    @else
                                        <td>{{ $data->$field ?? '' }}</td>
                                    @endif
                                @endif
                            @else
                                <td>{{ $data->$content ?? '' }}</td>
                            @endif
                        @endforeach
                        @if(isset($options['enable_action']) && $options['enable_action'])
                            <td style="width:150px">
                                @if(isset($options['enable_edit']) && $options['enable_edit'])
                                    <a href="{{ route($route_as_name.'.edit', $data->id) }}" class="btn btn-block btn-sm btn-warning">Edit</a>
                                @endif
                                @if(isset($options['enable_delete']) && $options['enable_delete'])
                                    <a onclick="return confirm('Are you sure to delete?!');" href="{{ route($route_as_name.'.destroy', $data->id) }}" class="btn btn-block btn-sm btn-danger">Delete</a>
                                @endif
                                @if(isset($options['button_extends']))
                                    @foreach($options['button_extends'] as $button_extend)
                                        <?php 
                                            // Get the params to edit or using and nested routes
                                            $params             = $button_extend['params'] ?? 'id';
                                            // Show the button when the value in variable $when/$when->$when_key equal to $when_value 
                                            $when               = $button_extend['when'] ?? '';
                                            $when_key           = $button_extend['when_key'] ?? '';
                                            $when_value         = $button_extend['when_value'] ?? '';
                                            // Hide the button when the value in variable $hide_when/$hide_when->$hide_when_key equal to $hide_when_value 
                                            $hide_when          = $button_extend['hide_when'] ?? '';
                                            $hide_when_key      = $button_extend['hide_when_key'] ?? '';
                                            $hide_when_value    = $button_extend['hide_when_value'] ?? '';
                                            // Set the variable if we want to skip to check the `when` datas
                                            $skip_when          = true;
                                            $skip_hide_when     = true;
                                            // Set the variable for showing or not showing the button
                                            $state_show         = false;
                                            $route_button       = null;
                                            // Get the permissions for specified user or open for all
                                            $permissions        = $button_extend['permissions'];
                                        ?>
                                        @canany($permissions)
                                            <?php 
                                                $state_show     = true; 
                                                $skip_when      = ($when == '' ? true : false);
                                                $skip_hide_when = ($hide_when == '' ? true : false);
                                            ?>
                                        @endcanany
                                        @if($when != ''  && !$skip_when)
                                            @if($when_key != '')
                                                @if(is_array($when))
                                                    <?php $state_true = false; ?>
                                                    @foreach($when as $key => $value)
                                                        <?php 
                                                            $check_key          = $when_key[$key]; 
                                                            $check_value        = $when_value[$key];
                                                            if ($check_key      == 'count_more') {
                                                                $state_show     = $data->$value->count() > $check_value ? true : false;
                                                            }elseif ($check_key == 'count_less') {
                                                                $state_show     = $data->$value->count() < $check_value ? true : false;
                                                            }elseif ($check_key == 'count_equal') {
                                                                $state_show     = $data->$value->count() == $check_value ? true : false;
                                                            }else {
                                                                $state_show     = $data->$value->$check_key == $check_value ? true : false;
                                                            }
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
                                                    @if(is_array($when_value))
                                                    <?php $state_show = in_array($data->$when->$when_key,$when_value) ? true : false; ?>
                                                    @else
                                                    <?php $state_show = $data->$when->$when_key == $when_value ? true : false ?>
                                                    @endif
                                                @endif
                                            @else
                                                @if(is_array($when))
                                                    <?php $state_true = false; ?>
                                                    @foreach($when as $key => $value)
                                                        <?php 
                                                            $check_value    = $when_value[$key];
                                                            $state_show     = $data->$value == $check_value ? true : false;
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
                                                <?php $state_show = $data->$when == $when_value ? true : false ?>
                                                @endif
                                            @endif
                                        @endif
                                        @if($hide_when != ''  && !$skip_hide_when)
                                            @if($hide_when_key != '')
                                                @if(is_array($hide_when))
                                                    <?php $state_true = false; ?>
                                                    @foreach($hide_when as $key => $value)
                                                        <?php 
                                                            $check_key          = $hide_when_key[$key]; 
                                                            $check_value        = $hide_when_value[$key];
                                                            if ($check_key      == 'count_more') {
                                                                $state_show     = $data->$value->count() > $check_value ? false : true;
                                                            }elseif ($check_key == 'count_less') {
                                                                $state_show     = $data->$value->count() < $check_value ? false : true;
                                                            }elseif ($check_key == 'count_equal') {
                                                                $state_show     = $data->$value->count() == $check_value ? false : true;
                                                            }else {
                                                                $state_show     = $data->$value->$check_key == $check_value ? false : true;
                                                            }
                                                            if (!$state_true && !$state_show) {
                                                                $state_true = $state_show;
                                                                $state_show = false;
                                                            }else{
                                                                $state_true = $state_show;
                                                                $state_show = true;
                                                            }
                                                        ?>
                                                    @endforeach
                                                @else
                                                    @if(is_array($hide_when_value))
                                                    <?php $state_show = in_array($data->$hide_when->$hide_when_key,$hide_when_value) ? false : true; ?>
                                                    @else
                                                    <?php $state_show = $data->$hide_when->$hide_when_key == $hide_when_value ? false : true; ?>
                                                    @endif
                                                @endif
                                            @else
                                                <?php $state_show = $data->$hide_when == $hide_when_value ? false : true ?>
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