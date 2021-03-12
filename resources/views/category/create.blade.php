@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('Add New Category') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('category.store') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="inputTitle" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="inputTitle" name="title" value="{{ old('title') }}" required autocomplete="title" autofocus>
                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputSlug" class="col-md-4 col-form-label text-md-right">{{ __('Slug') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="inputSlug" name="slug" value="{{ old('slug') }}" autocomplete="slug" autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputDescription" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>
                            <div class="col-md-6">
                                <textarea class="form-control" id="inputDescription" name="description" rows="3" autofocus>{{ old('description') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="inputPublished" name="published" value="1" {{ old('published') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inputPublished">{{ __('Published') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-sm btn-primary" title="Save"><i class="fas fa-save"></i> {{ __('Save') }}</button>
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