<h4>Dialogue Template</h4>

<h5>Background</h5>
<p>If you want a background image be rendered behind the characters as they speak.</p>
<table class="table table-sm" id="characterTable">
    <thead>
        <tr>
            <th width="20%">Background Image URL (optional)</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{!! Form::text('data[background]', $template->data['background'] ?? '', ['class' => 'form-control']) !!}</td>
        </tr>
    </tbody>
</table>

<h5>Character Images</h5>
<p>Sets up speaking characters. Can be left empty if you want no images for the dialogue. You can use the file manager to keep track of your character dialogue images, or any offsite host.
    <b>Images should ideally be of the same height/size.</b> But feel free to play around with it!
</p>

<table class="table table-sm" id="characterTable">
    <thead>
        <tr>
            <th width="20%">Name</th>
            <th width="20%">Image 1 URL (default) {!! add_help('The default image used for the character if no other is specified.') !!}</th>
            <th width="20%">Image 2 URL (optional) {!! add_help('Additional image for eg. different emotions.') !!}</th>
            <th width="20%">Image 3 URL (optional) {!! add_help('Additional image for eg. different emotions.') !!}</th>
            <th width="20%">Image 4 URL (optional) {!! add_help('Additional image for eg. different emotions.') !!}</th>
        </tr>
    </thead>
    <tbody id="characterTableBody">
        @if(isset($template->data['character_name']))
            @foreach($template->data['character_name'] as $name)
                @if($name != null)
                <tr class="character-row">
                    <td>{!! Form::text('data[character_name][]', $name, ['class' => 'form-control']) !!}</td>
                    <td>{!! Form::text('data[character_default][]', $template->data['character_default'][$loop->index] ?? '', ['class' => 'form-control']) !!}</td>
                    <td>{!! Form::text('data[character_emotion_1][]',  $template->data['character_emotion_1'][$loop->index] ?? '', ['class' => 'form-control']) !!}</td>
                    <td>{!! Form::text('data[character_emotion_2][]',  $template->data['character_emotion_2'][$loop->index] ?? '', ['class' => 'form-control']) !!}</td>
                    <td>{!! Form::text('data[character_emotion_3][]',  $template->data['character_emotion_3'][$loop->index] ?? '', ['class' => 'form-control']) !!}</td>
                    <td class="text-right"><a href="#" class="btn btn-danger remove-character-button">Remove</a></td>
                </tr>
                @endif
            @endforeach
        @endif
    </tbody>
</table>
<div class="text-right mb-3">
    <a href="#" class="btn btn-outline-info" id="addCharacter">Add Character</a>
</div>

<div id="characterRowData" class="hide">
    <table class="table table-sm">
        <tbody id="characterRow">
            <tr class="character-row">
            <td>{!! Form::text('data[character_name][]', '', ['class' => 'form-control']) !!}</td>
                    <td>{!! Form::text('data[character_default][]', '', ['class' => 'form-control']) !!}</td>
                    <td>{!! Form::text('data[character_emotion_1][]', '', ['class' => 'form-control']) !!}</td>
                    <td>{!! Form::text('data[character_emotion_2][]', '', ['class' => 'form-control']) !!}</td>
                    <td>{!! Form::text('data[character_emotion_3][]', '', ['class' => 'form-control']) !!}</td>
                <td class="text-right"><a href="#" class="btn btn-danger remove-character-button">Remove</a></td>
            </tr>
        </tbody>
    </table>
</div>

<h5>Dialogue</h5>
<p>Sets up the actual dialogue. Make sure that the character names match the names above if you intend to use images. 
    You can specify which character image should go with the text, for such things as different expressions.</p>


<table class="table table-sm" id="dialogueTable">
    <thead>
        <tr>
            <th width="20%">Name</th>
            <th width="10%">Image Number</th>
            <th width="60%">Text</th>
        </tr>
    </thead>
    <tbody id="dialogueTableBody">
        @if(isset($template->data['dialogue_name']))
            @foreach($template->data['dialogue_name'] as $dialogue)
                <tr class="dialogue-row">
                    <td>{!! Form::text('data[dialogue_name][]', $dialogue, ['class' => 'form-control']) !!}</td>
                    <td>{!! Form::text('data[image_number][]', $template->data['image_number'][$loop->index] ?? '', ['class' => 'form-control']) !!}</td>
                    <td>{!! Form::text('data[text][]', $template->data['text'][$loop->index] ?? '', ['class' => 'form-control']) !!}</td>
                    <td class="text-right"><a href="#" class="btn btn-danger remove-dialogue-button">Remove</a></td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
<div class="text-right mb-3">
    <a href="#" class="btn btn-outline-info" id="addDialogue">Add Dialogue</a>
</div>

<div id="dialogueRowData" class="hide">
    <table class="table table-sm">
        <tbody id="dialogueRow">
            <tr class="dialogue-row">
                <td>{!! Form::text('data[dialogue_name][]', '', ['class' => 'form-control']) !!}</td>
                <td>{!! Form::text('data[image_number][]', 1, ['class' => 'form-control']) !!}</td>
                <td>{!! Form::text('data[text][]', '', ['class' => 'form-control']) !!}</td>
                <td class="text-right"><a href="#" class="btn btn-danger remove-dialogue-button">Remove</a></td>
            </tr>
        </tbody>
    </table>
</div>


<script>
$( document ).ready(function() {    
    var $characterTable  = $('#characterTableBody');
    var $characterRow = $('#characterRow').find('.character-row');
    var $dialogueTable  = $('#dialogueTableBody');
    var $dialogueRow = $('#dialogueRow').find('.dialogue-row');

    $('#characterTableBody .selectize').selectize();
    attachRemoveListener($('#characterTableBody .remove-character-button'));
    $('#dialogueTableBody .selectize').selectize();
    attachRemoveListener($('#dialogueTableBody .remove-dialogue-button'));
    
    $('#addCharacter').on('click', function(e) {
        e.preventDefault();
        var $clone = $characterRow.clone();
        $characterTable.append($clone);
        attachRemoveListener($clone.find('.remove-character-button'));
    });

    $('#addDialogue').on('click', function(e) {
        e.preventDefault();
        var $clone = $dialogueRow.clone();
        $dialogueTable.append($clone);
        attachRemoveListener($clone.find('.remove-dialogue-button'));
    });

    function attachRemoveListener(node) {
        node.on('click', function(e) {
            e.preventDefault();
            $(this).parent().parent().remove();
        });
    }

});
    
</script>