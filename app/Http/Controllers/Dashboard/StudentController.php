<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\Program;
use App\Models\School;
use App\Models\Stage;
use App\Models\User;
use App\Models\UserCourse;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with(['details.stage', 'userCourses.program', 'groups'])
            ->where('role', '1');

        if ($request->filled('school')) {
            $query->whereHas('details', function ($q) use ($request) {
                $q->where('school_id', $request->input('school'));
            });
        }

        if ($request->filled('program')) {
            $query->whereHas('userCourses', function ($q) use ($request) {
                $q->where('program_id', $request->input('program'));
            });
        }

        if ($request->filled('grade')) {
            $query->whereHas('details', function ($q) use ($request) {
                $q->where('stage_id', $request->input('grade'));
            });
        }

        if ($request->filled('group')) {
            $query->whereHas('groups', function ($q) use ($request) {
                $q->where('group_id', $request->input('group'));
            });
        }

        $students = $query->simplePaginate(10);

        $schools = School::all();
        $programs = Program::all();
        $grades = Stage::all();
        $classes = Group::all();

        return view('dashboard.students.index', compact('students', 'schools', 'programs', 'grades', 'classes'));


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = School::all();
        $programs = Program::all();
        $stages = Stage::all();
        $groups = Group::all();
        return view('dashboard.students.create', compact('schools', 'programs', 'stages', 'groups'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15',
            'password' => 'required|string|confirmed|min:6',
            'school_id' => 'required|exists:schools,id',
            'program_id' => 'required|exists:programs,id',
            'stage_id' => 'required|exists:stages,id',
            'group_id' => 'nullable|exists:groups,id',
            'parent_image' => 'nullable|image|max:2048'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'school_id' => $request->school_id,
            'role' => '1',
            'is_student' => 1
        ]);
        foreach($request->program_id as $program_id){
        UserCourse::create([
            'user_id' => $user->id,
            'program_id' => $program_id
        ]);
    }

        UserDetail::create([
            'user_id' => $user->id,
            'school_id' => $request->school_id,
            'stage_id' => $request->stage_id
        ]);

        GroupStudent::create([
            'group_id' => $request->group_id,
            'student_id' => $user->id
        ]);

        if ($request->hasFile('parent_image')) {
            $imagePath = $request->file('parent_image')->store('images', 'public');
            $user->parent_image = $imagePath;
            $user->save();
        }

        return redirect()->route('students.index')->with('success', 'Student created successfully.');


    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        Excel::import(new UsersImport, $request->file('file'));

        return redirect()->back()->with('success', 'Users imported successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $student = User::findOrFail($id);
        $schools = School::all();
        $programs = Program::where('stage_id',UserDetail::where('user_id',$id)->first()->stage_id)->get();
        $stages = Stage::all();
        $groups = Group::all();
        return view('dashboard.students.edit', compact('student', 'schools', 'programs', 'stages', 'groups'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'phone' => 'required|string|max:15',
            'school_id' => 'required|exists:schools,id',
            'program_id' => 'required|exists:programs,id',
            'stage_id' => 'required|exists:stages,id',
            'group_id' => 'required|exists:groups,id',
            'parent_image' => 'nullable|image|max:2048'
        ]);

        $student = User::findOrFail($id);
        $student->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'school_id' => $request->school_id
        ]);
        UserCourse::where('user_id',$student->id)->delete();
        foreach($request->program_id as $program_id){
            // if(!UserCourse::where('user_id',$student->id)->where('program_id',$program_id)->first()){
                UserCourse::create([
                    'program_id' => $program_id,
                    'user_id' => $student->id
                ]);
            // }
    //         else{
    //     UserCourse::where('user_id', $student->id)->where('program_id','!=',$program_id)->update([
    //         'program_id' => $program_id
    //     ]);
    // }
    }
        UserDetail::where('user_id', $student->id)->update([
            'school_id' => $request->school_id,
            'stage_id' => $request->stage_id
        ]);

        GroupStudent::where('student_id', $student->id)->update([
            'group_id' => $request->group_id
        ]);

        if ($request->hasFile('parent_image')) {
            $imagePath = $request->file('parent_image')->store('images', 'public');
            $student->parent_image = $imagePath;
            $student->save();
        }

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = User::findOrFail($id);

        $student->delete();

        return redirect()->route('students.index')->with('success', 'Student deleted successfully.');

    }

    ///////////////////////////////////
    //Getters
    public function getCourses($id){
        $courses = Program::where('stage_id',$id)->get();
        return $courses;
    }
    ///////////////////////////////////
}