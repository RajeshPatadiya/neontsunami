<?php namespace NeonTsunami;

use NeonTsunami\Post;

use Carbon\Carbon;

class Post extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['series_id', 'title', 'slug', 'content'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['published_at', 'deleted_at'];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */
    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', Carbon::now());
    }

    public function scopeRelated($query, Post $post)
    {
        $tags = $post->tags->modelKeys();

        return $query->whereHas('tags', function($query) use ($tags)
        {
            $query->whereIn('id', $tags);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors and mutators
    |--------------------------------------------------------------------------
    */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = str_slug($value);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function series()
    {
        return $this->belongsTo('NeonTsunami\Series');
    }

    public function tags()
    {
        return $this->belongsToMany('NeonTsunami\Tag')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo('NeonTsunami\User');
    }

}