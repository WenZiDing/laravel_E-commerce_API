<?php

namespace Controller;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Services\AuthService;
use App\Http\Services\ShortUrlService;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */

    private $fakeUser;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function testSharedUrl()
    {
        $product = Product::factory()->create();
        $id = $product->id;
        $this->mock(ShortUrlService::class, function ($mock) use ($id) {
            $mock->shouldReceive('makeShortUrl')
                ->with('http://127.0.0.1:2080/products/' . $id)
                ->andReturn('fakeUrl');
        });

        $this->mock(AuthService::class, function ($mock) {
            $mock->shouldReceive('fakeReturn');
        });

        $response = $this->call(
            'GET',
            '/products/' . $id . '/shared-url'
        );
        $response->assertOk();
        $res = json_decode($response->getContent(), true);
        $this->assertEquals($res['url'], 'fakeUrl');
    }
}