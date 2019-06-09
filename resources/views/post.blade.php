@extends('layouts.blog-post')

@section('content')

<!-- Blog Post -->
<!-- Title -->
<h1>{{$post->title}}</h1>

<!-- Author -->
<p class="lead">
    by <a href="#">{{$post->user->name}}</a>
</p>

<hr>

<!-- Date/Time -->
<p><span class="glyphicon glyphicon-time"></span> Posted {{$post->created_at->diffForHumans()}}</p>

<hr>

<!-- Preview Image -->
<img class="img-responsive" src="{{$post->photo ? $post->photo->file : $post->photoPlaceholder()}}" alt="">

<hr>

<!-- Post Content -->
<p>{!! $post->body !!}</p>

<hr>

{{--<div id="disqus_thread"></div>--}}
{{--<script>--}}
    {{--/**  Adding Disqus --}}
     {{--*  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.--}}
     {{--*  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/--}}
    {{--/*--}}
    {{--var disqus_config = function () {--}}
    {{--this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable--}}
    {{--this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable--}}
    {{--};--}}
    {{--*/--}}
    {{--(function() { // DON'T EDIT BELOW THIS LINE--}}
        {{--var d = document, s = d.createElement('script');--}}
        {{--s.src = 'https://codehacking-uossfwzoiv.disqus.com/embed.js';--}}
        {{--s.setAttribute('data-timestamp', +new Date());--}}
        {{--(d.head || d.body).appendChild(s);--}}
    {{--})();--}}
{{--</script>--}}
{{--<noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>--}}
{{--<script id="dsq-count-scr" src="//codehacking-uossfwzoiv.disqus.com/count.js" async></script>--}}

@if(Session::has('comment_message'))
    <p class="bg-success">{{session('comment_message')}}</p>
@endif
@if(Session::has('reply_message'))
    <p class="bg-success">{{session('reply_message')}}</p>
@endif

<!-- Blog Comments -->
@if(Auth::check())
<!-- Comments Form -->
<div class="well">
    <h4>Leave a Comment:</h4>
    {!! Form::open(['method'=>'POST', 'action'=>'PostCommentsController@store']) !!}
        <input type="hidden" name="post_id" value="{{$post->id}}">
        <div class="form-group">
            {!! Form::label('body', 'Body:') !!}
            {!! Form::textarea('body', null, ['class'=>'form-control','rows'=>3]) !!}
        </div>
        <div class="form-group">
            {!! Form::submit('Sumbit Comment', ['class'=>'btn btn-primary']) !!}
        </div>
    {!! Form::close() !!}
</div>
@endif
<hr>

<!-- Posted Comments -->

@if(count($comments)>0)
    @foreach($comments as $comment)
    <!-- Comment -->
    <div class="media">
        <a class="pull-left" href="#">
{{--        <img height="64" class="media-object" src="{{$comment->file}}" alt="">--}}
            <img height="64" class="media-object" src="{{Auth::user()->gravatar ? Auth::user()->gravatar : $comment->file}}" alt="">
        </a>
        <div class="media-body">
            <h4 class="media-heading">{{$comment->author}}
                <small>{{$comment->created_at->diffForHumans()}}</small>
            </h4>
                {{$comment->body}}
            <p>&nbsp;</p>
            @if(count($comment->replies) > 0)
                @foreach($comment->replies as $reply)
                    @if($reply->is_active==1)
                    <!-- Nested Comment... i.e. REPLY -->
                    <div id="nested-comment" class="media">
                        <a class="pull-left" href="#">
{{--                        <img height="64" class="media-object" src="{{$reply->file}}" alt="">--}}
                            <img height="64" class="media-object" src="{{Auth::user()->gravatar ? Auth::user()->gravatar : $reply->file}}" alt="">
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading">{{$reply->author}}
                            <small>{{$reply->created_at->diffForHumans()}}</small>
                            </h4>
                            {{$reply->body}}
                            <p>&nbsp;</p>
                        </div>
                    <!-- End Nested Comment -->
                    </div>
                    @endif
                @endforeach
            @endif
            <!-- Reply button - click to bring up reply form -->
            <div class="comment-reply-container">
                <button class="toggle-reply btn btn-primary pull-left">Reply</button>
                <div class="comment-reply col-sm-9">
                    {!! Form::open(['method'=>'POST', 'action'=>'CommentRepliesController@createReply']) !!}
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input type="hidden" name="comment_id" value="{{$comment->id}}">
                            {!! Form::label('body', 'Reply:') !!}
                            {!! Form::textarea('body', null, ['class'=>'form-control', 'rows'=>2]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::submit('Submit', ['class'=>'btn btn-primary']) !!}
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
<!-- End Comment -->
    @endforeach
@endif

@stop

@section('scripts')
    <script>
        $(".comment-reply-container .toggle-reply").click(function(){
            $(this).next().slideToggle("slow");
        });
    </script>
@stop