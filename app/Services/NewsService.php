<?php namespace App\Services;

use App\Services\Service;

use DB;
use Config;

use App\Models\User\User;
use App\Models\News;

class NewsService extends Service
{
    /*
    |--------------------------------------------------------------------------
    | News Service
    |--------------------------------------------------------------------------
    |
    | Handles the creation and editing of news posts.
    |
    */

    /**
     * Creates a news post.
     *
     * @param  array                  $data
     * @param  \App\Models\User\User  $user
     * @return bool|\App\Models\News
     */
    public function createNews($data, $user)
    {
        DB::beginTransaction();

        try {
            $data['parsed_text'] = parse($data['text']);
            $data['user_id'] = $user->id;
            if(!isset($data['is_visible'])) $data['is_visible'] = 0;

            if(isset($data['summary']) && $data['summary']) $data['parsed_summary'] = parse($data['summary']);
            else $data['parsed_summary'] = null;

            $image = null;
            if(isset($data['image']) && $data['image']) {
                $data['has_image'] = 1;
                $image = $data['image'];
                unset($data['image']);
            }
            else $data['has_image'] = 0;

            $news = News::create($data);

            if ($image) $this->handleImage($image, $news->imagePath, $news->imageFileName);

            if($news->is_visible) $this->alertUsers();

            return $this->commitReturn($news);
        } catch(\Exception $e) { 
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Updates a news post.
     *
     * @param  \App\Models\News       $news
     * @param  array                  $data 
     * @param  \App\Models\User\User  $user
     * @return bool|\App\Models\News
     */
    public function updateNews($news, $data, $user)
    {
        DB::beginTransaction();

        try {
            $data['parsed_text'] = parse($data['text']);
            $data['user_id'] = $user->id;
            if(!isset($data['is_visible'])) $data['is_visible'] = 0;
            if(isset($data['bump']) && $data['is_visible'] == 1 && $data['bump'] == 1) $this->alertUsers();

            $image = null;
            if(isset($data['image']) && $data['image']) {
                $data['has_image'] = 1;
                $image = $data['image'];
                unset($data['image']);
            }

            if(isset($data['remove_image']))
            {
                if($news && $news->has_image && $data['remove_image'])
                {
                    $data['has_image'] = 0;
                    $this->deleteImage($news->imagePath, $news->imageFileName);
                }
                unset($data['remove_image']);
            }

            if(isset($data['summary']) && $data['summary']) $data['parsed_summary'] = parse($data['summary']);
            else $data['parsed_summary'] = null;

            $news->update($data);

            if ($news) $this->handleImage($image, $news->imagePath, $news->imageFileName);

            return $this->commitReturn($news);
        } catch(\Exception $e) { 
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Deletes a news post.
     *
     * @param  \App\Models\News  $news
     * @return bool
     */
    public function deleteNews($news)
    {
        DB::beginTransaction();

        try {
            if($news->has_image) $this->deleteImage($news->imagePath, $news->imageFileName);
            $news->delete();

            return $this->commitReturn(true);
        } catch(\Exception $e) { 
            $this->setError('error', $e->getMessage());
        }
        return $this->rollbackReturn(false);
    }

    /**
     * Updates queued news posts to be visible and alert users when
     * they should be posted.
     *
     * @return bool
     */
    public function updateQueue()
    {
        $count = News::shouldBeVisible()->count();
        if($count) {
            DB::beginTransaction();

            try {
                News::shouldBeVisible()->update(['is_visible' => 1]);
                $this->alertUsers();

                return $this->commitReturn(true);
            } catch(\Exception $e) { 
                $this->setError('error', $e->getMessage());
            }
            return $this->rollbackReturn(false);
        }
    }

    /**
     * Updates the unread news flag for all users so that
     * the new news notification is displayed.
     *
     * @return bool
     */
    private function alertUsers()
    {
        User::query()->update(['is_news_unread' => 1]);
        return true;
    }
}