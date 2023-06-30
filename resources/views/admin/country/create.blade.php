@extends('admin/layout')
<!--For Menu select class active-->
@section('master_menu_open','menu-open')
@section('master_active','active')
@section('country_select','active')

@section('content')
<!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add new country</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Add new country</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Add new</h3>

          <div class="card-tools">
            <a href="{{ url('admin/countries')}}" class="btn  btn-flat btn-default" title="List">
              <i class="fa fa-list"></i><span class="hidden-xs"> Back list</span>
            </a>

            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <form action="{{ route('countries.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="card-body">

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Name</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                @if($errors->has('name'))
                <span class="error invalid-feedback" style="display:block">{{ $errors->first('name') }}</span>
                @endif
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Country Code</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="code" value="{{ old('code') }}">
                @if($errors->has('code'))
                <span class="error invalid-feedback" style="display:block">{{ $errors->first('code') }}</span>
                @endif
              </div>
            </div>

            <div class="form-group row">
              <label class="col-sm-2 col-form-label">Phone Prefix</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="phone_no_prefix" value="{{ old('phone_no_prefix') }}">
                @if($errors->has('phone_no_prefix'))
                <span class="error invalid-feedback" style="display:block">{{ $errors->first('phone_no_prefix') }}</span>
                @endif
              </div>
            </div>
          
        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </form>
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->



@endsection


