<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Project, ProjectStatus};
use App\Http\Requests\{StoreProjectsRequest,UpdateProjectsRequest};

/**
 * Class ProjectsController
 * @package App\Http\Controllers
 */
class ProjectsController extends Controller
{
    /**
     * Display a listing of Project.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::all();

        return view('admin::projects.index', compact('projects'));
    }

    /**
     * Show the form for creating new Project.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $relations = [
            'clients' => Client::get()->pluck('first_name', 'id')->prepend('Please select', ''),
            'project_statuses' => ProjectStatus::get()->pluck('title', 'id')->prepend('Please select', ''),
        ];

        return view('admin::projects.create', $relations);
    }

    /**
     * Store a newly created Project in storage.
     *
     * @param  \App\Http\Requests\StoreProjectsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProjectsRequest $request)
    {
        Project::create($request->all());

        return redirect()->route('projects.index');
    }

    /**
     * Show the form for editing Project.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     *
     */
    public function edit($id)
    {
        $relations = [
            'clients' => Client::get()->pluck('first_name', 'id')->prepend('Please select', ''),
            'project_statuses' => ProjectStatus::get()->pluck('title', 'id')->prepend('Please select', ''),
        ];

        $project = Project::findOrFail($id);

        return view('admin::projects.edit', compact('project') + $relations);
    }

    /**
     * Update Project in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectsRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectsRequest $request, $id)
    {
        $project = Project::findOrFail($id);
        $project->update($request->all());

        return redirect()->route('projects.index');
    }

    /**
     * Display Project.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $relations = [
            'clients' => Client::get()->pluck('first_name', 'id')->prepend('Please select', ''),
            'project_statuses' => ProjectStatus::get()->pluck('title', 'id')->prepend('Please select', ''),
        ];

        $project = Project::findOrFail($id);

        return view('admin::projects.show', compact('project') + $relations);
    }

    /**
     * Remove Project from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index');
    }

    /**
     * Delete all selected Project at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Project::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }
}
