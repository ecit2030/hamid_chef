<?php

namespace Tests\Feature\SystemEnhancements;

use App\Http\Requests\BookingRejectionRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RejectionReasonValidationPropertyTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Feature: system-enhancements, Property 2: Rejection Reason Validation
     *
     * For any rejection reason string composed entirely of whitespace characters,
     * the system should reject the submission and the booking status should remain unchanged.
     *
     * Validates: Requirements 1.5
     */
    public function test_whitespace_only_rejection_reasons_are_rejected(): void
    {
        // Test various whitespace-only strings
        $whitespaceStrings = [
            ' ',           // Single space
            '  ',          // Multiple spaces
            "\t",          // Tab
            "\n",          // Newline
            "\r",          // Carriage return
            " \t\n\r ",    // Mixed whitespace
            '     ',       // Many spaces
        ];

        foreach ($whitespaceStrings as $index => $whitespaceString) {
            $request = new BookingRejectionRequest();
            $validator = Validator::make(
                ['rejection_reason' => $whitespaceString],
                $request->rules(),
                $request->messages()
            );

            // Assert: Validation should fail for whitespace-only strings
            $this->assertTrue(
                $validator->fails(),
                "Whitespace-only string at index {$index} should fail validation"
            );

            // Assert: The rejection_reason field should have an error
            $this->assertTrue(
                $validator->errors()->has('rejection_reason'),
                "Whitespace-only string at index {$index} should have rejection_reason error"
            );
        }
    }

    /**
     * Test that valid rejection reasons pass validation
     */
    public function test_valid_rejection_reasons_pass_validation(): void
    {
        // Run 100 iterations with random valid rejection reasons
        for ($i = 0; $i < 100; $i++) {
            // Generate random rejection reason (5-500 characters)
            $rejectionReason = fake()->text(rand(5, 500));

            $request = new BookingRejectionRequest();
            $validator = Validator::make(
                ['rejection_reason' => $rejectionReason],
                $request->rules(),
                $request->messages()
            );

            // Assert: Validation should pass for valid strings
            $this->assertFalse(
                $validator->fails(),
                "Valid rejection reason at iteration {$i} should pass validation"
            );
        }
    }

    /**
     * Test that rejection reasons exceeding 500 characters fail validation
     */
    public function test_rejection_reasons_exceeding_max_length_fail(): void
    {
        // Test with strings exceeding 500 characters
        for ($i = 0; $i < 10; $i++) {
            // Generate string with exactly 501 characters
            $longReason = str_repeat('a', 501);

            $request = new BookingRejectionRequest();
            $validator = Validator::make(
                ['rejection_reason' => $longReason],
                $request->rules(),
                $request->messages()
            );

            // Assert: Validation should fail for strings exceeding 500 chars
            $this->assertTrue(
                $validator->fails(),
                "Rejection reason exceeding 500 chars at iteration {$i} should fail validation"
            );

            // Assert: The rejection_reason field should have a max error
            $this->assertTrue(
                $validator->errors()->has('rejection_reason'),
                "Rejection reason exceeding 500 chars at iteration {$i} should have error"
            );
        }
    }

    /**
     * Test that empty rejection reasons fail validation
     */
    public function test_empty_rejection_reasons_fail(): void
    {
        $emptyValues = [
            '',
            null,
        ];

        foreach ($emptyValues as $index => $emptyValue) {
            $request = new BookingRejectionRequest();
            $validator = Validator::make(
                ['rejection_reason' => $emptyValue],
                $request->rules(),
                $request->messages()
            );

            // Assert: Validation should fail for empty values
            $this->assertTrue(
                $validator->fails(),
                "Empty value at index {$index} should fail validation"
            );

            // Assert: The rejection_reason field should have an error
            $this->assertTrue(
                $validator->errors()->has('rejection_reason'),
                "Empty value at index {$index} should have rejection_reason error"
            );
        }
    }

    /**
     * Test that rejection reasons with leading/trailing whitespace but valid content pass
     */
    public function test_rejection_reasons_with_whitespace_padding_pass(): void
    {
        $validReasonsWithWhitespace = [
            ' Valid reason ',
            "\tValid reason\t",
            "\nValid reason\n",
            '  Valid reason with spaces  ',
        ];

        foreach ($validReasonsWithWhitespace as $index => $reason) {
            $request = new BookingRejectionRequest();
            $validator = Validator::make(
                ['rejection_reason' => $reason],
                $request->rules(),
                $request->messages()
            );

            // Assert: Validation should pass for strings with valid content
            $this->assertFalse(
                $validator->fails(),
                "Valid reason with whitespace padding at index {$index} should pass validation"
            );
        }
    }
}
