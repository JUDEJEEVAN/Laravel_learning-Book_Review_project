<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
// we have to use allias here because obviously we can't use two classes with the same name within a file
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Finder\Iterator\DateRangeFilterIterator;

class Book extends Model
{
    use HasFactory;

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    // In Laravel's Eloquent, when you define a scope, you don't need to explicitly pass the $query parameter.
    // Laravel automatically passes the current query builder instance as the first parameter to the scope method.

    // you can use scopes in a more user-friendly way by omitting the "scope" part of the method name when calling them.
    // This allows for cleaner and more readable code.
    public function scopeTitle(Builder $query, string $title):Builder {
        return $query->where('title', 'LIKE', '%'.$title.'%');
    }
    // this function could be called from the other classes or methods with
    // \App\Models\Book::title('titleName')->get();

    // this function will count all reviews, and also will count reviews within a specific time frame
    public function scopeWithReviewCount(Builder $query, string $from = null, string $to = null)
    {
        return $query->withCount([
            'reviews' => fn (Builder $subQuery) => $this->dateRangeFinder($subQuery, $from, $to)
        ]);
    }

    public function scopeWithAverageRating(Builder $query, string $from = null, string $to = null)
    {
        return $query->withCount([
            'reviews' => fn (Builder $subQuery) => $this->dateRangeFinder($subQuery, $from, $to)
        ])
            ->withAvg('reviews', 'rating');
    }

    public function scopePopular(Builder $query, string $from = null, string $to = null) {
        return $query->withReviewCount()
        ->orderBy('reviews_count', 'desc');
    }

    public function scopeHighestRated(Builder $query, string $from = null, string $to = null) {
        return $query->withAverageRating()
            ->having('reviews_count', '>=', 10)
            ->orderBy('reviews_avg_rating', 'desc');
    }

    public function scopeMinReviews(Builder $query, $minReviews) {
        return $query->having('reviews_count', '>=', $minReviews);
    }

    // this function is to use within the other queries in order to specify the date range
    private function dateRangeFinder(Builder $query, string $from = null , string $to = null) {
        if ($from && !$to) {
            $query->where('created_at', '>=', $from);
        } elseif (!$from && $to) {
            $query->where('created_at', '<', $to);
        } elseif ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }
    }

    public function scopePopularLastMonth (Builder $query) {
        return $query->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now())
            ->minReviews(3);
    }

    public function scopePopularLastSixMonths (Builder $query) {
        return $query->popular(now()->subMonths(6), now())
            ->highestRated(now()->subMonths(6), now())
            ->minReviews(7);
    }

    public function scopeHighestRatedLastMonth (Builder $query) {
        return $query
            ->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now())
            ->minReviews(3);
    }

    public function scopeHighestRatedLastSixMonths (Builder $query) {
        return $query
            ->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(6), now())
            ->minReviews(7);
    }

}
