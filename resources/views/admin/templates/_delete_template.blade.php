@if($template)
    {!! Form::open(['url' => 'admin/templates/delete/'.$template->id]) !!}

    <p>You are about to delete the template <strong>{{ $template->name }}</strong>. This is not reversible.</p>
    <p>Are you sure you want to delete <strong>{{ $template->name }}</strong>?</p>

    <div class="text-right">
        {!! Form::submit('Delete Template', ['class' => 'btn btn-danger']) !!}
    </div>

    {!! Form::close() !!}
@else 
    Invalid template selected.
@endif