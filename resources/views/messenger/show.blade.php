@extends('layouts.master')

@section('content')
    
        <!-- <h1>{!! $thread->subject !!}</h1> -->
        <div class="JQ_messages chat_messages">
            @foreach($thread->messages as $message)
                <div class="media">
                    <div class="media-body">
                        <h5 class="media-heading"><b>{!! $message->user->name !!} </b> <small>(Posted {!! $message->created_at !!})</small></h5>
                        <p>{!! $message->body !!}</p>
                    </div>
                </div>
            @endforeach
        </div>
        
        
        {!! Form::open(['route' => ['messages.update', $id], 'method' => 'PUT', 'class' => 'JQ_message_form']) !!}
        <!-- Message Form Input -->
        <div class="form-group">
            {!! Form::text('message', null, ['class' => 'form-control JQ_chat_message', 'placeholder' => 'Message...']) !!}
            @if(count($thread->messages) >0 )
                {{ Form::hidden('last', $thread->messages->last()->created_at, ['class' => 'JQ_last']) }}
            @else
                {{ Form::hidden('last', null, ['class' => 'JQ_last']) }}
            @endif

            

        </div>
        <!-- Submit Form Input -->
        <div class="form-group">
            {!! Form::submit('Submit', ['class' => 'btn btn-primary form-control']) !!}
        </div>
        {!! Form::close() !!}
    
@stop