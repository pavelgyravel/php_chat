@extends('layouts.auth')


@section('content')
    <div class="row">
        <div class="page-header">
            <h2>Edit your profile</h2>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            {!! Form::open(array('url' => URL::to('user/update'), 'method' => 'post', 'files'=> true)) !!}
            <div class="form-group  {{ $errors->has('name') ? 'has-error' : '' }}">
                {!! Form::label('name', 'Name', array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('name', $user->name, array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('name', ':message') }}</span>
                </div>
            </div>
            <div class="form-group  {{ $errors->has('email') ? 'has-error' : '' }}">
                {!! Form::label('email', 'Email', array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::text('email', $user->email, array('class' => 'form-control', 'disabled' => 'disabled')) !!}
                    <span class="help-block">{{ $errors->first('email', ':message') }}</span>
                </div>
            </div>
            <div class="form-group  {{ $errors->has('password') ? 'has-error' : '' }}">
                {!! Form::label('password', "Password", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::password('password', array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('password', ':message') }}</span>
                </div>
            </div>
            <div class="form-group  {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                {!! Form::label('password_confirmation', "Confirm Password", array('class' => 'control-label')) !!}
                <div class="controls">
                    {!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
                    <span class="help-block">{{ $errors->first('password_confirmation', ':message') }}</span>
                </div>
            </div>
            <div class="form-group">
                
                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection