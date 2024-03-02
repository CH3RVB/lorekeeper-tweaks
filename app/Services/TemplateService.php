<?php namespace App\Services;

use App\Services\Service;

use DB;
use Config;

use App\Models\TemplateTag;

class TemplateService extends Service
{
    /*
    |--------------------------------------------------------------------------
    | Template Service
    |--------------------------------------------------------------------------
    |
    | Handles the creation and editing of template tags.
    |
    */

    /**********************************************************************************************

        TEMPLATE TAGS

    **********************************************************************************************/


    /**
     * Creates a template tag.
     *
     * @param  \App\Models\Template\Template  $template
     * @param  string                 $tag
     * @return string|bool
     */
    public function createTemplate($data)
    {
        DB::beginTransaction();

        try {
            if(TemplateTag::where('name', $data['name'])->exists()) throw new \Exception("This template name already exists.");

            $tag = TemplateTag::create([
                'name' => $data['name'],
                'tag' => $data['tag']
            ]);

            return $this->commitReturn($tag);
        } catch(\Exception $e) {
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    
    /**
     * Updates a template tag.
     */
    public function updateTemplate($template, $data)
    {
        DB::beginTransaction();

        try {
            if(TemplateTag::where('name', $data['name'])->where('id', '!=', $template->id)->exists()) throw new \Exception("This template name already exists.");

            //remove null values from all arrays as we do not want to save them
            $data['data'] = $this->walkRecursiveRemove($data['data'], function ($value) {
                return $value === null || $value === '';
            });

            $template->update($data);

            return $this->commitReturn($template);
        } catch(\Exception $e) { 
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    private function walkRecursiveRemove (array $array, callable $callback) { 
        foreach ($array as $k => $v) { 
            if (is_array($v)) { 
                $array[$k] = $this->walkRecursiveRemove($v, $callback); 
            } else { 
                if ($callback($v, $k)) { 
                    unset($array[$k]); 
                } 
            } 
        } 
    
        return $array; 
    } 

    /**
     * Deletes a template tag.
     *
     * @param  \App\Models\SitePage  $news
     * @return bool
     */
    public function deleteTemplate($template)
    {
        DB::beginTransaction();

        try {

            $template->delete();

            return $this->commitReturn(true);
        } catch(\Exception $e) { 
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }
}
