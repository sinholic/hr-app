@extends('layouts.app', ['detail' => $detail ?? NULL])

@section('css-header')
<!-- Css -->
<link rel="stylesheet" href="{{ asset('vendors/dataTable/dataTables.min.css') }}" type="text/css">
@endsection

@section('content')
    @if(isset($filters))
        <x-content.DataFilter :filters=$filters />
    @endif
    <x-content.TableList :options=$view_options :contents="$contents" :datas="$datas" />
@endsection

@section('js-footer')
<!-- Javascript -->
<script src="{{ asset('vendors/dataTable/jquery.dataTables.min.js') }}"></script>
<!-- Bootstrap 4 and responsive compatibility -->
<script src="{{ asset('vendors/dataTable/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendors/dataTable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/js/examples/datatable.js') }}"></script>
<script>
    // setInterval(function() {
    //     window.location.reload();
    // }, 60000 ); 
</script>
@endsection