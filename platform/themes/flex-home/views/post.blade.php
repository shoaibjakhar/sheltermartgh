<div class="bgheadproject hidden-xs">
    <div class="description">
        <div class="container-fluid w90">
            <h1 class="text-center">{{ $post->name }}</h1>
            {!! Theme::partial('breadcrumb') !!}
        </div>
    </div>
</div>

<div class="container padtop50">
    <div class="row">
        <div class="col-sm-9">
            {!! Theme::partial('post-meta', compact('post')) !!}
            <div class="scontent">
                {!! $post->content !!}
                <br>
                @if ($post->tags->count())
                    <div class="ps-tags">
                        <p>
                            <strong>{{ __('Tags') }}</strong>: @foreach ($post->tags as $tag)
                                <a href="{{ $tag->url }}">{{ $tag->name }}</a>@if (!$loop->last), @endif
                            @endforeach
                        </p>
                    </div>
                @endif
                <br>
                {!! Theme::partial('share', ['title' => __('Share this post'), 'description' => $post->description]) !!}
            </div>
            <div class="clearfix"></div>
            <br>
            <h5><strong>{{ __('Related posts') }}</strong>:</h5>
            <div class="blog-container">
                <div class="row">
                    @foreach (get_related_posts($post->id, 2) as $relatedItem)
                        <div class="col-md-6 col-sm-6 container-grid">
                            <div class="grid-in">
                                <div class="grid-shadow grid-shadow-gray">
                                    <div class="hourseitem" style="margin-top: 0;">
                                        <div class="blii">
                                            <div class="img"><img style="border-radius: 0" class="thumb" data-src="{{ get_object_image($relatedItem->image, 'small') }}" src="{{ get_object_image($relatedItem->image, 'small') }}" alt="{{ $relatedItem->name }}">
                                            </div>
                                            <a href="{{ $relatedItem->url }}" class="linkdetail"></a>
                                        </div>
                                    </div>
                                    <div class="grid-h">
                                        <div class="blog-title">
                                            <a href="{{ route('public.single', $relatedItem->slug) }}">
                                                <h2>{{ $relatedItem->name }}</h2></a>
                                            <p class="post-meta">{{ $relatedItem->created_at->format('d M, Y') }} - <i class="fa fa-eye"></i> {{ $relatedItem->views }}</p>
                                        </div>
                                        <div class="blog-excerpt">
                                            <p>{{ Str::words($relatedItem->description, 40) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            {!! dynamic_sidebar('primary_sidebar') !!}
        </div>
    </div>
</div>
<br>
<br>
