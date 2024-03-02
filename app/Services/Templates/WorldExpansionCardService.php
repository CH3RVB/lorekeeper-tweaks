<?php namespace App\Services\Templates;

use App\Services\Service;

use DB;

class WorldExpansionCardService extends Service
{
    /*
    |--------------------------------------------------------------------------
    | WorldExpansionCardService 
    |--------------------------------------------------------------------------
    |
    | Handles the editing and usage of WorldExpansionCard type templates.
    |
    */

    /**
     * Get the template data in a preferred format.
     */
    public function getTemplateData($tag) {

        $attachments = [];
        // [["attachment_id" => 2, "attachment_type" => "Fauna"]]

        if(isset($tag->data['attachment_id'])){
            foreach($tag->data['attachment_id'] as $key=>$name){
                $attachments[] = ['attachment_id' => $tag->data['attachment_id'][$key], 'attachment_type' => $tag->data['attachment_type'][$key]];
            }
        }

        return $attachments;
    }

}