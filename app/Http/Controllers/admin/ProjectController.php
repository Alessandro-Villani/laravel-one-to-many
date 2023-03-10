<?php

namespace App\Http\Controllers\admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->query('status-filter');


        $selected = $filter ? $filter : 'all';


        $query = Project::orderBy('updated_at', 'DESC');

        if ($filter) {
            $filter_value = $filter === 'published' ? 1 : 0;
            $query->where('is_published', $filter_value);
        }

        $projects = $query->paginate(10);


        return view('admin.projects.index', compact('projects', 'selected'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $project = new Project();

        return view('admin.projects.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|unique:projects',
            'description' => 'required|string',
            'project_url' => 'required|url',
            'image_url' => 'image|nullable|mimes:jpg,jpeg,bmp,png'
        ]);

        $data = $request->all();


        if (array_key_exists('image_url', $data)) {

            $image_url = Storage::put('projects', $data['image_url']);
            $data['image_url'] = $image_url;
        }

        $new_project = new Project();

        $new_project->fill($data);
        $new_project->save();

        return to_route('admin.projects.show', $new_project->id)->with('message', "Il progetto <strong>" . strtoupper($new_project->name) . "</strong> è stato aggiunto con successo")->with('type', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $project = Project::withTrashed()->findOrFail($id);
        return view('admin.projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $project = Project::withTrashed()->findOrFail($id);
        return view('admin.projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('projects')->ignore($project->id)],
            'description' => 'required|string',
            'project_url' => 'required|url',
            'image_url' => 'image|nullable|mimes:jpg,jpeg,bmp,png'
        ]);

        $data = $request->all();

        if (array_key_exists('image_url', $data)) {

            if ($project->hasUploadedImage()) Storage::delete($project->image_url);
            $image_url = Storage::put('projects', $data['image_url']);
            $data['image_url'] = $image_url;
        }

        $project->fill($data);
        $project->save();

        return to_route('admin.projects.show', $project->id)->with('message', "Il progetto <strong>" . strtoupper($project->name) . "</strong> è stato modificato con successo")->with('type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {

        if ($project->hasUploadedImage()) Storage::delete($project->image_url);

        $project->delete();

        return to_route('admin.projects.index')->with('message', "Il progetto <strong>" . strtoupper($project->name) . "</strong> è stato eliminato con successo")->with('type', 'success');
    }

    /**
     * Toggle pubblication status.
     */
    public function toggleStatus(Project $project)
    {
        $project->is_published = !$project->is_published;

        $message = $project->is_published ? 'è stato pubblicato con successo.' : 'è stato spostato in bozze.';

        $project->save();

        return redirect()->back()->with('message', "Il progetto <strong>" . strtoupper($project->name) . "</strong> " . $message)->with('type', 'success');
    }

    /**
     * Display a listing of trashed resources.
     */
    public function trash()
    {
        $projects = Project::onlyTrashed()->get();

        return view('admin.projects.trash.index', compact('projects'));
    }

    //TODO restore and permanently delete functions
}
