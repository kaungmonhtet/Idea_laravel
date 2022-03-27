<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Idea;
use App\Models\Comment;
use Illuminate\Http\Request;
use DB;

class ReportController extends Controller
{
    public function __construct ()
    {
        $this->header = array('Content-Type:application/vnd.ms-excel');
    }
    public function ideaPerDepartment(Request $request)
    {
        $departments = Department::withCount('idea')->paginate(10);
        // $departments = Department::where('id',2)->withCount('idea')->paginate(10);

        if ($request->btn == 'export') {

            $departments = Department::withCount('idea')->get();

            if ($departments->count() > 0) {
                $file_name = now()->toDateString().".xlsx";

                $list[0] = "No,Department Code,Department Name,Idea Count, Percentage";

                foreach ($departments as $key => $department) {

                    $percentage = ($department->idea_count / 100);

                    $list[$key + 1] = ($key +1).","."{$department->code}, {$department->description}, {$department->idea_count}, {$percentage}";
                }

                $this->export($list, $file_name);
                    
                return response()->download(public_path('files/'.$file_name), $file_name, $this->header);
            }
        }

        return view('report.idea-per-deparment',compact('departments'));
    }

    public function ideaWithoutComment(Request $request)
    {
        $ideas = Idea::doesnthave('comments')->paginate(10);

        if ($request->btn == 'export') {

            $ideas = Idea::doesnthave('comments')->get();

            if ($ideas->count() > 0) {
                $file_name = now()->toDateString().".xlsx";

                $list[0] = "No,Title,Description,View Count,Created By";

                foreach ($ideas as $key => $idea) {
                    
                $list[$key + 1] = ($key +1).","."{$idea->title}, {$idea->description}, {$idea->view_count}, {$idea->createdByUser()}";
                }

                $this->export($list, $file_name);

                return response()->download(public_path('files/'.$file_name), $file_name, $this->header);
            }
        }

        return view('report.idea-without-comment',compact('ideas'));
    }

    public function anonymousIdea(Request $request)
    {
        $ideas = Idea::where('annonymous',1)->paginate(10);

        if ($request->btn == 'export') {


            $ideas = Idea::where('annonymous',1)->get();

            if ($ideas->count() > 0) {
                $file_name = now()->toDateString().".xlsx";

                $list[0] = "No,Title,Description,View Count,Created By";

                foreach ($ideas as $key => $idea) {
                    
                $list[$key + 1] = ($key +1).","."{$idea->title}, {$idea->description}, {$idea->view_count}, {$idea->createdByUser()}";
                }

                $this->export($list, $file_name);


                return response()->download(public_path('files/'.$file_name), $file_name, $this->header);
            }
        }

        return view('report.anonymous-idea',compact('ideas'));
    }

    public function anonymousComment(Request $request)
    {
        $comments = Comment::where('annonymous',1)->paginate(10);

        if ($request->btn == 'export') {


            $comments = Comment::where('annonymous',1)->get();

            if ($comments->count() > 0) {
                $file_name = now()->toDateString().".xlsx";

                $list[0] = "No,Commentted By,Description";

                foreach ($comments as $key => $comment) {
                    
                $list[$key + 1] = ($key +1).","."{$comment->user->full_name}, {$comment->description}";
                }

                $this->export($list, $file_name);

                return response()->download(public_path('files/'.$file_name), $file_name,$this->header);
            }
        }

        return view('report.anonymous-comment',compact('comments'))->with('success','No Data');
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
