<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'student_id',
        'user_id',
        'subject_id',
        'grade_section',
        'quarter_id',
        'year_level_id',
        'school_year_id',
        'teacher',
        // Written works scores (10 items)
        'written_work_1',
        'written_work_2',
        'written_work_3',
        'written_work_4',
        'written_work_5',
        'written_work_6',
        'written_work_7',
        'written_work_8',
        'written_work_9',
        'written_work_10',
        'written_works_total',
        'written_works_ps',
        'written_works_ws',
        // Performance tasks scores (10 items)
        'performance_task_1',
        'performance_task_2',
        'performance_task_3',
        'performance_task_4',
        'performance_task_5',
        'performance_task_6',
        'performance_task_7',
        'performance_task_8',
        'performance_task_9',
        'performance_task_10',
        'performance_tasks_total',
        'performance_tasks_ps',
        'performance_tasks_ws',
        // Quarterly Assessment
        'quarterly_assessment',
        'quarterly_assessment_ps',
        'quarterly_assessment_ws',
        // Final Grades
        'initial_grade',
        'quarterly_grade',
        // Global header fields for highest possible scores
        'hww',
        'hpt',
        'global_hqa',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        // Global header arrays (stored as JSON)
        'hww'                     => 'array',
        'hpt'                     => 'array',
        'global_hqa'              => 'float',
        // Totals and weighted scores
        'written_works_total'     => 'float',
        'written_works_ps'        => 'float',
        'written_works_ws'        => 'float',
        'performance_tasks_total' => 'float',
        'performance_tasks_ps'    => 'float',
        'performance_tasks_ws'    => 'float',
        'quarterly_assessment'    => 'float',
        'quarterly_assessment_ps' => 'float',
        'quarterly_assessment_ws' => 'float',
        'initial_grade'           => 'float',
        'quarterly_grade'         => 'float',
    ];

    /**
     * Get the teacher (user) that owns the class record.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the student associated with the class record.
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the subject associated with the class record.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }
    /**
     * Get the quarter associated with the class record.
     */
    public function quarter()
    {
        return $this->belongsTo(Quarter::class, 'quarter_id', 'id');
    }
    /**
     * Get the quarter associated with the class record.
     */
    public function yearLevel()
    {
        return $this->belongsTo(YearLevel::class, 'year_level_id', 'id');
    }

    /**
     * (Optional) Get the school year associated with this record.
     * Adjust the relationship if school_year is a string or another model.
     */
    public function schoolYear()
    {
        // If you have a SchoolYear model and store an ID, use that.
        // Otherwise, you might remove or adjust this relationship.
        return $this->belongsTo(SchoolYear::class, 'school_year_id', 'id');
    }

    /**
     * (Optional) Get the school info associated with the record.
     * Adjust if school_id references another model.
     */
    public function schoolInfo()
    {
        return $this->belongsTo(SchoolInfo::class);
    }
}
