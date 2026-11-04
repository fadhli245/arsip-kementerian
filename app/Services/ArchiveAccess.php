<?php

namespace App\Services;

use App\Models\Arsip;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class ArchiveAccess
{
    public static function applyTo(Builder $query, User $user): Builder
    {
        return $query->where('is_active', true)
            ->where('approval_status', 'Disetujui')
            ->where(function (Builder $visibility) use ($user) {
                $visibility->whereIn('classification', ['Publik', 'Internal']);

                if ($user->division) {
                    $visibility->orWhere(function (Builder $restricted) use ($user) {
                        $restricted->whereIn('classification', ['Terbatas', 'Rahasia'])
                            ->whereJsonContains('allowed_divisions', $user->division);
                    });
                }
            });
    }

    public static function allows(Arsip $arsip, User $user): bool
    {
        if (!$arsip->is_active || $arsip->approval_status !== 'Disetujui') {
            return false;
        }

        if (in_array($arsip->classification, ['Publik', 'Internal'], true)) {
            return true;
        }

        return $user->division && in_array($user->division, $arsip->allowed_divisions ?? [], true);
    }
}
