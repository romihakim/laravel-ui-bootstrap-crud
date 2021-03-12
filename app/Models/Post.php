<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

    static function unique_slug($slug, $id = 0)
    {
        $count = self::where('id', '!=', $id)->where('slug', $slug)->count();

        if ($count > 0) {
            $exist = self::where('id', '!=', $id)->where('slug', 'like', $slug . '%')
                ->whereRaw('CONCAT("", RIGHT(slug, 1) * 1) = RIGHT(slug, 1)')
                ->orderByRaw('LENGTH(slug) ASC, slug DESC')
                ->first();

            if (isset($exist)) {
                $last = Str::substr($exist['slug'], Str::length($slug));
                $last = Str::of($last)->ltrim('-');
                $last = intval('0' . $last) + 1;

                $slug = $slug . '-' . $last;
            } else {
                $slug = $slug . '-' . $count;
            }
        }

        return $slug;
    }
}
