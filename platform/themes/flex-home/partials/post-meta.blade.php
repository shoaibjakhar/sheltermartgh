<p class="post-meta">
    <i class="fa fa-calendar"></i> {{ $post->created_at->format('Y/m/d') }} {{ __('in') }} @foreach($post->categories as $category)
            <a href="{{ $category->url }}">{{ $category->name }}</a>
            @if (!$loop->last)
                ,
            @endif
        @endforeach
    - <i class="fa fa-eye"></i> {{ $post->views }}
</p>
