<style>
.grayscale {
    -webkit-filter: grayscale(100%);
    /* Safari 6.0 - 9.0 */
    filter: grayscale(100%);
}

.typed-cursor {
    display: none;
}
</style>

@php
$dialogue = $data["dialogue"];
$characters = $data["characters"];
$id = $tag->id;
@endphp

<!---Version: show all chars at the top of box and highlight the speaker--->
<div class="row rounded" style="background:url({{ ($data['background'] != null) ? $data['background'] : '' }});background-size:cover;">
    @foreach($characters as $name => $images)
    <div class="col-lg col-6 justify-content-center d-flex align-items-end"
        id="{{ str_replace(' ', '', $name) }}-image-container-{{$id}}">
        <img src="{{ $images[0]}}" class="rounded grayscale" style="max-width:100%;">
    </div>
    @endforeach
</div>
<div class="border rounded mt-1">
    @foreach($dialogue as $dialogueStep)
    <div class="{{ ($loop->index == 0) ? '' : 'hide' }} dialogue-{{$id}}" id="dialogue-{{$loop->index}}-{{$id}}">
        <img src="{{ $dialogueStep['image'] ?? null}}" class="rounded hide" style="max-width:100%;"
            id="image-{{$loop->index}}-{{$id}}">
        <div class="bg-light ml-2 p-0 text-left">
            <h4 id="speaker-{{$loop->index}}-{{$id}}">{{ $dialogueStep['name']}}</h4>
        </div>
        <div class="hide" id="text-{{$loop->index}}-{{$id}}">
            <p>{{ $dialogueStep['text']}}</p>
        </div>
        <div class="p-4 text-justify" style="height:100px;overflow:auto;" id="text-container-{{$loop->index}}-{{$id}}"></div>
    </div>
    @endforeach
    <div class="bg-light text-left">
        <div id="previous-button-{{$id}}" class="btn btn-primary disabled"><i class="fa fa-chevron-left"></i></div>
        <div id="next-button-{{$id}}" class="btn btn-primary float-right"><i class="fa fa-chevron-right"></i></div>
    </div>
</div>



<script>
$(document).ready(function() {
    var step = 0;
    var dialogueSteps = $(".dialogue-{{ $id }}").length;
    updateSpeaker();
    if (step <= 0) $('#previous-button-{{ $id }}').addClass('disabled');
    if (step + 1 >= dialogueSteps) $('#next-button-{{ $id }}').addClass('disabled');

    $('#next-button-{{ $id }}').on('click', function(e) {
        if (!$('#next-button-{{ $id }}').hasClass('disabled')) {
            var currentDialogue = document.getElementById('dialogue-' + step + '-{{ $id }}');
            step += 1;
            var nextDialogue = document.getElementById('dialogue-' + step + '-{{ $id }}');
            currentDialogue.classList.add('hide');
            nextDialogue.classList.remove('hide');
            $('#previous-button-{{ $id }}').removeClass('disabled');
            if (step + 1 >= dialogueSteps) $('#next-button-{{ $id }}').addClass('disabled');
            updateSpeaker();
        }
    });

    $('#previous-button-{{ $id }}').on('click', function(e) {
        if (!$('#previous-button-{{ $id }}').hasClass('disabled')) {
            var currentDialogue = document.getElementById('dialogue-' + step + '-{{ $id }}');
            step -= 1;
            var previousDialogue = document.getElementById('dialogue-' + step + '-{{ $id }}');
            currentDialogue.classList.add('hide');
            previousDialogue.classList.remove('hide');
            if (step <= 0) $('#previous-button-{{ $id }}').addClass('disabled');
            $('#next-button-{{ $id }}').removeClass('disabled');
            updateSpeaker();
        }
    });

    function updateSpeaker() {
        if (step > 0) $('#image-' + (step - 1) + '-{{ $id }}').addClass('grayscale');
        if (step < dialogueSteps) $('#image-' + (step + 1) + '-{{ $id }}').addClass('grayscale');

        var speaker = document.getElementById('speaker-' + step + '-{{ $id }}').innerHTML;

        //typed animation of new speaker
        new Typed('#text-container-' + step + '-{{ $id }}', {
            stringsElement: '#text-' + step + '-{{ $id }}',
            typeSpeed: 20,
        });

        //update the images, turn non speaking chars grey and all
        var imageContainer = $('#' + speaker.replace(/ /g, '') + '-image-container-{{ $id }}');
        var replacementImg = $('#image-' + step + '-{{ $id }}').clone();
        replacementImg.removeClass('hide');
        replacementImg.removeClass('grayscale');
        imageContainer.empty();
        imageContainer.append(replacementImg);
    }
});
</script>