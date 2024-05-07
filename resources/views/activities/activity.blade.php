@extends('activities.layout')

@section('activities-title')
    {{ $activity->name }}
@endsection

@section('activities-content')
    {!! breadcrumbs(['Activities' => 'activities', $activity->name => $activity->url]) !!}

    <h1>
        {{ $activity->name }}
    </h1>

    <div class="text-center">
        <p>{!! $activity->parsed_description !!}</p>
    </div>

    @if ($user && $activity->checkLimit($activity, $user))
        @if (isset($activity->limit) && isset($activity->limit_period))
            <div class="alert alert-info text-center">You can complete this activity {{ $activity->limit }} time(s)
                {{ $activity->limit_period ? ' per ' . strtolower($activity->limit_period) : '' }}.</div>
        @endif
        
        @if (View::exists('activities.modules.' . $activity->module))
            @include('activities.modules.' . $activity->module, ['settings' => $activity->data])
        @endif
    @else
        <div class="alert alert-danger text-center">
            You have already completed this activity the maximum number of times.
        </div>
    @endif
@endsection

@section('scripts')
    @parent
@endsection
