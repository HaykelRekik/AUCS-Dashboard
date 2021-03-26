<?php

namespace App\Modules\Book\Controllers;

use App\Models\Book;
use App\Modules\Book\Requests\BookRequest;
use App\Modules\Book\Resources\BookResource;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use \Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookController extends Controller
{
    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        [$column, $order] = explode(',', $request->input('sortBy', 'id,asc'));
        $pageSize = (int) $request->input('pageSize', 10);

        $resource = Book::query()
            ->when($request->filled('search'), function (Builder $q) use ($request) {
                $q->where(Book::COLUMN_TITLE, 'like', '%' . $request->search . '%');
            })
            ->orderBy($column, $order)->paginate($pageSize);

        return BookResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     * @param BookRequest $request
     * @return JsonResponse
     */
    public function store(BookRequest $request)
    {
        $data = $request->validated();
        $book = new Book($data);
        $book->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully created'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Book $book
     * @return BookResource
     */
    public function show(Book $book)
    {
        return new BookResource($book);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BookRequest $request
     * @param Book $book
     * @return JsonResponse
     */
    public function update(BookRequest $request, Book $book)
    {
        $data = $request->validated();
        $book->fill($data)->save();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully updated'
        ]);
    }

    /**
     * @param Book $book
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return response()->json([
            'type' => self::RESPONSE_TYPE_SUCCESS,
            'message' => 'Successfully deleted'
        ]);
    }

}
