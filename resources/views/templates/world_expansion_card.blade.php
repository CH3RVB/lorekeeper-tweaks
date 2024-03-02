@php
// Get the objects needed for the cards and throw them into one array

$idsByType = [];

foreach($data as $attachment){
    $idsByType[$attachment['attachment_type']][] = $attachment['attachment_id'];
}


$locations = \App\Models\WorldExpansion\Location::where('id', $idsByType['Location'] ?? [])->get();
$figures = \App\Models\WorldExpansion\Figure::where('id', $idsByType['Figure'] ?? [])->get();
$faunas = \App\Models\WorldExpansion\Fauna::where('id', $idsByType['Fauna'] ?? [])->get();
$floras = \App\Models\WorldExpansion\Flora::where('id', $idsByType['Flora'] ?? [])->get();
$concepts = \App\Models\WorldExpansion\Concept::where('id', $idsByType['Concept'] ?? [])->get();
$factions = \App\Models\WorldExpansion\Faction::where('id', $idsByType['Faction'] ?? [])->get();
$events = \App\Models\WorldExpansion\Event::where('id', $idsByType['Event'] ?? [])->get();

// we can merge all because we only use common attributes: image, description and name
$cardData = $locations->toBase()->merge($figures)->merge($faunas)->merge($floras)->merge($concepts)->merge($factions)->merge($events);

@endphp

<div class="row justify-content-center">
@foreach($cardData as $card)

<div class="card m-2 col-xl-5 col-12 p-0 m-0">
  <div class="card-body row w-100">
    <div class="col">
        <h5 class="card-title">{{$card->name}}</h5>
        <p class="card-text">
        {!! substr(preg_replace('/#.+?\b/', '', strip_tags($card->description)), 0, 180). '... <a href="'.$card->url.'"> <i>Read More</i></a>'; !!}
        </p>
    </div>
    @if($card->thumbUrl)
        <div class="col-md-4 col-3 p-0 m-0 text-center d-flex align-items-center">
            <img src="{{ $card->thumbUrl }}" class="w-100" style="max-width:150px !important;">
        </div>
    @endif
  </div>
</div>

@endforeach
</div>