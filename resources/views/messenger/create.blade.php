@extends('layouts.master')

@section('content')
<h1>Create a new message</h1>
{!! Form::open(['route' => 'messages.store']) !!}

    <!-- Subject Form Input -->
    <div class="form-group">
        {!! Form::label('subject', 'Subject', ['class' => 'control-label']) !!}
        {!! Form::text('subject', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Message Form Input -->
    <div class="form-group">
        <!-- {!! Form::label('message', 'Message', ['class' => 'control-label']) !!} -->
        {!! Form::text('message', null, ['class' => 'form-control', 'placeholder' => 'Message...']) !!}
    </div>

    @if($users->count() > 0)
    <div class="checkbox">
        @foreach($users as $user)
            <label title="{!!$user->name!!}"><input type="checkbox" name="recipients[]" value="{!!$user->id!!}">{!!$user->name!!}</label>
        @endforeach
    </div>
    @endif
    
    <!-- Submit Form Input -->
    <div class="form-group">
        {!! Form::submit('Submit', ['class' => 'btn btn-primary form-control']) !!}
    </div>

{!! Form::close() !!}
@stop