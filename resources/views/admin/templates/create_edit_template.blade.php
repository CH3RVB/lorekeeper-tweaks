@extends('admin.layout')

@section('admin-title') Templates @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Templates' => 'admin/templates', ($template->id ? 'Edit' : 'Create').' Template' => $template->id ? 'admin/templates/edit/'.$template->id : 'admin/templates/create']) !!}

<h1>{{ $template->id ? 'Edit' : 'Create' }} Template
    @if($template->id)
        <a href="#" class="btn btn-outline-danger float-right delete-template-button">Delete Template</a>
    @endif
</h1>

{!! Form::open(['url' => $template->id ? 'admin/templates/edit/'.$template->id : 'admin/templates/create', 'files' => true]) !!}

<h3>Basic Information</h3>

<div class="form-group">
    {!! Form::label('Name') !!}
    {!! Form::text('name', $template->name, ['class' => 'form-control']) !!}
</div>



<h3>Template Tag</h3>
<p>The template tag indicates what type of presentation this tag will have, and the data you can enter. Simply select one and save/create, you will be able to enter data afterwards.</p>

<div class="form-group">
    {!! Form::label('Tag') !!} 
    {!! Form::select('tag', $tags, $template->tag, ['class' => 'form-control', 'id' => 'tag']) !!}
</div>


<hr>
@if(isset($template->id))
<h3>Tag Data</h3>
<p>The data that should be rendered when this tag is used.</p>

    @if(View::exists('admin.templates.tags.'.$template->tag))
        @include('admin.templates.tags.'.$template->tag, ['template' => $template])
    @endif

@endif

<div class="text-right">
    {!! Form::submit($template->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}



@endsection

@section('scripts')
@parent
<script>
$( document ).ready(function() {

    $('.delete-template-button').on('click', function(e) {
        e.preventDefault();
        loadModal("{{ url('admin/templates/delete') }}/{{ $template->id }}", 'Delete Template');
    });

});

</script>
@endsection
