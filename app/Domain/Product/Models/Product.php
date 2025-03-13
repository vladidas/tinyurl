<?php

declare(strict_types=1);

namespace App\Domain\Product\Models;

use App\Domain\Category\Models\Category;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 * @property float $price
 * @property int $rating
 * @property Category[] $categories
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 */
class Product extends Model
{
    use HasFactory, SoftDeletes;

    public const ID = 'id';
    public const NAME = 'name';
    public const DESCRIPTION = 'description';
    public const PRICE = 'price';
    public const RATING = 'rating';
    public const CATEGORY_IDS = 'category_ids';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DELETED_AT = 'deleted_at';
    public const CATEGORIES = 'categories';

    protected $fillable = [
        'name',
        'description',
        'price',
        'rating',
    ];

    protected $casts = [
        'price' => 'float',
        'rating' => 'integer',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function getId(): int
    {
        return $this->id;
    }

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
    }
}
