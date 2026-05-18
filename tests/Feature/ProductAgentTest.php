<?php

namespace Tests\Feature;

use App\Ai\Agents\ProductAgent;
use App\Ai\Tools\SearchProducts;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Ai\Ai;
use Tests\TestCase;

class ProductAgentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the agent has the SearchProducts tool registered correctly.
     */
    public function test_agent_has_search_products_tool_registered(): void
    {
        $agent = new ProductAgent;
        $tools = collect($agent->tools());

        $this->assertCount(1, $tools);
        $this->assertInstanceOf(SearchProducts::class, $tools->first());
    }

    /**
     * Test that the agent can be prompted successfully with fake AI gateway responses.
     */
    public function test_agent_prompt_execution(): void
    {
        // Seed a laptop
        Product::create([
            'name' => 'Apple MacBook Air M3',
            'category' => 'Laptop',
            'price' => 120000,
            'stock' => 5,
        ]);

        Ai::fakeAgent(ProductAgent::class, [
            'Yes, we do have laptops available!',
        ]);

        $agent = new ProductAgent;
        $response = $agent->prompt('do you have laptops?');

        $this->assertNotNull($response);
        $this->assertEquals('Yes, we do have laptops available!', $response->text);
    }

    /**
     * Test the chat stream controller route.
     */
    public function test_chat_stream_route_execution(): void
    {
        Ai::fakeAgent(ProductAgent::class, [
            'Hello! I can help you with products.',
        ]);

        $response = $this->get('/chat/stream?message=hello');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/event-stream; charset=UTF-8');

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);
    }
}
