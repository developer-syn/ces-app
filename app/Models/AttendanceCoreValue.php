<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceCoreValue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['total_days', 'total_present', 'total_absent'];

    /**
     * Get the student enrollment that owns the attendance record.
     */
    public function studentEnrollment()
    {
        return $this->belongsTo(StudentEnrollment::class);
    }

    /**
     * Get total school days accessor.
     */
    public function getTotalDaysAttribute()
    {
        return $this->jun_days + $this->jul_days + $this->aug_days +
               $this->sept_days + $this->oct_days + $this->nov_days +
               $this->dec_days + $this->jan_days + $this->feb_days +
               $this->mar_days + $this->apr_days;
    }

    /**
     * Get total present days accessor.
     */
    public function getTotalPresentAttribute()
    {
        return $this->jun_present + $this->jul_present + $this->aug_present +
               $this->sept_present + $this->oct_present + $this->nov_present +
               $this->dec_present + $this->jan_present + $this->feb_present +
               $this->mar_present + $this->apr_present;
    }

    /**
     * Get total absent days accessor.
     */
    public function getTotalAbsentAttribute()
    {
        return $this->total_days - $this->total_present;
    }

    /**
     * Get all months with their attendance data
     */
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
