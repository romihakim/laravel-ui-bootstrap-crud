# Membuat CRUD dan Autentikasi dengan Laravel UI Bootstrap
Membuat, membaca, memperbarui, dan menghapus data digunakan hampir setiap aplikasi.
Berikut adalah langkah dalam membuat aplikasi CRUD dan Autentikasi (login & register) menggunakan Laravel (versi 8) dan Bootstrap (versi 4).

- Step 1 – Download Laravel
- Step 2 – Instal Package laravel/ui
- Step 3 – Membuat Halaman CRUD
- Step 4 – Menambahkan Font Awesome dan TinyMCE

Pastikan sebelumnya sudah menginstal aplikasi Composer dan NPM.

## Step 1 – Download Laravel
Download dan instal Laravel menggunakan Composer, jalankan perintah berikut pada command prompt:
```
D:\Belajar> composer create-project laravel/laravel laravel-ui-bootstrap-crud
```

Setelah instalasi laravel selesai, jangan lupa masuk ke dalam directory project yang sudah dibuat:
```
D:\Belajar> cd laravel-ui-bootstrap-crud
```

Kita sudah bisa menjalankan aplikasi laravel di localhost dengan tampilan default-nya menggunakan perintah Artisan CLI's serve:
```
D:\Belajar\laravel-ui-bootstrap-crud> php artisan serve
```

Untuk menghentikan perintah Artisan CLI's serve, cukup tekan ctrl + c pada command prompt.

Sebelum keproses selanjutnya kita harus menyiapkan database, dan untuk seting koneksinya edit file .env pada directory project.

```
DB_CONNECTION=mysql  
DB_HOST=127.0.0.1  
DB_PORT=3306  
DB_DATABASE=laravel  
DB_USERNAME=root  
DB_PASSWORD=  
```

Jalankan Database Migration untuk men-generate tabel users (default) di database:
```
D:\Belajar\laravel-ui-bootstrap-crud> php artisan migrate
```

Untuk membuat file upload yang dapat diakses dari web, kita harus membuat symbolic link dari *public/storage* ke *storage/app/public*, dengan menggunakan perintah Artisan CLI's storage:link.
```
D:\Belajar\laravel-ui-bootstrap-crud> php artisan storage:link
```


## Step 2 – Instal Package laravel/ui
Laravel UI adalah package yang dikembangkan oleh Laravel untuk menghasilkan kerangka User Interface (UI) dan kode Autentikasi sederhana menggunakan framework CSS Bootstrap.

Jalankan perintah berikut pada command prompt untuk menginstal package laravel/ui:
```
D:\Belajar\laravel-ui-bootstrap-crud> composer require laravel/ui
```

Setelah package laravel/ui terinstal, kita dapat men-generate frontend scaffolding menggunakan perintah **artisan ui**,
jalankan perintah berikut sesuai dengan kebutuhan, apakah aplikasi yang dikembangkan menggunakan sistem autentikasi atau tidak!

```
// Generate login / registration scaffolding...  
D:\Belajar\laravel-ui-bootstrap-crud> php artisan ui bootstrap --auth  
  
// Generate basic scaffolding...  
D:\Belajar\laravel-ui-bootstrap-crud> php artisan ui bootstrap  
```

Setelah menginstal package laravel/ui dan men-generate frontend scaffolding, kita harus menginstal dependensi Frontend dan JavaScript menggunakan Node Package Manager (NPM):
```
D:\Belajar\laravel-ui-bootstrap-crud> npm install
```

Setelah dependensi terinstal menggunakan **npm install**, compile file SASS (resources/sass/app.scss) dan JavaScript (resources/js/app.js) menggunakan Laravel Mix. 
Perintah **npm run dev** akan memproses instruksi pada file webpack.mix.js, dan hasil kompilasi akan ditempatkan pada directory public/css dan public/js.
```
D:\Belajar\laravel-ui-bootstrap-crud> npm run dev
```

Apabila pada command prompt keluar informasi seperti di bawah ini, kita harus meng-compile ulang file SASS dan JavaScript menggunakan perintah **npm run dev**.
```
Additional dependencies must be installed. This will only take a moment.  
Running: npm install resolve-url-loader@^3.1.2 --save-dev --legacy-peer-deps  
Finished. Please run Mix again.  
```

Sampai di sini package laravel/ui sudah terinstal dan kita bisa menggunakannya. Jalankan aplikasi laravel untuk melihat perubahan dengan menggunakan perintah Artisan CLI's serve.

Untuk lanjut keproses berikutnya, keluar dari aplikasi terlebih dahulu dengan metekan ctrl + c pada command prompt.

## Step 3 – Membuat Halaman CRUD
Dalam membuat aplikasi CRUD di laravel melalui beberapa tahapan, diantaranya:
- Model & Database Migration
- Resource Controllers
- Setting Routes
- User Interface / Views

### Model & Database Migration

Generate class Model yang ada pada directory *app\Models* dan Database Migration pada directory *database\migrations*, sebagai contoh kita buat tabel **posts** untuk menyimpan data artikel.

Jalankan perintah berikut pada command prompt:
```
D:\Belajar\laravel-ui-bootstrap-crud> php artisan make:model Post -m
```

maka file class **Model** dan **Migration** ter-generate.

Buka file *XXXX_XX_XX_XXXXXX_create_posts_table.php* pada directory Database Migration, edit sesuai dengan kebutuhan:

```
public function up()  
{  
    Schema::create('posts', function (Blueprint $table) {  
        $table->id();  
        $table->integer('category')->unsigned()->default(0);  
        $table->string('title');  
        $table->string('slug')->unique();  
        $table->text('excerpt');  
        $table->text('content');  
        $table->string('image')->nullable();  
        $table->boolean('published')->default(false);  
        $table->integer('user')->unsigned()->default(0);  
        $table->timestamps();  
    });  
}  
```

Menjalankan Database Migration di atas untuk men-generate tabel posts di database:
```
D:\Belajar\laravel-ui-bootstrap-crud> php artisan migrate
```

Selanjutnya buka file *Post.php* pada directory Model, edit sesuai kode berikut:

```
class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'image',
        'published',
    ];
}
```
 
### Resource Controllers
Generate class Controller yang ada pada directory *app\Http\Controllers* dengan perintah berikut pada command prompt:

```
D:\Belajar\laravel-ui-bootstrap-crud> php artisan make:controller PostController --resource --model=Post
```

Selanjutnya buka file *PostController.php* pada directory Controller, edit sesuai kode berikut:

```
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $posts = Post::latest()->paginate(5);

        return view('post.index', compact('posts'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('post.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'mimes:png,jpg,jpeg'
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());
        }

        $req = $request->all();
        $req['slug'] = Str::of($req['slug'] ?? $req['title'])->slug('-');
        $req['excerpt'] = $req['excerpt'] ?? '';
        $req['content'] = $req['content'] ?? '';

        if (isset($image)) {
            $req['image'] = $image->hashName();
        }

        if (!isset($req['published'])) {
            $req['published'] = 0;
        }

        Post::create($req);

        return redirect()->route('post.index')->with('success', 'Post created');
    }

    public function show(Post $post)
    {
        return view('post.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('post.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required',
            'image' => 'mimes:png,jpg,jpeg'
        ]);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());
        }

        $req = $request->all();
        $req['slug'] = Str::of($req['slug'] ?? $req['title'])->slug('-');
        $req['excerpt'] = $req['excerpt'] ?? '';
        $req['content'] = $req['content'] ?? '';

        if (isset($image)) {
            $req['image'] = $image->hashName();
        }

        if (!isset($req['published'])) {
            $req['published'] = 0;
        }

        $post->update($req);

        return redirect()->route('post.index')->with('success', 'Post updated');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('post.index')->with('success', 'Post deleted');
    }
}
```

Untuk menampilkan pagination menggunakan Bootstrap CSS, kita dapat memanggil metode **useBootstrap** pada class *app\Providers\AppServiceProvider.php*:
```
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Paginator::useBootstrap();
    }
}
```

### Setting Routes
Setelah membuat Controller, saatnya mengatur routing aplikasi yang telah disediakan laravel pada file *routes\web.php*.

```
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resource('post', App\Http\Controllers\PostController::class);
```

### User Interface / Views  
Kita dapat membuat **view** dengan menempatkan file berekstensi **.blade.php** pada directory *resources\views*. 
Dan untuk memanggil file **view** bisa dilakukan pada **routes** atau **controllers** menggunakan **global view helper** seperti ini:
```
public function create()
{
    return view('post.create');
}
```

Berikut adalah struktur file dan directory **view post** yang harus dibuat:

```
resources
  views
    post
      index.blade.php
      create.blade.php
      edit.blade.php
      show.blade.php
```

Buka file **index.blade.php**, tambahkan kode berikut:
```
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
```

Buka file **create.blade.php**, tambahkan kode berikut:
```
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('Add New Post') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('post.store') }}" enctype="multipart/form-data">
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
                            <label for="inputExcerpt" class="col-md-4 col-form-label text-md-right">{{ __('Excerpt') }}</label>
                            <div class="col-md-6">
                                <textarea class="form-control" id="inputExcerpt" name="excerpt" rows="3" autofocus>{{ old('excerpt') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputContent" class="col-md-4 col-form-label text-md-right">{{ __('Content') }}</label>
                            <div class="col-md-6">
                                <textarea class="form-control tiny" id="inputContent" name="content" rows="3" autofocus>{{ old('content') }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputImage" class="col-md-4 col-form-label text-md-right">{{ __('Image') }}</label>
                            <div class="col-md-6">
                                <input type="file" class="form-control-file @error('image') is-invalid @enderror" id="inputImage" name="image" autofocus>
                                @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
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
```

Buka file **edit.blade.php**, tambahkan kode berikut:
```
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    {{ __('Edit Post') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('post.update', $post->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label for="inputTitle" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="inputTitle" name="title" value="{{ old('title', $post->title) }}" required autocomplete="title" autofocus>
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
                                <input type="text" class="form-control" id="inputSlug" name="slug" value="{{ old('slug', $post->slug) }}" autocomplete="slug" autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputExcerpt" class="col-md-4 col-form-label text-md-right">{{ __('Excerpt') }}</label>
                            <div class="col-md-6">
                                <textarea class="form-control" id="inputExcerpt" name="excerpt" rows="3" autofocus>{{ old('excerpt', $post->excerpt) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputContent" class="col-md-4 col-form-label text-md-right">{{ __('Content') }}</label>
                            <div class="col-md-6">
                                <textarea class="form-control tiny" id="inputContent" name="content" rows="3" autofocus>{{ old('content', $post->content) }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="inputImage" class="col-md-4 col-form-label text-md-right">{{ __('Image') }}</label>
                            <div class="col-md-6">
                                <input type="file" class="form-control-file @error('image') is-invalid @enderror" id="inputImage" name="image" autofocus>
                                @error('image')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="inputPublished" name="published" value="1" {{ old('published', $post->published) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="inputPublished">{{ __('Published') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-sm btn-primary" title="Save"><i class="fas fa-save"></i> {{ __('Save') }}</button>
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
```
Untuk file **show.blade.php**, silahkan mencoba buat sendiri, karena hanya menampilkan data berdasarkan ID.

Edit menu navbar untuk menambahkan link halaman **post**, buka file **resources\views\layouts\app.blade.php**, sesuaikan kode berikut:
```
<ul class="navbar-nav mr-auto">
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('home') }}">Home <span class="sr-only">(current)</span></a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('post.index') }}">Post</a>
    </li>
</ul>
```
Sampai di sini kita sudah membuat fungsi CRUD pada tabel **posts**. Jalankan aplikasi laravel untuk melihat perubahannya, dengan menggunakan perintah Artisan CLI's serve.

Untuk lanjut keproses berikutnya, keluar dari aplikasi terlebih dahulu dengan metekan ctrl + c pada command prompt.

## Step 4 – Menambahkan Font Awesome dan TinyMCE
Agar aplikasi yang dibuat menjadi **eye-catching**, harus ditambahkan icon tertentu. Icon akan memudahkan kita untuk mengenali fungsi atau fitur disetiap button.

Jalankan perintah berikut pada command prompt untuk menginstal Font Awesome:
```
D:\Belajar\laravel-ui-bootstrap-crud> npm install @fortawesome/fontawesome-free --save-dev
```

Edit file *resources/sass/app.scss* dan tambahkan kode ini:
```
// Fonts
@import url('https://fonts.googleapis.com/css?family=Nunito');
@import '~@fortawesome/fontawesome-free/scss/fontawesome';
@import '~@fortawesome/fontawesome-free/scss/regular';
@import '~@fortawesome/fontawesome-free/scss/solid';
@import '~@fortawesome/fontawesome-free/scss/brands';

// Variables
@import 'variables';

// Bootstrap
@import '~bootstrap/scss/bootstrap';
```


TinyMCE adalah sebuah text editor yang terintegrasi dengan tag html "textarea". Berikut adalah perintah untuk menginstalnya:
```
D:\Belajar\laravel-ui-bootstrap-crud> npm install tinymce --save-dev
```

Edit file *webpack.mix.js* pada directory project aplikasi:
```
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();

mix.copy('node_modules/tinymce/skins', 'public/js/skins');
mix.copy('node_modules/tinymce/icons', 'public/js/icons');
```

Edit file *resources/js/app.js* dan tambahkan kode ini:
```
require('./bootstrap');
require('tinymce');
require('tinymce/themes/silver');
require('tinymce/plugins/paste');
require('tinymce/plugins/advlist');
require('tinymce/plugins/autolink');
require('tinymce/plugins/lists');
require('tinymce/plugins/link');
require('tinymce/plugins/image');
require('tinymce/plugins/charmap');
require('tinymce/plugins/print');
require('tinymce/plugins/preview');
require('tinymce/plugins/anchor');
require('tinymce/plugins/textcolor');
require('tinymce/plugins/searchreplace');
require('tinymce/plugins/visualblocks');
require('tinymce/plugins/code');
require('tinymce/plugins/fullscreen');
require('tinymce/plugins/insertdatetime');
require('tinymce/plugins/media');
require('tinymce/plugins/table');
require('tinymce/plugins/contextmenu');
require('tinymce/plugins/code');
require('tinymce/plugins/help');
require('tinymce/plugins/wordcount');

$(document).ready(function () {
    tinymce.init({
        selector: '.tiny',
        height: 300,
        menubar: false,
        plugins: [
            'advlist autolink lists link image charmap print preview anchor textcolor',
            'searchreplace visualblocks code fullscreen',
            'insertdatetime media table contextmenu paste code help wordcount'
        ],
        toolbar: 'insert | undo redo |  formatselect | bold italic backcolor  | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css'
        ]
    });
});
```

Setelah Font Awesome dan TinyMCE terinstal, compile ulang file SASS dan JavaScript menggunakan Laravel Mix:
```
D:\Belajar\laravel-ui-bootstrap-crud> npm run dev
```

Jalankan aplikasi laravel untuk melihat perubahannya, dengan menggunakan perintah Artisan CLI's serve.
```
D:\Belajar\laravel-ui-bootstrap-crud> php artisan serve
```


## Yeah! Selamat, kita telah berhasil

![Capture 1](https://user-images.githubusercontent.com/51874332/110931198-91b94480-835c-11eb-9db4-fad124c671a0.PNG)
![Capture 2](https://user-images.githubusercontent.com/51874332/110931225-97af2580-835c-11eb-907b-0fe4419968e2.PNG)


