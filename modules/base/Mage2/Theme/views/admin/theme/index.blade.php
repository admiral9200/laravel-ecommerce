@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col s12">
        <div class="main-title-wrapper">
            <h1>
                Theme List
                <!--<small>Sub title</small> -->
            </h1>
            <div class="right">
                <a href="{{ route('admin.theme.create') }}"
                   class="btn btn-primary">Create Theme</a>
            </div>
        </div>

        
        <div class="clearfix"></div>
        <br/>
        @if(count($themes) <= 0)

        <p>Sorry No Theme Found</p>

        @else
        
        <table class="table table-bordered table-responsive">
            <thead>
            <th>Name</th>
            <th>Description</th>
            <th>Action</th>
            </thead>
            <tbody>
                @foreach($themes as $theme)
                <tr>
                    <td>{{ $theme['name'] }}</td>
                    <td>{{ $theme['description'] }}</td>
                    <td>
                        @if($activeTheme != $theme['name'])
                        {!! Form::open(['method' => 'POST', 'route' => ['admin.configuration.store']]) !!}
                        {!! Form::hidden('theme_path_info',$theme['path']) !!}
                        {!! Form::hidden('theme_name',$theme['name']) !!}
                        <button type="submit" class="btn btn-primary">Activate</a>
                        {!! Form::close() !!}
                        @else 
                        
                        <button class="btn disabled">Activate</button>
                        @endif
                    </td>
                   
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        
    </div>
</div>
@endsection

