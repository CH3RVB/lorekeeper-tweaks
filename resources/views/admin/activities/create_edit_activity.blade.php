@extends('admin.layout')

@section('admin-title') Activity @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Activities' => 'admin/data/activities', ($activity->id ? 'Edit' : 'Create').' Activity' => $activity->id ? 'admin/data/activities/edit/'.$activity->id : 'admin/data/activities/create']) !!}

<h1>{{ $activity->id ? 'Edit' : 'Create' }} Activity
    @if($activity->id)
        ({!! $activity->displayName !!})
        <a href="#" class="btn btn-danger float-right delete-activity-button">Delete Activity</a>
    @endif
</h1>

{!! Form::open(['url' => $activity->id ? 'admin/data/activities/edit/'.$activity->id : 'admin/data/activities/create', 'files' => true]) !!}

<h3>Basic Information</h3>

<div class="form-group">
    {!! Form::label('Name') !!}
    {!! Form::text('name', $activity->name, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('Activity Image (Optional)') !!} {!! add_help('This image is used on the activity index as an icon.') !!}
    <div>{!! Form::file('image') !!}</div>
    <div class="text-muted">Recommended size: None (Choose a standard size for all activity images)</div>
    @if($activity->has_image)
        <div class="form-check">
            {!! Form::checkbox('remove_image', 1, false, ['class' => 'form-check-input']) !!}
            {!! Form::label('remove_image', 'Remove current image', ['class' => 'form-check-label']) !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('Description (Optional)') !!}
    {!! Form::textarea('description', $activity->description, ['class' => 'form-control wysiwyg']) !!}
</div>

<div class="form-group">
    {!! Form::checkbox('is_active', 1, $activity->id ? $activity->is_active : 1, ['class' => 'form-check-input', 'data-toggle' => 'toggle']) !!}
    {!! Form::label('is_active', 'Set Active', ['class' => 'form-check-label ml-3']) !!} {!! add_help('If turned off, the activity will not be visible to regular users.') !!}
</div>

    @if (($activity->module && $activity->module != 'collection') || !$activity->module)
        <h3>Completion Limits</h3>
        <p>Limit the number of times a user can complete this. Leave blank to allow endless completions.</p>
        <p>Set a number into number of completions. This will be applied for all time if you leave period blank, or per time period (ex: once a month, twice a week) if selected.</p>
        <p>Please note that the collection module already has a lifetime completion limit of 1 per user.</p>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('limit', 'Number of Submissions (Optional)') !!} {!! add_help('Enter a number to limit how many times a user can submit. Leave blank to allow endless submissions.') !!}
                    {!! Form::text('limit', $activity->limit, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {!! Form::label('limit_period', 'Limit Period') !!} {!! add_help('The time period that the limit is set for.') !!}
                    {!! Form::select('limit_period', $limit_periods, $activity->limit_period, ['class' => 'form-control', 'data-name' => 'limit_period']) !!}
                </div>
            </div>
        </div>
    @endif

<h3>Activity Module</h3>
An activity module defines it's behavior. Each module type will come with different settings once you've saved the activity.

<div class="form-group">
    {!! Form::select('module', [0 => 'Select a Module'] + $modules, $activity->module ?? null, ['class' => 'form-control']) !!}
</div>


<div class="text-right">
    {!! Form::submit($activity->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}
@if($activity->module)
    <h3 class="mt-5">Module Settings</h3>
    {!! Form::open(['url' => 'admin/data/activities/module/'.$activity->id]) !!}
        @if(View::exists('admin.activities.modules.'.$activity->module))
            @include('admin.activities.modules.'.$activity->module, ['settings' => $activity->data])
        @endif
        
        <div class="text-right">
            {!! Form::submit('Edit', ['class' => 'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
    @if(View::exists('admin.activities.modules.'.$activity->module.'_post'))
        @include('admin.activities.modules.'.$activity->module.'_post')
    @endif
@endif
 
@endsection

@section('scripts')
@parent
@if(View::exists('admin.activities.modules.'.$activity->module.'_js'))
    @include('admin.activities.modules.'.$activity->module.'_js')
@endif
<script>
$( document ).ready(function() {
    $('.delete-activity-button').on('click', function(e) {
        e.preventDefault();
        loadModal("{{ url('admin/data/activities/delete') }}/{{ $activity->id }}", 'Delete Activity');
    });
});
    
</script>
@endsection