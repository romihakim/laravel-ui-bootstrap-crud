@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('Show Post') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('post.store') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="inputTitle" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>
                            <div class="col-md-6 col-form-label">
                                {{ $post->title }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputSlug" class="col-md-4 col-form-label text-md-right">{{ __('Slug') }}</label>
                            <div class="col-md-6 col-form-label">
                                {{ $post->slug }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputExcerpt" class="col-md-4 col-form-label text-md-right">{{ __('Excerpt') }}</label>
                            <div class="col-md-6 col-form-label">
                                {{ $post->excerpt }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputContent" class="col-md-4 col-form-label text-md-right">{{ __('Content') }}</label>
                            <div class="col-md-6 col-form-label">
                                {{ $post->content }}
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputImage" class="col-md-4 col-form-label text-md-right">{{ __('Image') }}</label>
                            <div class="col-md-6">
                                @if($post->image)
                                <img src="{{ Storage::url('posts/' . $post->image) }}" width="80">
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <label class="{{ $post->published == 0 ? 'text-black-50' : 'text-success' }}">
                                    @if($post->published == 0)
                                    <i class="fas fa-check"></i> {{ __('Unpublished') }}
                                    @else
                                    <i class="fas fa-check"></i> {{ __('Published') }}
                                    @endif
                                </label>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <a class="btn btn-sm btn-secondary" href="{{ route('post.index') }}" title="Go Back"><i class="fas fa-undo"></i> {{ __('Back') }}</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection