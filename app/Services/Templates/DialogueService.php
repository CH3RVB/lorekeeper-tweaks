<?php namespace App\Services\Templates;

use App\Services\Service;

use DB;

class DialogueService extends Service
{
    /*
    |--------------------------------------------------------------------------
    | Dialogue Service
    |--------------------------------------------------------------------------
    |
    | Handles the editing and usage of Dialogue type templates.
    |
    */

    /**
     * Get the template data in a preferred format.
     */
    public function getTemplateData($tag) {

        $characters = [];
        foreach($tag->data['character_name'] as $key=>$name){
            $characters[$name][] = $tag->data['character_default'][$key] ?? null;
            $characters[$name][] = $tag->data['character_emotion_1'][$key] ?? null;
            $characters[$name][] = $tag->data['character_emotion_2'][$key] ?? null;
            $characters[$name][] = $tag->data['character_emotion_3'][$key] ?? null;
        }
        $dialogue = [];
        foreach($tag->data['dialogue_name'] as $key=>$name){
            $dialogue[$key]['name'] = $name;
            $dialogue[$key]['text'] = $tag->data['text'][$key];
            if(isset($characters[$name]))
                $dialogue[$key]['image'] = ($characters[$name][(int)$tag->data['image_number'][$key] - 1]) ? $characters[$name][(int)$tag->data['image_number'][$key] - 1] : $characters[$name][0];
        }
        return ['dialogue' => $dialogue, 'characters' => $characters, 'background' => $tag->data['background'] ?? null];
    }

}