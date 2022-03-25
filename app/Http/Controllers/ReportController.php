<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Idea;
use App\Models\Comment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function ideaPerDepartment(Request $request)
    {
        $departments = Department::withCount('idea')->paginate(10);

        if ($request->btn == 'export') {
            // $departments = Department::withCount('idea')->get();

            $file_name = now()->toDateString().".xlsx";

            Department::withCount('idea')->chunk($departments->count(),function($departments) use ($file_name){

                $list[0] = "No,Department Code,Department Name,Idea Count";

                foreach ($departments as $key => $department) {
                    
                $list[$key + 1] = ($key +1).","."{$department->code}, {$department->description}, {$department->idea_count}";
                }

                $this->export($list, $file_name);

            });

            return response()->download(public_path('files/'.$file_name));
        }

        return view('report.idea-per-deparment',compact('departments'));
    }

    public function ideaWithoutComment(Request $request)
    {
        $ideas = Idea::doesnthave('comments')->paginate(10);

        if ($request->btn == 'export') {

            $file_name = now()->toDateString().".xlsx";

            Idea::doesnthave('comments')->chunk($ideas->count(),function($ideas) use ($file_name){

                $list[0] = "No,Title,Description,View Count,Created By";

                foreach ($ideas as $key => $idea) {
                    
                $list[$key + 1] = ($key +1).","."{$idea->title}, {$idea->description}, {$idea->view_count}, {$idea->createdByUser()}";
                }

                $this->export($list, $file_name);

            });

            return response()->download(public_path('files/'.$file_name));
        }

        return view('report.idea-without-comment',compact('ideas'));
    }

    public function anonymousIdea(Request $request)
    {
        $ideas = Idea::where('annonymous',1)->paginate(10);

        if ($request->btn == 'export') {

            $file_name = now()->toDateString().".xlsx";

            Idea::where('annonymous',1)->chunk($ideas->count(),function($ideas) use ($file_name){

                $list[0] = "No,Title,Description,View Count,Created By";

                foreach ($ideas as $key => $idea) {
                    
                $list[$key + 1] = ($key +1).","."{$idea->title}, {$idea->description}, {$idea->view_count}, {$idea->createdByUser()}";
                }

                $this->export($list, $file_name);

            });

            return response()->download(public_path('files/'.$file_name));
        }

        return view('report.anonymous-idea',compact('ideas'));
    }

    public function anonymousComment(Request $request)
    {
        $comments = Comment::where('annonymous',1)->paginate(10);

        if ($request->btn == 'export') {

            $file_name = now()->toDateString().".xlsx";

            Comment::where('annonymous',1)->chunk($comments->count(),function($comments) use ($file_name){

                $list[0] = "No,Commentted By,Description";

                foreach ($comments as $key => $comment) {
                    
                $list[$key + 1] = ($key +1).","."{$comment->user->full_name}, {$comment->description}";
                }

                $this->export($list, $file_name);

            });

            return response()->download(public_path('files/'.$file_name));
        }

        return view('report.anonymous-comment',compact('comments'));
    }

    public function export($list, $file_name)
    {
        $csvFile = time().".csv";

        $file = fopen(public_path('files/' . $file_name), 'w');

        foreach ($list as $line) {
            fputcsv($file, explode(',', $line));
        }

        fclose($file);
    }
}
