@extends('avored-ecommerce::layouts.app')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    {{ __('avored-ecommerce::user.admin-user-update') }}
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.admin-user.update', $model->id) }}" 
                        enctype="multipart/form-data" 
                        method="post">
                        @csrf()
                        @method('put')

                        @include('avored-ecommerce::admin-user._fields')

                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">
                                {{ __('avored-ecommerce::user.admin-user-update') }}
                            </button>
                            <a href="{{ route('admin.admin-user.index') }}" class="btn">
                                {{ __('avored-ecommerce::lang.cancel') }}
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection