@extends('layouts.app')

@section('css-header')
<!-- Css -->
<link rel="stylesheet" href="{{ asset('vendors/dataTable/dataTables.min.css') }}" type="text/css">
@endsection

@section('content')
<div class="container-fluid">
    <x-content.TableList :options=$view_options :contents="$contents" :datas="$datas" />
</div>
@endsection

@section('js-footer')
<!-- Javascript -->
<script src="{{ asset('vendors/dataTable/jquery.dataTables.min.js') }}"></script>
<!-- Bootstrap 4 and responsive compatibility -->
<script src="{{ asset('vendors/dataTable/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendors/dataTable/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/js/examples/datatable.js') }}"></script>
@endsection