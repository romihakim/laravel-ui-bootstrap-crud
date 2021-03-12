@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('Posts') }}
                    <a class="btn btn-sm btn-success" href="{{ route('post.create') }}" title="Add New"><i class="fas fa-plus"></i> {{ __('Add New') }}</a>
                </div>

                <div class="card-body">
                    <div>
                        @if(session()->get('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                        @endif
                    </div>
                    <table class="table table-striped table-sm">
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Image</th>
                            <th>Published</th>
                            <th></th>
                        </tr>
                        @foreach ($posts as $post)
                        <tr>
                            <td>{{ ++$i }}</td>
                            <td>{{ $post->title }}</td>
                            <td>
                                @if($post->image)
                                <img src="{{ Storage::url('posts/' . $post->image) }}" width="80">
                                @endif
                            </td>
                            <td class="{{ $post->published == 0 ? 'text-black-50' : 'text-success' }}">
                                <i class="fas fa-check"></i>
                            </td>
                            <td class="text-right">
                                <form action="{{ route('post.destroy', $post->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <a class="btn btn-sm btn-info" href="{{ route('post.show', $post->id) }}" title="Show"><i class="fas fa-eye"></i></a>
                                    <a class="btn btn-sm btn-primary" href="{{ route('post.edit', $post->id) }}" title="Edit"><i class="fas fa-pen"></i></a>
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure to delete?');"><i class="fas fa-times"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                    {!! Str::of($posts->links())->replace('pagination', 'pagination pagination-sm') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection