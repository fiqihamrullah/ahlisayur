@extends('adminlte::page')

@section('title', $title)

@section('content_header')
   <h1>{{ $title }} </h1>
    <x-adminlte-alert theme="info" title="Info">
     {{ $sub_title }}
   </x-adminlte-alert>
@stop

@section('content')
<x-adminlte-card theme="success" theme-mode="outline">
        {{-- Tombol --}}
        <div class="row">

              <div class="col-md-6  text-left">

              </div>

                <div class="col-md-6  text-right">
                    <x-adminlte-button class="btn-lg" id="btnAdd" type="button"  label="{{ __('common.button_tambah') }}" theme="success" icon="fas fa-lg fa-plus" /> 
               </div>
        </div>

        <br/>
        {{-- Tabel --}}
        <div class="row">
                <x-adminlte-datatable id="table" :heads="$heads" :config="$config"   head-theme="dark" class="bg-light" 
                    striped hoverable with-footer footer-theme="dark" beautify/>

        </div>
    
</x-adminlte-card>



@stop


@section('plugins.Datatables', true)
@section('plugins.Toastr', true)
@section('plugins.Sweetalert2', true)

@section('js')

   <script type="text/javascript" src="{{ asset('js/category.js') }}"></script>
   <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    
    {!! JsValidator::formRequest('App\Http\Requests\CategoryRequest', '#formCategory') !!}
   

@stop
 
