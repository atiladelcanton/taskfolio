<?php

namespace App\Rules;

use App\Domain\Sprint\Models\Sprint;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SprintDateRangeAvailable implements ValidationRule
{


    public function __construct(
        protected readonly int $projectId,
        protected readonly ?int $sprintId,
        protected readonly ?string $endDate)
    {
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $projectId = $this->projectId;
        $sprintId = $this->sprintId;
        $startDate = $value;
        $endDate = $this->endDate;
        if ($startDate && $endDate) {
            $conflict = Sprint::query()->where(function ($query) use ($projectId, $sprintId, $startDate, $endDate) {
                $query->whereDate('date_start', '<=', $endDate)
                    ->whereDate('date_end', '>=', $startDate)
                    ->where('project_id', $projectId)
                    ->when($sprintId, fn ($query) => $query->where('id', '!=', $sprintId));
            })->exists();

            if ($conflict) {
                $fail('Ja tem sprint cadastrada nesse intervalo de data.');
            }
        }
    }
}
