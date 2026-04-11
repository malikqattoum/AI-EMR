<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * Base Model for all Eloquent models.
 * 
 * Provides common scopes, utilities, and standardized behaviors
 * to ensure consistency across all models in the application.
 */
abstract class BaseModel extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The storage format of the model's dates.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        // Common boot logic can be added here
        // Individual models should use observers for specific lifecycle events
    }

    /**
     * Scope a query to only include active records.
     *
     * @param Builder $query The query builder
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include inactive records.
     *
     * @param Builder $query The query builder
     * @return Builder
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope a query to order by creation date (newest first).
     *
     * @param Builder $query The query builder
     * @param string $column The column to order by
     * @return Builder
     */
    public function scopeLatest(Builder $query, string $column = 'created_at'): Builder
    {
        return $query->orderBy($column, 'desc');
    }

    /**
     * Scope a query to order by creation date (oldest first).
     *
     * @param Builder $query The query builder
     * @param string $column The column to order by
     * @return Builder
     */
    public function scopeOldest(Builder $query, string $column = 'created_at'): Builder
    {
        return $query->orderBy($column, 'asc');
    }

    /**
     * Scope a query to filter by date range.
     *
     * @param Builder $query The query builder
     * @param Carbon|string $startDate Start date
     * @param Carbon|string $endDate End date
     * @param string $column The column to filter
     * @return Builder
     */
    public function scopeDateRange(Builder $query, $startDate, $endDate, string $column = 'created_at'): Builder
    {
        return $query->whereBetween($column, [$startDate, $endDate]);
    }

    /**
     * Scope a query to search by a term.
     *
     * @param Builder $query The query builder
     * @param string $term The search term
     * @param array $columns The columns to search
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $term, array $columns = []): Builder
    {
        if (empty($columns)) {
            $columns = $this->getSearchableColumns();
        }

        if (empty($columns) || empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($columns, $term) {
            foreach ($columns as $index => $column) {
                if ($index === 0) {
                    $q->where($column, 'like', "%{$term}%");
                } else {
                    $q->orWhere($column, 'like', "%{$term}%");
                }
            }
        });
    }

    /**
     * Scope a query to include only records with specific IDs.
     *
     * @param Builder $query The query builder
     * @param array $ids Array of IDs
     * @return Builder
     */
    public function scopeIds(Builder $query, array $ids): Builder
    {
        return $query->whereIn($this->getKeyName(), $ids);
    }

    /**
     * Get searchable columns for the search scope.
     * Override in child classes to customize.
     *
     * @return array
     */
    protected function getSearchableColumns(): array
    {
        // Default implementation returns empty array
        // Child classes should override this method
        return [];
    }

    /**
     * Find a model by ID or fail with a standardized exception.
     *
     * @param int $id The model ID
     * @param array $columns The columns to select
     * @return static
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public static function findByIdOrFail(int $id, array $columns = ['*']): static
    {
        return static::findOrFail($id, $columns);
    }

    /**
     * Find a model by ID.
     *
     * @param int $id The model ID
     * @param array $columns The columns to select
     * @return static|null
     */
    public static function findById(int $id, array $columns = ['*']): ?static
    {
        return static::find($id, $columns);
    }

    /**
     * Get the model's cache key.
     *
     * @return string
     */
    public function getCacheKey(): string
    {
        return sprintf(
            '%s:%s:%d',
            str_replace('\\', '.', strtolower(static::class)),
            $this->getTable(),
            $this->getKey()
        );
    }

    /**
     * Forget the model's cache.
     *
     * @return void
     */
    public function forgetCache(): void
    {
        Cache::forget($this->getCacheKey());
    }

    /**
     * Get the model as an array with optional transformations.
     *
     * @return array
     */
    public function toArray(): array
    {
        return parent::toArray();
    }

    /**
     * Check if the model is in a given state.
     *
     * @param string $attribute The attribute to check
     * @param mixed $value The value to check against
     * @return bool
     */
    public function is(string $attribute, $value): bool
    {
        return $this->{$attribute} === $value;
    }

    /**
     * Check if the model has a given attribute.
     *
     * @param string $attribute The attribute name
     * @return bool
     */
    public function hasAttribute(string $attribute): bool
    {
        return array_key_exists($attribute, $this->getAttributes());
    }

    /**
     * Duplicate the model instance.
     *
     * @return static
     */
    public function duplicate(): static
    {
        return $this->replicate();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'id';
    }
}
