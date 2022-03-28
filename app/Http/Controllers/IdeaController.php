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
use App\Filters\IdeaFilter;
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
    public function index(IdeaFilter $filters)
    {
        $ideas = Idea::filter($filters)->paginate(5);
        // $count = DB::table('ideas')->join('comments',)

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
            return redirect()->route('ideas.create')->with('error', 'Idea can’t be submitted because Closure Date is expired ');
        }

        $data['created_by'] = Auth::id();
        $data['user_id'] = Auth::id();
        $data['department_id'] = Auth::user()->department_id;

        if ($request->document_url) {
            $ext = $request->document_url->getClientOriginalExtension();

            $name = time().Str::random(6).".".$ext;

            $request->document_url->move(public_path('document'), $name);
            $data['document_url'] = $name;
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
            $image_path = public_path("document/".$idea->document_url);
            File::delete($image_path);
            $ext = $request->document_url->getClientOriginalExtension();

            $name = time().Str::random(6).".".$ext;
            $request->document_url->move(public_path('document'), $name);
            $data['document_url'] = $name;
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
                                    $query->where('final_closure_date', '<', now()->toDateString());
                                })->paginate(5);

        return view('idea.idea-list-fcdate',compact('ideas'));
    }

    public function downloadZip()
    {
        $zip = new ZipArchive;
   
        $fileName = 'myfile.zip';
        $ideas = Idea::whereHas('academic', function ($query) {
                                $query->where('final_closure_date', '<', now()->toDateString());
                            })->get();

        if ($ideas->count() <= 0) {
            return redirect()->route('idea.closure',compact('ideas'));
        }

        if ($zip->open(public_path($fileName), ZipArchive::CREATE) === TRUE)
        {
            foreach ($ideas as $key => $idea) {
                $relativeNameInZipFile = basename($idea->document_url);
                $path = public_path('document');

                $files = File::files($path);

                $zip->addFile($files[$key], $relativeNameInZipFile);

            }
             
            $zip->close();
        }
    
        return response()->download(public_path($fileName))->deleteFileAfterSend();
    }
}
;