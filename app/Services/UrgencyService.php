<?php

namespace App\Services;

use App\Models\Panti;

class UrgencyService
{
    public function refresh(Panti $panti): void
    {
        $supplyScore = $this->supplyScore($panti);
        $capacityScore = $this->capacityScore($panti);
        $vulnerabilityScore = $this->vulnerabilityScore($panti);
        $freshnessScore = $this->freshnessScore($panti);

        $score = (int) round(
            ($supplyScore * 0.5) +
            ($capacityScore * 0.2) +
            ($vulnerabilityScore * 0.2) +
            ($freshnessScore * 0.1)
        );

        $status = match (true) {
            $score >= 70 => Panti::URGENCY_KRITIS,
            $score >= 40 => Panti::URGENCY_WASPADA,
            default => Panti::URGENCY_AMAN,
        };

        $panti->forceFill([
            'urgency_score' => $score,
            'urgency_status' => $status,
            'urgency_calculated_at' => now(),
        ])->save();
    }

    protected function supplyScore(Panti $panti): int
    {
        $minStockDays = $panti->needs()
            ->active()
            ->min('stock_days_remaining');

        if (is_null($minStockDays)) {
            return 10;
        }

        return match (true) {
            $minStockDays <= 3 => 100,
            $minStockDays <= 7 => 70,
            $minStockDays <= 14 => 40,
            default => 10,
        };
    }

    protected function capacityScore(Panti $panti): int
    {
        if ($panti->capacity <= 0) {
            return 20;
        }

        $ratio = $panti->total_residents / $panti->capacity;

        return match (true) {
            $ratio > 1 => 100,
            $ratio >= 0.8 => 60,
            default => 20,
        };
    }

    protected function vulnerabilityScore(Panti $panti): int
    {
        return match ($panti->type) {
            Panti::TYPE_JOMPO => 80,
            Panti::TYPE_CAMPURAN => 70,
            Panti::TYPE_ANAK => 60,
            default => 40,
        };
    }

    protected function freshnessScore(Panti $panti): int
    {
        if (! $panti->updated_at) {
            return 10;
        }

        $days = $panti->updated_at->diffInDays(now());

        return match (true) {
            $days <= 7 => 100,
            $days <= 14 => 70,
            $days <= 30 => 40,
            default => 10,
        };
    }
}
