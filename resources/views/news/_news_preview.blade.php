<div class="col-md-6">
    <div class="card mb-4">
        <div class="card-header">
            <h2 class="card-title mb-0">{!! $news->displayName !!}</h2>
            <small>
                Posted {!! $news->post_at ? pretty_date($news->post_at) : pretty_date($news->created_at) !!} :: Last edited {!! pretty_date($news->updated_at) !!} by {!! $news->user->displayName !!}
            </small>
        </div>
        <br>
        <div class="card-body text-center">
            @if ($news->has_image)
                <div><a href="{{ $news->imageUrl }}" data-lightbox="entry" data-title="{{ $news->title }}"><img
                            src="{{ $news->imageUrl }}" class="world-entry-image" /></a></div>
            @endif
            <br>
            @if ($news->parsed_summary)
                <div class="parsed-text">
                    {!! $news->parsed_summary !!}
                </div>
            @endif
            <hr>
            <a href="{{ $news->url }}">Click here to read this news post.</a>
        </div>
        <?php $commentCount = App\Models\Comment::where('commentable_type', 'App\Models\News')
            ->where('commentable_id', $news->id)
            ->count(); ?>

        <div class="text-right mb-2 mr-2">
            <a class="btn" href="{{ $news->url }}"><i class="fas fa-comment"></i> {{ $commentCount }}
                Comment{{ $commentCount != 1 ? 's' : '' }}</a>
        </div>
    </div>
</div>
