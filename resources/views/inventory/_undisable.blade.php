<li class="list-group-item">
    <a class="card-title h5 collapse-title" data-toggle="collapse" href="#undisableform"> Undisable Character</a>
    <div id="undisableform" class="collapse">
        {!! Form::hidden('tag', $tag->tag) !!}
        <p>This item will undisable the selected character. This action is not reversible. Are you sure you want to use this item?</p>
        <div class="form-group">
            {!! Form::label('Character') !!}
            {!! Form::select('undisable_character',Auth::user()->characters()->where('is_disabled', 1)->get()->pluck('fullName', 'id')->toArray(),null, ['class' => 'form-control', 'placeholder' => 'Select a Character'],) !!}
        </div>
        <div class="text-right">
            {!! Form::button('Use', ['class' => 'btn btn-primary', 'name' => 'action', 'value' => 'act', 'type' => 'submit']) !!}
        </div>
    </div>
</li>
