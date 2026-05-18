<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SearchEscrowHelp implements Tool
{
    /**
     * Get the description of the tool's purpose.
     */
    public function description(): Stringable|string
    {
        return 'Search the official Pakescrow.com knowledge base for workflows, transaction rules, fees, safeguards, and dispute resolution guidelines.';
    }

    /**
     * Execute the tool.
     */
    public function handle(Request $request): Stringable|string
    {
        $query = trim(strtolower((string) ($request['query'] ?? '')));

        if ($query === '') {
            return 'Search query is empty.';
        }

        $kb = $this->getKnowledgeBase();

        // Search the knowledge base for keyword matches
        $results = [];
        foreach ($kb as $item) {
            if (
                str_contains(strtolower($item['question']), $query) ||
                str_contains(strtolower($item['answer']), $query) ||
                str_contains(strtolower($item['category']), $query)
            ) {
                $results[] = "Category: {$item['category']}\nQuestion: {$item['question']}\nAnswer: {$item['answer']}";
            }
        }

        if (empty($results)) {
            return 'No matching knowledge base articles found for: '.$query;
        }

        return implode("\n\n---\n\n", array_slice($results, 0, 3));
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()
                ->description('Keyword or topic to search in the knowledge base (e.g., "fee", "dispute", "shipping", "hold")')
                ->required(),
        ];
    }

    /**
     * Get the full knowledge base dataset.
     */
    protected function getKnowledgeBase(): array
    {
        return [
            [
                'category' => 'general',
                'question' => 'What is Pakescrow.com and how does it work?',
                'answer' => "Pakescrow.com is Pakistan's first dedicated online escrow system designed to eliminate transactional scams. It acts as a trusted third party that holds a buyer's payment securely until the seller delivers the item as described. The general process is: (1) Create Request: A seller (or buyer) creates a transaction request with the selling price and terms. (2) Accept Invite: The other party accepts the invitation link (valid for 6 to 12 hours). (3) Secure Deposit: The buyer deposits the funds into Pakescrow's secure account and uploads the payment receipt. (4) Ship Item: Once the Admin approves the payment, the seller is notified that it is safe to ship the goods. (5) Inspect & Confirm: The buyer receives and inspects the item, then confirms satisfaction. (6) Payout Release: After a 2-day security hold, Pakescrow transfers the funds safely to the seller.",
            ],
            [
                'category' => 'general',
                'question' => 'What are the transaction fees on Pakescrow.com?',
                'answer' => 'Pakescrow.com charges a 2.5% processing fee calculated on the selling price. For example, if an item is sold for Rs. 10,000, a processing fee of Rs. 250 (2.5%) is added to the transaction. This fee is automatically rounded and displayed to the buyer as part of the total price during payment.',
            ],
            [
                'category' => 'general',
                'question' => 'Is Pakescrow.com secure? How does it prevent online scams?',
                'answer' => "Yes, Pakescrow.com is extremely secure. It prevents scams by ensuring: For Buyers: The seller never receives your money until you confirm that you have received and inspected the item. If the seller doesn't ship or sends the wrong item, your money is protected and can be refunded. For Sellers: You only ship the product after Pakescrow confirms that the buyer's funds have been received and verified by our Admin. No more fake bank transfer screenshots or unpaid deliveries.",
            ],
            [
                'category' => 'general',
                'question' => 'What payment methods does Pakescrow.com support?',
                'answer' => "Pakescrow.com supports standard, reliable payment methods in Pakistan, including Bank Transfers (IBFT), JazzCash, and Easypaisa. The buyer transfers funds directly to the platform's accounts, and the seller registers their payout details (Bank or Mobile Wallet) to receive their funds upon completion.",
            ],
            [
                'category' => 'seller',
                'question' => 'How do I create a selling request?',
                'answer' => "1. Log in to your Pakescrow dashboard and click 'Create Selling Request'. 2. Enter the item details: Title, Description, Selling Price, and Terms & Conditions. 3. Upload clear images of the item (optional but highly recommended). 4. Select the delivery option (standard or custom delivery date). 5. Enter your buyer's email or phone number and submit. A secure invitation link will be sent to them.",
            ],
            [
                'category' => 'seller',
                'question' => 'How do I invite my buyer to the request?',
                'answer' => "When creating or updating the request, simply enter the buyer's email address or mobile phone number. If you enter an email, Pakescrow sends an official secure email invitation. If you enter a phone number, Pakescrow sends a secure SMS invitation containing the direct link and a unique verification code.",
            ],
            [
                'category' => 'seller',
                'question' => 'How long does the invitation link remain active?',
                'answer' => 'The invitation token and link are highly secure and expire after 6 hours for Seller-initiated requests, and 12 hours for Buyer-initiated requests. If the buyer does not accept the request within this time frame, the token expires, and you will need to re-send or update the request to generate a new invitation.',
            ],
            [
                'category' => 'seller',
                'question' => 'The buyer has accepted the request. Is it safe for me to ship the item now?',
                'answer' => "NO! Acceptance only means the buyer agrees to the terms. You must wait until the request status changes to 'Approved' (or Payment Status shows 'Confirmed' by the Admin). Shipping before the Admin verifies and approves the buyer's payment proof exposes you to risk. Always wait for the official notification stating it is safe to ship.",
            ],
            [
                'category' => 'seller',
                'question' => 'Where and when do I submit my payment payout details?',
                'answer' => "Once the request status is 'Approved', a section titled 'Add Payment Details' will appear on your request dashboard. Enter your preferred payout account details (Bank Name, Account Number/IBAN, or JazzCash/Easypaisa number). You must enter these details before shipping. Pakescrow will store your payout method and transfer the money directly to it once the transaction completes.",
            ],
            [
                'category' => 'seller',
                'question' => 'I have shipped the product. What should I do next?',
                'answer' => "Go to your active request details, enter the shipping/tracking information, and click 'Mark Shipped'. This changes the status to Shipped and triggers an automatic notification to the buyer so they can track the shipment and prepare to receive it.",
            ],
            [
                'category' => 'seller',
                'question' => 'When will I actually receive my money?',
                'answer' => "After the buyer receives the product and clicks 'Confirm Order', Pakescrow applies a 2-day (48-hour) security hold. This hold is a safety buffer. Once the 48 hours expire, the Admin releases the funds. The money is deposited into your registered payout method, and the transaction is recorded as a 'Debit' in your Pakescrow transactions log.",
            ],
            [
                'category' => 'buyer',
                'question' => 'How do I accept a request invitation sent by a seller?',
                'answer' => "1. Open the email or SMS invitation sent to you by Pakescrow. 2. Click the secure link, or go to Pakescrow.com and input the unique verification code under the 'Find Request' page. 3. Review the request details, selling price, and terms. 4. Log in or create a Pakescrow account and click 'Accept Request'. The status will change to Accepted.",
            ],
            [
                'category' => 'buyer',
                'question' => 'How do I pay for the transaction?',
                'answer' => "1. Once you accept the request, the page will show Pakescrow's official payment accounts (Bank, JazzCash, or Easypaisa). 2. Transfer the exact Total Price (Selling Price + 2.5% Processing Fee) to Pakescrow's account. 3. Take a screenshot or receipt of your transaction. 4. Go back to your request details page, upload the receipt under 'Payment Proof', and click submit. 5. Your request will transition to 'Admin Pending Approval'.",
            ],
            [
                'category' => 'buyer',
                'question' => "Why is my request stuck in 'Admin Pending Approval'?",
                'answer' => "This status means you successfully uploaded your payment proof and the platform's Admin is verifying that the funds have cleared in our bank account. Verification is usually completed quickly. Once approved, the status changes to Approved, and the seller is notified to ship the item immediately.",
            ],
            [
                'category' => 'buyer',
                'question' => 'Why do I need to submit my shipping address on Pakescrow?',
                'answer' => 'Submitting your shipping address ensures that the seller has the official, verified destination details. Once you upload your payment, submit your address on the request page. You can also save it as your default shipping address for future transactions.',
            ],
            [
                'category' => 'buyer',
                'question' => 'What should I do when the seller ships the item?',
                'answer' => "Monitor the tracking details provided by the seller. Once the package arrives at your address, unpack it, inspect the contents thoroughly, and click the 'Received' button on your request dashboard. This changes the status to Delivered.",
            ],
            [
                'category' => 'buyer',
                'question' => "What is the 'Hold' feature and when should I use it?",
                'answer' => "If you receive the item but need extra time to inspect it (e.g., test electronics, check machinery, or verify authenticity), you can click 'Hold' on your request dashboard. This holds the funds securely in escrow for up to 3 days to prevent automatic completion. Use this if you are not ready to confirm the order but want to avoid a formal dispute while testing.",
            ],
            [
                'category' => 'buyer',
                'question' => 'How do I finalize the transaction so the seller gets paid?',
                'answer' => "Once you are fully satisfied with the item, click 'Confirm Order' (or Confirm Payment). This authorizes Pakescrow to proceed with releasing the funds to the seller. The status becomes Completed, and the money enters the 2-day security hold before final release to the seller.",
            ],
            [
                'category' => 'dispute',
                'question' => 'What is a Dispute? When should I open one?',
                'answer' => "A Dispute is a formal conflict resolution process on Pakescrow.com. You should open a dispute if: (1) The delivery date has passed, and the seller has not shipped the product. (2) The tracking number provided by the seller is fake or invalid. (3) The product you received is broken, wrong, damaged, or does not match the seller's description. CRITICAL: Always open a dispute BEFORE clicking 'Confirm Order'. Once you confirm, the transaction is finalized and the funds cannot be recovered.",
            ],
            [
                'category' => 'dispute',
                'question' => 'How do I open a Dispute?',
                'answer' => "1. Navigate to your active request dashboard. 2. Click 'Open Dispute'. 3. Choose or enter a clear Reason and write a detailed Description of the issue. 4. Upload images or video files as Attachments (proof). 5. Input an Offer Amount (refund proposed). 6. State whether you will return the goods (Return Goods: Yes/No) and provide your address. 7. Submit the dispute. The status changes to Dispute.",
            ],
            [
                'category' => 'dispute',
                'question' => "What is a 'Dispute Offer'?",
                'answer' => 'A Dispute Offer is a proposed settlement solution. For example, if an item cost Rs. 10,000 but arrived slightly damaged, you can offer to keep the item if the seller agrees to refund Rs. 3,000 (meaning you pay Rs. 7,000 and receive a Rs. 3,000 refund). An offer details the refund proposed (Offer Amount), whether the buyer will ship the item back (Return Goods), and the return shipping address.',
            ],
            [
                'category' => 'dispute',
                'question' => 'I am the seller. A dispute was opened against me. What are my options?',
                'answer' => "When a buyer opens a dispute, you will receive an email. You can: (1) Accept the Offer: If you agree with the proposed settlement, click 'Accept Offer'. This immediately closes the dispute as 'dispute resolved', and the Admin will release/refund the agreed-upon split. (2) Counter-Offer: Submit a counter-proposal (new offer amount and terms). (3) Escalate to Admin: If negotiations stall, either party can request Admin mediation.",
            ],
            [
                'category' => 'dispute',
                'question' => 'How does Admin Mediation work in escalated disputes?',
                'answer' => 'If the buyer and seller cannot agree on an offer, the Admin steps in. The Admin reviews the transaction terms, shipping/tracking proofs, dispute history, chat logs, and uploaded evidence (images/videos), and then makes a final, binding decision to release, refund, or split the funds. Once the Admin submits this verdict, the dispute is closed as \'dispute resolved\' and the funds are distributed.',
            ],
            [
                'category' => 'dispute',
                'question' => 'Can a buyer cancel a dispute?',
                'answer' => "Yes. If the buyer and seller resolve the issue privately (e.g., the seller sends a replacement or clarifies an issue), the buyer can click 'Cancel Dispute'. This moves the transaction back to the Delivered status with a payment Hold active, allowing the buyer to confirm or hold the request as normal.",
            ],
        ];
    }
}
