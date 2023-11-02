<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    private function filterBooks($books, $filter) {
        $books = match ($filter) {
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLastSixMonths(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6months' => $books->highestRatedLastSixMonths(),
            default => $books->latest()
        };
        return $books;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $filter = $request->input('filter');

        $books = Book::when($title, fn($query, $title) => $query = Book::title($title));

        $books = match($filter) {
            'popular_last_month' => $books->popularLastMonth(),
            'popular_last_6months' => $books->popularLastSixMonths(),
            'highest_rated_last_month' => $books->highestRatedLastMonth(),
            'highest_rated_last_6month' => $books->highestRatedLastSixMonths(),
            default => $books->latest()->withAverageRating()->withReviewCount()
        };

        // $books = cache()->remember($books, 3600 , fn () => $books->get())
        //
        // this is how we cache something
        // when we cache something it will be stored in the cache for a specific time period, in our case 3600seconds
        // the cache driver will look for $books in the cache and if it didn't find it, it will run the function within the arguement. in our case the lambda function which returs values from $books->get()
        // if we specify the driver to look for the $books in the cache and if someone has already searched for some specific title or had a filter at that time
        // it would have been stored and the cache driver will get that stored data of books from that cache and it will show that data the next and following times too.
        // in order to stop this from happening we have to specify a cache key which will be generated everytime the user specifies something,
        // and the cache driver should serarch for that key to display data to the user
        // and if the user specifies another data in our case if he needs another book, the key should be generated again and the cache would be updated.
        // in order to do that we could say -------- $cacheKey = '$books' . ':' . '$filter' . ':' , '$title';
        // and we could update the value which sould be returned like this ------ $books = cache()->remember($cacheKey, 3600 , fn () => $books->get());

        // $cacheKey = 'books :' . $filter . ':' . $title;
        // $books = cache()->remember($cacheKey, 3600 , fn () => $books->get());
        $books = $books->get();

        return view('books.index', ['books'=>$books]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return view('books.show', ['book' => Book::with([
            'reviews' => fn ($query) => $query->latest()
        ])->withAverageRating()->withReviewCount()->findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
