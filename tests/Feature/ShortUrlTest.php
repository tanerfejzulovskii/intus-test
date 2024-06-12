<?php

namespace Tests\Feature;

use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Url;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class ShortUrlTest extends TestCase
{
    use RefreshDatabase;

    public function test_shorten_url()
    {
        $url = 'https://www.example.com';

        $response = $this->postJson('/api/shorten', ['url' => $url]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['shortUrl']);

        $this->assertDatabaseHas('urls', [
            'primary_url' => $url
        ]);
    }

    public function test_shorten_duplicate_url()
    {
        $url = 'https://www.example.com';

        $this->postJson('/api/shorten', ['url' => $url]);

        $response = $this->postJson('/api/shorten', ['url' => $url]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['shortUrl']);
        $this->assertEquals(Url::where('primary_url', $url)->count(), 1);
    }

    public function test_redirect_to_primary_url()
    {
        $url = Url::factory()->create([
            'primary_url' => 'https://www.example.com',
            'short_url' => 'abcdef'
        ]);

        $response = $this->get('/short-urls/abcdef');

        $response->assertStatus(302);
        $response->assertRedirect($url->primary_url);
    }

    public function test_shorten_unsafe_url()
    {
        $this->mock(Client::class, function ($mock) {
            $mock->shouldReceive('post')
                ->andReturn(new Response(200, [], json_encode([
                    'matches' => [
                        [
                            'threatType' => 'MALWARE'
                        ]
                    ]
                ])));
        });

        $url = 'http://malware.testing.google.test/testing/malware/';

        $response = $this->postJson('/api/shorten', ['url' => $url]);

        $response->assertStatus(400);
        $response->assertJson(['error' => 'URL is unsafe']);
    }
}
