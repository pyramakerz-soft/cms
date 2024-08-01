<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\School;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = Group::all();
        return view('dashboard.class.index', compact("classes"));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $schools = School::all();

        return view('dashboard.class.create', compact('schools'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',

        ]);

        $class = Group::create([
            'name' => $request->name,
            'school_id' => $request->school_id,
        ]);

        return redirect()->route('classes.create')->with('success', 'Class created successfully.');
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
        $class = Group::findOrFail($id);
        $schools = School::all();
        return view("dashboard.class.edit", compact(["class", 'schools']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'school_id' => 'required|exists:schools,id',

        ]);

        $class = Group::findOrFail($id);
        $class->update([
            'name' => $request->name,

            'school_id' => $request->school_id
        ]);



        return redirect()->route('classes.index')->with('success', 'Class updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $class = Group::findOrFail($id);

        $class->delete();

        return redirect()->route('classes.index')->with('success', 'Class deleted successfully.');
    }
}
