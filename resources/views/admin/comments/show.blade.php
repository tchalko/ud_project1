@extends('layouts.admin')

@section('content')

    @if(count($comments) > 0)
        <h1>Comments</h1>

        <table class="table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Author</th>
                <th>Email</th>
                <th>File</th>
                <th>Body</th>
            </tr>
            </thead>
            <tbody>
            @foreach($comments as $comment)
                <tr>
                    <td>{{$comment->id}}</td>
                    <td>{{$comment->author}}</td>
                    <td>{{$comment->email}}</td>
                    <td>{{$comment->file}}</td>
                    <td>{{$comment->body}}</td>
                    <td><a href="{{route('home.post',$post->slug)}}">View Post</a></td>

                    <td>
                        {!! Form::open(['method'=>'PATCH', 'action'=>['PostCommentsController@update', $comment->id]]) !!}

                        @if($comment->is_active == 1)
                            <input type="hidden" name="is_active" value="0">
                            {!! Form::submit('Un-approve', ['class'=>'btn btn-danger']) !!}
                        @else
                            <input type="hidden" name="is_active" value="1">
                            {!! Form::submit('Approve', ['class'=>'btn btn-success']) !!}
                        @endif
                        {!! Form::close() !!}
                    </td>
                    <td>
                        {!! Form::open(['method'=>'DELETE', 'action'=>['PostCommentsController@destroy', $comment->id]]) !!}
                        <div class="form-group">
                            {!! Form::submit('Delete Comment', ['class'=>'btn btn-danger']) !!}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <h1 class="text-center">No Comments</h1>
    @endif

@stop