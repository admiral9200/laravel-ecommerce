@extends('layouts.admin-bootstrap')

@section('content')
        <div class="row">
            <div class="col-md-12">
                <div class="main-title-wrap">
                    <span class="title">
                        Create User
                        <!--<small>Sub title</small> -->
                    </span>
                </div>
                {!! Form::open(['method' => 'post','action' =>  route('admin.user.store')]) !!}
                    @include('admin.user.user._fields',['editMethod' => false])
                    {!! Form::submit("Create User",['class' => 'btn btn-primary']) !!}
                    {!! Form::button("cancel",['class' => 'btn ','onclick' => 'location="' . route('admin.user.index'). '"']) !!}
                    
                {!! Form::close() !!}
            </div>
        </div>
@endsection