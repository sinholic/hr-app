@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <x-content.TableList :options=$view_options :contents="$contents" :datas="$datas" />
</div>
@endsection

@section('js-footer')
<!-- Circle progress -->
<script src="{{ asset('vendors/circle-progress/circle-progress.min.js') }}"></script>
<!-- App scripts -->
<script src="{{ asset('assets/js/app.js') }}"></script>
@endsection