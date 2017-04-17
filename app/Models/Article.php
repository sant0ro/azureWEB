<?php

namespace App\Models;

use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;

/**
 * Class Article
 * @package App\Models
 */
class Article extends ChocolateyModel
{
    use Eloquence, Mappable;

    /**
     * Disable Timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'chocolatey_articles';

    /**
     * Primary Key of the Table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that will be mapped.
     *
     * @var array
     */
    protected $maps = array('updatedAt' => 'updated_at', 'createdAt' => 'created_at');

    /**
     * The Appender(s) of the Model.
     *
     * @var array
     */
    protected $appends = array('updatedAt', 'createdAt');

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('updated_at', 'created_at');

    /**
     * Store a new CMS Article.
     *
     * @param string $title
     * @param string $description
     * @param string $content
     * @param string $author
     * @param string $categories
     * @param string $imageUrl
     * @param string $thumbnailUrl
     * @return Article
     */
    public function store(string $title, string $description, string $content, string $author, string $categories, string $imageUrl, string $thumbnailUrl): Article
    {
        $this->attributes['title'] = $title;
        $this->attributes['description'] = $description;
        $this->attributes['content'] = $content;
        $this->attributes['author'] = $author;
        $this->attributes['categories'] = $categories;
        $this->attributes['imageUrl'] = $imageUrl;
        $this->attributes['thumbnailUrl'] = $thumbnailUrl;

        $this->save();

        return $this;
    }

    /**
     * Get All Article Categories from the Article.
     *
     * @return array
     */
    public function getCategoriesAttribute(): array
    {
        $categories = array();

        foreach (explode(',', $this->attributes['categories']) as $articleCategory) {
            $categories[] = ArticleCategory::query()->where('link', $articleCategory)->first();
        }

        return $categories;
    }
}
