<?php

namespace Tests\Feature\Api\V1\NYT;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class BestSellerTest extends TestCase
{

    private const array RESPONSE_STRUCTURE = [
        'status',
        'copyright',
        'num_results',
        'results' => [
            '*' => [
                'title',
                'description',
                'contributor',
                'author',
                'contributor_note',
                'price',
                'age_group',
                'publisher',
                'isbns' => [
                    '*' => [
                        'isbn10',
                        'isbn13',
                    ],
                ],
                'ranks_history' => [
                    '*' => [
                        'primary_isbn10',
                        'primary_isbn13',
                        'rank',
                        'list_name',
                        'display_name',
                        'published_date',
                        'bestsellers_date',
                        'weeks_on_list',
                        'rank_last_week',
                        'asterisk',
                        'dagger',
                    ],
                ],
                'reviews' => [
                    '*' => [
                        'book_review_link',
                        'first_chapter_link',
                        'sunday_review_link',
                        'article_chapter_link',
                    ],
                ],
            ],
        ],
    ];

    private static string $successResponse;

    public function testMissingParameters()
    {
        $this->fakeSuccess();
        $response = $this->json('get', '/api/1/nyt/best-sellers');

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['author', 'isbn', 'title', 'offset']);
    }

    private function fakeSuccess(): void
    {
        self::$successResponse ??= file_get_contents(storage_path('testing/responses/nyt/best-sellers.json'));
        Http::fake(['https://api.nytimes.com/svc/books/v3/lists/best-sellers/history.json*' => Http::response(self::$successResponse),]);
    }

    public function testAuthor()
    {
        $this->fakeSuccess();

        // Test with a single author
        $response = $this->json('get', '/api/1/nyt/best-sellers?author=Stephen+King');
        $response->assertStatus(200);
        $response->assertJsonStructure(self::RESPONSE_STRUCTURE);

        // Test with multiple authors (should fail)
        $response = $this->json('get', '/api/1/nyt/best-sellers?author[]=Stephen+King');
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('author');
    }

    public function testISBN()
    {
        $this->fakeSuccess();

        // Test with a single 10 digit ISBN
        $response = $this->json('get', '/api/1/nyt/best-sellers?isbn[]=9781982110');
        $response->assertStatus(200);
        $response->assertJsonStructure(self::RESPONSE_STRUCTURE);

        // Test with a single 13 digit ISBN
        $response = $this->json('get', '/api/1/nyt/best-sellers?isbn[]=9781982110567');
        $response->assertStatus(200);
        $response->assertJsonStructure(self::RESPONSE_STRUCTURE);

        // Test with multiple ISBNs
        $response = $this->json('get', '/api/1/nyt/best-sellers?isbn[]=9781982110&isbn[]=9781982110567');
        $response->assertStatus(200);
        $response->assertJsonStructure(self::RESPONSE_STRUCTURE);

        // Test with an invalid (9 digit) ISBN (should fail)
        $response = $this->json('get', '/api/1/nyt/best-sellers?isbn[]=978198211');
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('isbn');

        // Test with an invalid (11 digit) ISBN (should fail)
        $response = $this->json('get', '/api/1/nyt/best-sellers?isbn[]=97819821111');
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('isbn');

        // Test with an invalid (14 digit) ISBN (should fail)
        $response = $this->json('get', '/api/1/nyt/best-sellers?isbn[]=97819821111111');
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('isbn');

        // Test with one valid and one invalid ISBN (should fail)
        $response = $this->json('get', '/api/1/nyt/best-sellers?isbn[]=9781982110&isbn[]=97819821111111');
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('isbn');
    }

    public function testTitle()
    {
        $this->fakeSuccess();

        // Test with a single title
        $response = $this->json('get', '/api/1/nyt/best-sellers?title=The+Institute');
        $response->assertStatus(200);

        // Test with multiple titles (should fail)
        $response = $this->json('get', '/api/1/nyt/best-sellers?title[]=The+Institute');
        $response->assertStatus(422);
        $response->assertJsonValidationErrorFor('title');
    }

    public function testOffset()
    {
        $this->fakeSuccess();

        // Test with a valid offset
        $response = $this->json('get', '/api/1/nyt/best-sellers?offset=0');
        $response->assertStatus(200);

        // Test with another valid offset (multiple of 20)
        $response = $this->json('get', '/api/1/nyt/best-sellers?offset=40');
        $response->assertStatus(200);

        // Test with an invalid offset (not a multiple of 20)
        $response = $this->json('get', '/api/1/nyt/best-sellers?offset=1');
        $response->assertStatus(422);

        // Test with an invalid offset (not an integer)
        $response = $this->json('get', '/api/1/nyt/best-sellers?offset=1.5');
        $response->assertStatus(422);

        // Test with an invalid offset (non-numeric)
        $response = $this->json('get', '/api/1/nyt/best-sellers?offset=twenty');
        $response->assertStatus(422);
    }

}
