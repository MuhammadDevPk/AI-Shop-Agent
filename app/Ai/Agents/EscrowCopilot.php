<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class EscrowCopilot implements Agent, Conversational, HasTools
{
    use Promptable;

    protected array $userState;

    public function __construct(array $userState)
    {
        $this->userState = $userState;
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return "You are the Pak Escrow Digital Copilot. Your job is to guide users through transactions. " .
            "CRITICAL CURRENT USER CONTEXT: " . json_encode($this->userState) .
            " Guide them exactly on what their current step demands or correct what they are doing wrong.";
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new CreateMilestoneTool(),
            new VerifyOtpTool()
        ];
    }
}
