@php
    $collections = \App\Models\Collection\Collection::all()->pluck('name', 'id');
@endphp

<h3>Collection Embed</h3>

<p>You will be able to embed the chosen collection's card onto a page using the tag.</p>
<p>By default this simply uses the "world.collections._collection_entry" file so tweak it or link to another file if you want to change the layout of the embed!</p>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('data[collection_id]', 'Collection') !!}
            {!! Form::select('data[collection_id]', $collections, $template->data['collection_id'] ?? null, [
                'class' => 'form-control selectize',
                'placeholder' => 'Select Collection',
            ]) !!}
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.selectize').selectize();
    });
</script>
