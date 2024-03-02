@php
    $recipes = \App\Models\Recipe\Recipe::all()->pluck('name', 'id');
@endphp

<h3>Recipe Embed</h3>

<p>You will be able to embed the chosen recipe's card onto a page using the tag.</p>
<p>By default this simply uses the "world.recipes._recipe_entry" file so tweak it or link to another file if you want to change the layout of the embed!</p>
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('data[recipe_id]', 'Recipe') !!}
            {!! Form::select('data[recipe_id]', $recipes, $template->data['recipe_id'] ?? null, [
                'class' => 'form-control selectize',
                'placeholder' => 'Select Recipe',
            ]) !!}
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.selectize').selectize();
    });
</script>
