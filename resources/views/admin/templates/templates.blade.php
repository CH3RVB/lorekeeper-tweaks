@extends('admin.layout')

@section('admin-title') Templates @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Templates' => 'admin/templates']) !!}

<h1>Templates</h1>

<p>These templates hold the data for usage with a template tag of your choice. They are meant to create reusable html snippets that can be included on any text field/description. 
    Each tag defines different data and presentation of said data. For example, creating a template using the dialogue tag will give you input to create a dialogue, while the pure tag just asks for any kind of static html.
    To use the template, simply copy its identifier into any description you wish, such as sales or shops.</p>

<div class="text-right mb-3">
    <a class="btn btn-primary" href="{{ url('admin/templates/create') }}"><i class="fas fa-plus"></i> Create New Template</a>
</div>

<div>
    {!! Form::open(['method' => 'GET', 'class' => 'form-inline justify-content-end']) !!}
        <div class="form-group mr-3 mb-3">
            {!! Form::text('name', Request::get('name'), ['class' => 'form-control', 'placeholder' => 'Name']) !!}
        </div>
        <div class="form-group mr-3 mb-3">
            {!! Form::select('tag', $tags, Request::get('tag'), ['class' => 'form-control']) !!}
        </div>
        <div class="form-group mb-3">
            {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
</div>

@if(!count($templates))
    <p>No templates found.</p>
@else
    {!! $templates->render() !!}

        <div class="row ml-md-2 mb-4">
          <div class="d-flex row flex-wrap col-12 pb-1 px-0 ubt-bottom">
            <div class="col-3 col-md-3 font-weight-bold">Name</div>
            <div class="col-3 col-md-3 font-weight-bold">Tag</div>
            <div class="col-3 col-md-3 font-weight-bold">Identifier</div>
            <div class="col-3 col-md-1 font-weight-bold"></div>
          </div>
          @foreach($templates as $template)
          <div class="d-flex row flex-wrap col-12 mt-1 pt-2 px-0 ubt-top">
            <div class="col-3 col-md-3"> {{ $template->name }} </div>
            <div class="col-3 col-md-3"> {{ $template->tag }} </div>
            <div class="col-3 col-md-3"> #{{ $template->tag }}{{$template->id}}</div>
            <div class="col-3 col-md-1 text-right">
              <a href="{{ url('admin/templates/edit/'.$template->id) }}"  class="btn btn-primary py-0 px-2">Edit</a>
            </div>
          </div>
          @endforeach
        </div>

    {!! $templates->render() !!}
@endif

@endsection

@section('scripts')
@parent
@endsection
