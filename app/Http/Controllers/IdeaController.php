<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\Category;
use App\Models\AcademicYear;
use App\Models\Department;
use App\Models\Comment;
use App\Models\User;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\IdeaCreateMail;
use Auth;
use File;
use ZipArchive;
use Str;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ideas = Idea::paginate(5);

        return view('idea.index',compact('ideas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::get();
        $departments = Department::get();
        $academic_years = AcademicYear::get();

        return view('idea.create', compact('categories','departments','academic_years'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            "category_id" => ['required'],
            "academic_year_id" => ['required'],
            "title" => ['required'],
            "description" => ['required'],
            "document_url" => ['nullable'],
            "annonymous" => ['nullable'],
        ]);

        if ($request->closure_date < now()->toDateString()) {
            return redirect()->route('ideas.create')->with('error', 'Idea Can\'t Be Submit.');
        }

        $data['created_by'] = Auth::id();
        $data['user_id'] = Auth::id();
        $data['department_id'] = Auth::user()->department_id;

        if ($request->document_url) {
            $ext = $request->document_url->getClientOriginalExtension();

            $name = time().Str::random(6).".".$ext;

            $data['document_url'] = $request->document_url->storeAs('document', $name);
        }

        $data['annonymous'] = $request->annonymous ? $request->annonymous : 0;
        $idea = Idea::create($data);

        $this->sendMail($idea);

        return redirect()->route('ideas.index')->with('success', 'Saved Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function show(Idea $idea)
    {
        $idea->increment('view_count');
        $check = Comment::where('user_id',Auth::id())->where('idea_id',$idea->id)->first();
        $reaction_check = Reaction::where('user_id',Auth::id())->where('idea_id',$idea->id)->first();

        $disable = $check ? 'disabled' : '';
        $closure_check = $idea->academic->final_closure_date <= now()->toDateString() ? true : false;

        if ($reaction_check) {
            $reaction_up = ($reaction_check->up_down == 1) ? 'secondary' : 'outline-secondary';
            $reaction_down = ($reaction_check->up_down == 2) ? 'secondary' : 'outline-secondary';

            return view('idea.view', compact('idea','disable','reaction_up','reaction_down','closure_check'));
        }

        return view('idea.view', compact('idea','disable','closure_check'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function edit(Idea $idea)
    {
        $categories = Category::get();
        $departments = Department::get();
        $academic_years = AcademicYear::get();

        return view('idea.edit',compact('idea', 'categories','departments','academic_years'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Idea $idea)
    {
        $data = $request->validate([
            "category_id" => ['required'],
            "title" => ['required'],
            "description" => ['required'],
            "document_url" => ['nullable'],
            "annonymous" => ['nullable'],
        ]);

        $data['last_modified_by'] = Auth::id();
        $data['user_id'] = Auth::id();
        $data['department_id'] = Auth::user()->department_id;

        if ($request->document_url) {
            \Storage::delete($idea->document_url);
            $ext = $request->document_url->getClientOriginalExtension();

            $name = time().Str::random(6).".".$ext;

            $data['document_url'] = $request->document_url->storeAs('document', $name);
        }

        $data['annonymous'] = $request->annonymous ? $request->annonymous : 0;

        $idea->update($data);

        return redirect()->route('ideas.index')->with('success', 'Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Idea  $idea
     * @return \Illuminate\Http\Response
     */
    public function destroy(Idea $idea)
    {
        $idea->delete();
        $idea->comments()->delete();
        $idea->reactions()->delete();

        return redirect()->route('ideas.index')->with('success', 'Deleted Successfully.');
    }

    public function sendMail($idea)
    {
        $users = User::where('role',2)->get();

        foreach ($users as $key => $user) {
            Mail::to($user)->send(new IdeaCreateMail($idea));
        }
    }

    public function ideaListByFCDate(Request $request)
    {
        $ideas = Idea::whereHas('academic', function ($query) {
                                    $query->where('final_closure_date', '>', now()->toDateString());
                                })->paginate(5);

        return view('idea.idea-list-fcdate',compact('ideas'));
    }

    public function downloadZip()
    {
        $zip = new ZipArchive;
   
        $fileName = 'myfile.zip';
   
        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE)
        {
            $ideas = Idea::take(2)->get();
   
            foreach ($ideas as $key => $idea) {
                $relativeNameInZipFile = basename($key+1);
                $path = \Storage::url($idea->document_url);
                $url = asset($path);
                $file = file_get_contents($url);
                // dd($file);
                $zip->addFile($file, $relativeNameInZipFile);
            }
             
            $zip->close();
        }
    
        return response()->download(public_path($fileName));
    }
}
