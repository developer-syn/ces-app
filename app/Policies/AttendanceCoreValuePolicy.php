<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AttendanceCoreValue;
use App\Models\StudentEnrollment;

class AttendanceCoreValuePolicy
{
    /**
     * Create a new policy instance.
     */
    public function create(User $user, StudentEnrollment $enrollment)
    {
        return $user->isTeacher() &&
            $enrollment->teacher_id === $user->id;
    }

    public function update(User $user, AttendanceCoreValue $record)
    {
        return $user->isTeacher() &&
            $record->studentEnrollment->teacher_id === $user->id;
    }
}
