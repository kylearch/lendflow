<?php

namespace App\Http\Controllers\Api\V1\NYT;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\NYT\BestSellersRequest;
use Illuminate\Support\Facades\Http;

class BestSellersController extends Controller
{
    public function __invoke(BestSellersRequest $request)
    {
        $isbn = $this->formatISBNsAsString($request->validated('isbn'));
        $response = Http::get('https://api.nytimes.com/svc/books/v3/lists/best-sellers/history.json', [
            'api-key' => config('services.nyt.key'),
            'author' => $request->validated('author'),
            'title' => $request->validated('title'),
            'offset' => $request->validated('offset'),
            'isbn' => $isbn,
        ]);


        // TODO: Decide on an API error handling strategy
        if ($response->failed()) {
            abort($response->status());
        }

        return $response->json();
    }

    private function formatISBNsAsString(mixed $validated): ?string
    {
        if (is_null($validated)) {
            return null;
        }

        return collect($validated)->map(fn ($isbn) => (string)$isbn)->implode(';');
    }
}
