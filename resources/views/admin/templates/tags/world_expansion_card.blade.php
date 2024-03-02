
@php
// This file represents a common source and definition for assets used in attachment_select
// While it is not per se as tidy as defining these in the controller(s),
// doing so this way enables better compatibility across disparate extensions
$items = \App\Models\Item\Item::orderBy('name')->pluck('name', 'id');
$newses = \App\Models\News::orderBy('title')->pluck('title', 'id');
$prompts = \App\Models\Prompt\Prompt::orderBy('name')->pluck('name', 'id');
$locations = \App\Models\WorldExpansion\Location::getLocationsByType();
$figures = \App\Models\WorldExpansion\Figure::getFiguresByCategory();
$faunas = \App\Models\WorldExpansion\Fauna::getFaunasByCategory();
$floras = \App\Models\WorldExpansion\Flora::getFlorasByCategory();
$concepts = \App\Models\WorldExpansion\Concept::getConceptsByCategory();
$factions = \App\Models\WorldExpansion\Faction::getFactionsByType();
$events = \App\Models\WorldExpansion\Event::getEventsByCategory();
@endphp

<h5>World Extension Card Template</h5>
Attach all the objects you would like to create cards of here.

@if($template->id)
<div class="card mb-3">
    <div class="card-header h3">
        <div class="float-right"><a href="#" class="btn btn-sm btn-primary" id="addAttachment">Add Attachment</a></div>
        Attachments
    </div>
    <div class="card-body">
        <div class="row no-gutters" id="attachments">
            <div class="row col-12  logs-table-header align-items-center ">
                <div class="col-4 py-1 h-100 col-md-4 font-weight-bold">
                    <div class="logs-table-cell">Attachment Type</div>
                </div>
                <div class="col-4 py-1 h-100 col-md-3 font-weight-bold">
                    <div class="logs-table-cell">Attachment</div>
                </div>
            </div>
            <div id="attachmentsBody" class="row col-12 px-0">
                @if($template->getTemplateData())
                @foreach($template->getTemplateData() as $attachment)
                <div class="row col-12 py-1 logs-table-row attachment-row">
                    <div class="col-6 col-md-4">{!! Form::select('data[attachment_type][]', [
                        'Figure' => 'Figure', 'Fauna' => 'Fauna', 'Flora' => 'Flora',
                        'Faction' => 'Faction', 'Concept' => 'Concept', 'Location' => 'Location', 'Event' => 'Event'
                        ], $attachment['attachment_type'], ['class' => 'form-control attachment-type', 'placeholder' =>
                        'Select Attachment Type']) !!}</div>
                    <div class="col-6 col-md-6 attachment-row-select">
                        @if($attachment['attachment_type'] == 'Figure')
                        {!! Form::select('data[attachment_id][]', $figures, $attachment['attachment_id'], ['class' =>
                        'form-control figure-select', 'placeholder' => 'Select Figure']) !!}
                        @elseif($attachment['attachment_type'] == 'Fauna')
                        {!! Form::select('data[attachment_id][]', $faunas, $attachment['attachment_id'], ['class' =>
                        'form-control fauna-select', 'placeholder' => 'Select Fauna']) !!}
                        @elseif($attachment['attachment_type'] == 'Flora')
                        {!! Form::select('data[attachment_id][]', $floras, $attachment['attachment_id'], ['class' =>
                        'form-control flora-select', 'placeholder' => 'Select Flora']) !!}
                        @elseif($attachment['attachment_type'] == 'Faction')
                        {!! Form::select('data[attachment_id][]', $factions, $attachment['attachment_id'], ['class' =>
                        'form-control faction-select', 'placeholder' => 'Select Faction']) !!}
                        @elseif($attachment['attachment_type'] == 'Concept')
                        {!! Form::select('data[attachment_id][]', $concepts, $attachment['attachment_id'], ['class' =>
                        'form-control concept-select', 'placeholder' => 'Select Concept']) !!}
                        @elseif($attachment['attachment_type'] == 'Location')
                        {!! Form::select('data[attachment_id][]', $locations, $attachment['attachment_id'], ['class' =>
                        'form-control location-select', 'placeholder' => 'Select Location']) !!}
                        @elseif($attachment['attachment_type'] == 'Event')
                        {!! Form::select('data[attachment_id][]', $events, $attachment['attachment_id'], ['class' =>
                        'form-control event-select', 'placeholder' => 'Select Event']) !!}
                        @endif
                    </div>
                    <div class="col-6 col-md text-right"><a href="#"
                            class="btn btn-danger remove-attachment-button">Remove</a></div>
                </div>
                @endforeach
                @endif

            </div>
        </div>

    </div>
</div>
@endif


<div id="attachmentRowData" class="row col-12 mt-1 px-0 hide">
    <div class="table table-sm px-0">
        <div id="attachmentRow" class="row px-0">
            <div class="attachment-row col-12 row py-1 logs-table-row">
                <div class="col-6 col-md-4">
                    {!! Form::select('data[attachment_type][]', [
                    'Figure'    => 'Figure',    'Fauna'     => 'Fauna',     'Flora'     => 'Flora',
                    'Faction'   => 'Faction',   'Concept'   => 'Concept',   'Location'  => 'Location',  'Event' => 'Event'
                ], null, ['class' => 'form-control attachment-type', 'placeholder' => 'Select Attachment Type']) !!}
                </div>
                <div class="col-6 col-md-6 attachment-row-select"></div>
                <div class="col-6 col-md text-right"><a href="#" class="btn btn-danger remove-attachment-button">Remove</a></div>
            </div>
        </div>
    </div>

    {!! Form::select('data[attachment_id][]', $locations, null, ['class' => 'form-control location-select', 'placeholder' => 'Select Location']) !!}
    {!! Form::select('data[attachment_id][]', $figures, null, ['class' => 'form-control figure-select', 'placeholder' => 'Select Figure']) !!}
    {!! Form::select('data[attachment_id][]', $faunas, null, ['class' => 'form-control fauna-select', 'placeholder' => 'Select Fauna']) !!}
    {!! Form::select('data[attachment_id][]', $floras, null, ['class' => 'form-control flora-select', 'placeholder' => 'Select Flora']) !!}
    {!! Form::select('data[attachment_id][]', $factions, null, ['class' => 'form-control faction-select', 'placeholder' => 'Select Faction']) !!}
    {!! Form::select('data[attachment_id][]', $concepts, null, ['class' => 'form-control concept-select', 'placeholder' => 'Select Concept']) !!}
    {!! Form::select('data[attachment_id][]', $events, null, ['class' => 'form-control event-select', 'placeholder' => 'Select Event']) !!}
</div>

@section('scripts')
@parent
@include('js._attachment_js')
<script>

</script>
@endsection