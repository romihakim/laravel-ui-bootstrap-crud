@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('Show Category') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('category.store') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="inputTitle" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>
                            <div class="col-md-6 col-form-label">
                                {{ $category->title }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputSlug" class="col-md-4 col-form-label text-md-right">{{ __('Slug') }}</label>
                            <div class="col-md-6 col-form-label">
                                {{ $category->slug }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputDescription" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>
                            <div class="col-md-6 col-form-label">
                                {{ $category->description }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <label class="{{ $category->published == 0 ? 'text-black-50' : 'text-success' }}">
                                    @if($category->published == 0)
                                    <i class="fas fa-check"></i> {{ __('Unpublished') }}
                                    @else
                                    <i class="fas fa-check"></i> {{ __('Published') }}
                                    @endif
                                </label>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <a class="btn btn-sm btn-secondary" href="{{ route('category.index') }}" title="Go Back"><i class="fas fa-undo"></i> {{ __('Back') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection