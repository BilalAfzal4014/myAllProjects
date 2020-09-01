<?php

namespace App\Http\Controllers;

use App\NewsTemplate;
use Illuminate\Http\Request;

use App\Http\Requests;
use PhpParser\Node\Expr\Cast\Object_;
use Symfony\Component\HttpFoundation\Response;

class NewsFeedTemplate extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('superadmin');
    }

    public function index()
    {
        $newFeedTemplate = NewsTemplate::where('is_deleted', '=', '0')->get();
        return view('NewsFeedTemplate.index')->with(['newFeedTemplate' => $newFeedTemplate]);
    }

//    public function show($id)
//    {
//        return redirect('newsFeedTemplates');
//    }

    public function NewsFeedTemplateListing($id)
    {
        if ($id == "active") {
            $newFeedTemplate = NewsTemplate::where("is_active", 1)->get();

        } else {

            $newFeedTemplate = NewsTemplate::where("is_active", 0)->get();
        }
        $arrayTemp = [];
        foreach ($newFeedTemplate as $newFeedTemplates) {
            array_push($arrayTemp, [
                $newFeedTemplates->id,
                $newFeedTemplates->name,
                $newFeedTemplates->created_at->format('F d, Y h:ia'),
                view('NewsFeedTemplate.newFeedTemplateAjax.actionCol', ["newFeedTemplates" => $newFeedTemplates])->render()
            ]);
        }
        $arrayToReturn['data'] = $arrayTemp;
        return new Response(json_encode($arrayToReturn));
    }

    public function newsFeedTemplatesCreate()
    {
        $newsTemplate = array(
            'id' => '',
            'name' => '',
            'content' => ''
        );
        return view('NewsFeedTemplate.create')->with(['newsTemplate' =>(object)$newsTemplate]);
    }

    public function saveNewsFeedTemplate(Request $request)
    {
        $userid = $request->input('userid');
        $allinputs = $request->all();
//        dd($allinputs);
        if ($userid != '') {
            $updateRecord = NewsTemplate::where('id', $userid)->update([
                'name' => $request->input('name'),
                'content' => $request->input('content')
            ]);
            if ($updateRecord) {
                return redirect('/newsFeedTemplates')->with(['flash_message' => 'NewsFeed Template Updated']);
            } else {
                return redirect('/newsFeedTemplates/edit/' . $userid)->with(['flash_message' => 'Failed NewsFeed Updated']);
            }


        } else {
            $checkUser = NewsTemplate::where('name', '=', $request->input('name'))->get();
            if (count($checkUser) == 0) {
                $newsFeedmodel = new NewsTemplate();
                $newsFeedmodel->name = $request->input('name');
                $newsFeedmodel->company_id = '1';
                $newsFeedmodel->content = $request->input('content');
                $newsFeedmodel->is_active = '1';
                $newsFeedmodel->is_deleted = '0';
                $result = $newsFeedmodel->save();
                if ($result) {
                    return redirect('/newsFeedTemplates')
                        ->with('flash_message', 'NewFeed Template successfully added.');
                } else {
                    return redirect('newsFeedTemplates')->with(['flash_message' => 'This NewsFeed Not Added']);
                }
            } else {
                return redirect('newsFeedTemplates')->with(['flash_message' => 'This NewsFeed Template Name ALready Exist']);
            }
        }
    }

    public function newFeedTemplatesStatus($id, $status)
    {
        $userId = $id;
        $userObj = NewsTemplate::find($userId);
        $userObj->is_active = ($status == 1) ? 0 : 1;
        $userObj->save();
        return redirect('/newsFeedTemplates')->with(['flash_message'=>'NewFeed Template Status Updated']);

    }

    public function edit($id)
    {
        $newsTemplate = NewsTemplate::findOrFail($id);
        return view('NewsFeedTemplate.create')->with(['newsTemplate' => $newsTemplate]);
    }
}
