<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use Auth;
use Config;

use App\Models\TemplateTag;
use App\Services\TemplateService;

use App\Http\Controllers\Controller;

class TemplateController extends Controller
{
    /**
     * Shows the template index.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getIndex(Request $request)
    {
        $query = TemplateTag::query();
        $data = $request->only(['tag', 'name']);
        if(isset($data['tag']) && $data['tag'] != 'none')
            $query->where('tag', $data['tag']);
        if(isset($data['name']))
            $query->where('name', 'LIKE', '%'.$data['name'].'%');

        return view('admin.templates.templates', [
            'templates' => $query->paginate(20)->appends($request->query()),
            'tags' => ['none' => 'Any Tag'] + $this->getTemplateTags()
        ]);
    }
    
    /**
     * Shows the create template page. 
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getCreateTemplate()
    {
        return view('admin.templates.create_edit_template', [
            'template' => new TemplateTag,
            'tags' => $this->getTemplateTags()
        ]);
    }
    
    /**
     * Shows the edit template page.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getEditTemplate($id)
    {
        $template = TemplateTag::find($id);
        if(!$template) abort(404);
        return view('admin.templates.create_edit_template', [
            'template' => $template,
            'tags' => $this->getTemplateTags()
        ]);
    }

    /**
     * Creates or edits a template.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\TemplateService  $service
     * @param  int|null                  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreateEditTemplate(Request $request, TemplateService $service, $id = null)
    {
        $id ? $request->validate(TemplateTag::$updateRules) : $request->validate(TemplateTag::$createRules);
        $data = $request->only([
            'name', 'tag', 'data'
        ]);
        if($id && $service->updateTemplate(TemplateTag::find($id), $data)) {
            flash('Template updated successfully.')->success();
        }
        else if (!$id && $template = $service->createTemplate($data)) {
            flash('Template created successfully.')->success();
            return redirect()->to('admin/templates/edit/'.$template->id);
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->back();
    }
    
    /**
     * Gets the template deletion modal.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getDeleteTemplate($id)
    {
        $template = TemplateTag::find($id);
        return view('admin.templates._delete_template', [
            'template' => $template,
        ]);
    }

    /**
     * Deletes a template.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Services\TemplateService  $service
     * @param  int                       $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDeleteTemplate(Request $request, TemplateService $service, $id)
    {
        if($id && $service->deleteTemplate(TemplateTag::find($id))) {
            flash('Template deleted successfully.')->success();
        }
        else {
            foreach($service->errors()->getMessages()['error'] as $error) flash($error)->error();
        }
        return redirect()->to('admin/templates');
    }

    /**
     * Gets a list of template tags for selection.
     *
     * @return array
     */
    private function getTemplateTags()
    {
        $tags = Config::get('lorekeeper.template_tags');
        $result = [];
        foreach($tags as $tag => $tagData)
            if(isset($tagData['requires'])){
                if(class_exists($tagData['requires']))
                    $result[$tag] = $tagData['name'];
            } else {
                $result[$tag] = $tagData['name'];
            }

        return $result;
    }
}
