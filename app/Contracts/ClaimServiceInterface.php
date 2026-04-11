<?php

namespace App\Contracts;

/**
 * Interface for claim-related services.
 * 
 * Provides a standardized contract for claim operations
 * to ensure consistency and enable service swapping.
 */
interface ClaimServiceInterface
{
    /**
     * Create a new insurance claim.
     *
     * @param array $data Claim data
     * @return array
     */
    public function createClaim(array $data): array;

    /**
     * Submit a claim to clearinghouse.
     *
     * @param int $claimId The claim ID
     * @return array
     */
    public function submitClaim(int $claimId): array;

    /**
     * Update claim status.
     *
     * @param int $claimId The claim ID
     * @param string $status New status
     * @param array $metadata Additional metadata
     * @return array
     */
    public function updateClaimStatus(int $claimId, string $status, array $metadata = []): array;

    /**
     * Mark claim as approved.
     *
     * @param int $claimId The claim ID
     * @return array
     */
    public function markApproved(int $claimId): array;

    /**
     * Mark claim as denied.
     *
     * @param int $claimId The claim ID
     * @param string $denialReason Denial reason
     * @return array
     */
    public function markDenied(int $claimId, string $denialReason): array;

    /**
     * Mark claim as paid.
     *
     * @param int $claimId The claim ID
     * @param float $paidAmount Amount paid
     * @return array
     */
    public function markPaid(int $claimId, float $paidAmount): array;

    /**
     * Get claim statistics.
     *
     * @param int $doctorId The doctor ID
     * @param string|null $startDate Start date filter
     * @param string|null $endDate End date filter
     * @return array
     */
    public function getStatistics(int $doctorId, ?string $startDate = null, ?string $endDate = null): array;

    /**
     * Predict claim denial risk.
     *
     * @param int $claimId The claim ID
     * @return array
     */
    public function predictDenialRisk(int $claimId): array;

    /**
     * Check claim eligibility.
     *
     * @param int $claimId The claim ID
     * @return array
     */
    public function checkEligibility(int $claimId): array;

    /**
     * Generate claim report.
     *
     * @param int $claimId The claim ID
     * @return array
     */
    public function generateReport(int $claimId): array;

    /**
     * Batch submit claims.
     *
     * @param array $claimIds Array of claim IDs
     * @return array
     */
    public function batchSubmit(array $claimIds): array;
}
