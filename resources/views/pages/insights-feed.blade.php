<?php echo '<?xml version="1.0" encoding="UTF-8"?>'."\n"; ?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
    <channel>
        <title>HANSA Management Ltd. — Insights</title>
        <link>{{ url('insights') }}</link>
        <description>Guides, updates and practical notes on hospitality and facility management in Bangladesh.</description>
        <language>en</language>
        <atom:link href="{{ url('insights/feed') }}" rel="self" type="application/rss+xml" />
        @foreach ($posts as $post)
            <item>
                <title>{{ $post->title }}</title>
                <link>{{ url($post->path()) }}</link>
                <guid isPermaLink="true">{{ url($post->path()) }}</guid>
                <pubDate>{{ ($post->published_date ?? now())->toRfc2822String() }}</pubDate>
                <description>{{ $post->summary(300) }}</description>
            </item>
        @endforeach
    </channel>
</rss>
