<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Template tags
    |--------------------------------------------------------------------------
    |
    | This is a list of usable template tags.
    | Add tags here to make them selectable in the admin panel.
    | The key must be unique, but names do not have to be.
    | Requires is for tags that require another extension. Use a class name that
    | is contained only in said extension here, as it will check for its existence.
    |
    */

    'pure' => [
        'name' => 'Pure HTML',
        'description' => 'Allows you to add any sort of premade html for easy reuse across the whole site via a template tag.'
    ],
    'dialogue' => [
        'name' => 'Dialogue',
        'description' => 'Allows the creation of dialogue to be clicked through by users.'
    ],
    'world_expansion_card' => [
        'name' => 'World Expansion Card',
        'description' => 'Allows you to easily add a little preview card of any world extension object of your choice.',
        'requires' => '\App\Services\WorldExpansion\WorldExpansionService'
    ],

];
