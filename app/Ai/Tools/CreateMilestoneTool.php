<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class CreateMilestoneTool implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Create a new escrow transaction request for the authenticated user. Use this when the user wants to start selling or escrowing an item.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        // $user = Auth::user();

        // if (!$user) {
        //     return "Error: Please tell the user they must be logged in to create a transaction.";
        // }

        $transaction = Transaction::create(
            [
                'creator_id' => 1,
                'buyer_id' => null,
                'seller_id' => 1,
                'title' => $request['title'],
                'description' => $request['description'] ?? 'No description provided',
                'amount' => $request['amount'],
                'deadline' => $request['deadline'] ?? 7,
                'status' => 'pending'
            ]
        );

        return "Successfully created escrow transaction ID #{$transaction->id} for {$request['amount']} PKR.";
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'title' => $schema->string()->description('The title or name of the item being sold')->required(),
            'amount' => $schema->number()->description('The total transaction amount in PKR')->required(),
            'description' => $schema->string()->description('Details about the transaction')->nullable(),
            'deadline' => $schema->integer()->description('The deadline timeframe in days')->nullable(),
        ];
    }
}
