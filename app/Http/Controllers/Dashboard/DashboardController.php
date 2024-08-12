<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        if (Auth::user()->hasRole('school')) {
            $schoolId = Auth::user()->school_id;

            $studentsInSchool = User::where('school_id', $schoolId)
                ->where('role', 2)
                ->where('is_student', 1)
                ->count();

            $teachersInSchool = User::where('school_id', $schoolId)
                ->where('role', 1)
                ->where('is_student', 0)
                ->count();

        } else {
            $studentsInSchool = User::where('role', 2)
                ->where('is_student', 1)
                ->count();

            $teachersInSchool = User::where('role', 1)
                ->where('is_student', 0)
                ->count();
        }

        $totalUsers = $studentsInSchool + $teachersInSchool;

        // Calculate total schools, and breakdown by type
        $totalSchools = School::count();
        $nationalSchools = School::where('type', 'National')->count();
        $internationalSchools = School::where('type', 'International')->count();

        return view('dashboard.index', compact(
            'studentsInSchool',
            'teachersInSchool',
            'totalUsers',
            'totalSchools',
            'nationalSchools',
            'internationalSchools'
        )
        );
    }


}
