<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceCoreValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_enrollment_id',
        // Attendance months
        'jun_days', 'jun_present',
        'jul_days', 'jul_present',
        'aug_days', 'aug_present',
        'sept_days', 'sept_present',
        'oct_days', 'oct_present',
        'nov_days', 'nov_present',
        'dec_days', 'dec_present',
        'jan_days', 'jan_present',
        'feb_days', 'feb_present',
        'mar_days', 'mar_present',
        'apr_days', 'apr_present',
        // Core Values
        'maka_diyos_q1', 'maka_diyos_q2', 'maka_diyos_q3', 'maka_diyos_q4',
        'makatao_q1', 'makatao_q2', 'makatao_q3', 'makatao_q4',
        'maka_kalikasan_q1', 'maka_kalikasan_q2', 'maka_kalikasan_q3', 'maka_kalikasan_q4',
        'makabansa_q1', 'makabansa_q2', 'makabansa_q3', 'makabansa_q4',
    ];

    protected $appends = ['total_days', 'total_present', 'total_absent', 'months'];

    public function studentEnrollment()
    {
        return $this->belongsTo(StudentEnrollment::class);
    }

    /**
     * Safely get a numeric value (treat null as 0)
     */
    protected function getNumericValue($value)
    {
        return $value ?? 0;
    }

    public function getTotalDaysAttribute()
    {
        return $this->getNumericValue($this->jun_days) +
               $this->getNumericValue($this->jul_days) +
               $this->getNumericValue($this->aug_days) +
               $this->getNumericValue($this->sept_days) +
               $this->getNumericValue($this->oct_days) +
               $this->getNumericValue($this->nov_days) +
               $this->getNumericValue($this->dec_days) +
               $this->getNumericValue($this->jan_days) +
               $this->getNumericValue($this->feb_days) +
               $this->getNumericValue($this->mar_days) +
               $this->getNumericValue($this->apr_days);
    }

    public function getTotalPresentAttribute()
    {
        return $this->getNumericValue($this->jun_present) +
               $this->getNumericValue($this->jul_present) +
               $this->getNumericValue($this->aug_present) +
               $this->getNumericValue($this->sept_present) +
               $this->getNumericValue($this->oct_present) +
               $this->getNumericValue($this->nov_present) +
               $this->getNumericValue($this->dec_present) +
               $this->getNumericValue($this->jan_present) +
               $this->getNumericValue($this->feb_present) +
               $this->getNumericValue($this->mar_present) +
               $this->getNumericValue($this->apr_present);
    }

    public function getTotalAbsentAttribute()
    {
        return max(0, $this->total_days - $this->total_present);
    }

    public function getMonthsAttribute()
    {
        return [
            'jun'   => ['days' => $this->jun_days,  'present' => $this->jun_present],
            'jul'   => ['days' => $this->jul_days,  'present' => $this->jul_present],
            'aug'   => ['days' => $this->aug_days,  'present' => $this->aug_present],
            'sept'  => ['days' => $this->sept_days, 'present' => $this->sept_present],
            'oct'   => ['days' => $this->oct_days,  'present' => $this->oct_present],
            'nov'   => ['days' => $this->nov_days,  'present' => $this->nov_present],
            'dec'   => ['days' => $this->dec_days,  'present' => $this->dec_present],
            'jan'   => ['days' => $this->jan_days,  'present' => $this->jan_present],
            'feb'   => ['days' => $this->feb_days,  'present' => $this->feb_present],
            'mar'   => ['days' => $this->mar_days,  'present' => $this->mar_present],
            'apr'   => ['days' => $this->apr_days,  'present' => $this->apr_present],
        ];
    }
}
