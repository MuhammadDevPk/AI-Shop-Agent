<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class CreateMilestoneTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'A description of the tool.';
    }

    /**
     * Execute the tool.
     */
    public function handle(int $amount = 0, string $deadline = ''): Stringable|string
    {
        $user = Auth::user();

        $transaction = User::create([
            'buyer_id' => $user,
            'amount' => $amount,
            'deadline' => $deadline,
            'status' => 'pending_status'
        ]);
        $amount = (int) $amount;

        return "Successfully created milestone ID #{$transaction->id} for {$amount} PKR.";
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'value' => $schema->string()->required(),
        ];
    }
}
