<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Question;
use App\Models\Test;
use App\Models\Quiz;
use App\Models\QuestionGroup;
use App\Models\QuestionOption;

use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class QuestionController extends Controller
{
    use FileUploadTrait;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List questions
     */
    public function index() {
        //
        $courses = Course::all();
        $first_course = $courses->first();
        $tests = $first_course->tests;

        return view('backend.question.index', compact('courses', 'tests'));
    }

    public function getList($course_id, $test_id) {

        $data = [];

        if($test_id != 0) {
            $test = Test::find($test_id);
            $questions = $test->questions;
            $data = $this->getArrayData($questions);

        } else {

            if($course_id != 0) {
                $course = Course::find($course_id);
                $tests = $course->tests;

                $all_questions = [];

                foreach($tests as $test) {
                    $questions = $test->questions;
                    if($questions->count() > 0) {
                        foreach($questions as $question) {
                            array_push($all_questions, $question);
                        }
                    }
                }

                $data = $this->getArrayData($all_questions);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Create a Question
     */
    public function store(Request $request) {

        $data = $request->all();

        if(!isset($data['score'])) {
            $data['score'] = 1;
        }

        if(!isset($data['type'])) {
            $data['type'] = 0;
        }

        $question_data = [
            'question' => $data['question'],
            'model_id' => $data['model_id'],
            'score' => $data['score'],
            'type' => $data['type'],
            'user_id' => auth()->user()->id
        ];

        switch($data['model_type']) {
            case 'test':
                $question_data['model_type'] = Test::class;
            break;

            case 'quiz':
                $question_data['model_type'] = Quiz::class;
                $question_data['group_id'] = $data['group_id'];
            break;

            case 'assignment':
                $question_data['model_type'] = Assignment::class;
            break;

            default:
                $question_data['model_type'] = Quiz::class;
        }

        // Question image
        if(!empty($data['image'])) {
            $image = $request->file('image');
            $image_url = $this->saveImage($image, 'upload', true);
            $question_data['image'] = $image_url;
        }

        // Attachment
        if(!empty($data['attachment'])) {
            $file = $request->file('attachment');
            $attachment_url = $this->saveFile($file);
            $question_data['attachment'] = $attachment_url;
        }

        try {

            $question = Question::create($question_data);
            $question_count = Question::where('model_id', $data['model_id'])->where('model_type', $data['model_type'])->count();

            if($question_data['model_type'] == Test::class) {
                $html = $this->getTestHtml($question);
            }

            if($question_data['model_type'] == Quiz::class) {

                // update section score
                $scores = $this->setTotalMarks($question_data['model_id'], $question_data['group_id']);

                $html = $this->storeOptions($data, $question);

                return response()->json([
                    'success' => true,
                    'question' => $question,
                    'count' => $question_count,
                    'html' => $html,
                    'section_score' => $scores['section_score']
                ]);

            } else {

                return response()->json([
                    'success' => true,
                    'question' => $question,
                    'count' => $question_count,
                    'html' => $html
                ]);
            }
            
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * Edit Question
     */
    public function edit($id) {

        $courses = Course::all();
        $first_course = $courses->first();
        $tests = $first_course->tests;
        $question = Question::find($id);
        return view('backend.question.edit', compact('question', 'courses', 'tests'));
    }

    public function getQuestion($id)
    {
        $question = Question::find($id);
        $html = '';
        $str_ids = ['option_s', 'option_m', 'option_f'];
        $str_names = ['option_single', 'option_multi[]', 'option_fill[]'];
        $str_css = ['custom-radio', 'custom-checkbox', 'custom-checkbox'];

        foreach($question->options as $option) {

            $checked_str = ($option->correct == 1) ? 'checked' : '';

            $html .= '<div class="row mb-8pt">
                        <div class="col-10 form-inline">
                            <div class="custom-control '. $str_css[$question->type] .'">
                                <input id="new_'. $str_ids[$question->type] . $option->id . '_q' . $question->id . '" name="'. $str_names[$question->type] . '" type="radio" class="custom-control-input" '. $checked_str .' value="0">
                                <label for="new_'. $str_ids[$question->type] . $option->id . '_q' . $question->id .'" class="custom-control-label">&nbsp;</label>
                            </div>
                            <input type="text" name="option_text[]" class="form-control" style="width: 90%" value="'. $option->option_text .'" >
                        </div>
                        <div class="col-2 text-right">
                            <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                        </div>
                    </div>';
        }

        return response()->json([
            'success' => true,
            'question' => $question,
            'html' => $html
        ]);
    }

    /**
     * Update a Question
     */
    public function update(Request $request, $id) {

        $update_data = [
            'question' => $request->question,
            'score' => $request->score,
            'model_id' => $request->model_id,
            'type' => $request->type
        ];

        if(!isset($request->score)) {
            $update_data['score'] = 1;
        }

        if(!isset($request->type)) {
            $update_data['type'] = 0;
        }

        switch($request->model_type) {
            case 'test':
                $update_data['model_type'] = Test::class;
            break;

            case 'quiz':
                $update_data['model_type'] = Quiz::class;
            break;

            case 'assignment':
                $update_data['model_type'] = Assignment::class;
            break;

            default:
            $update_data['model_type'] = Quiz::class;
        }
        
        // Question image
        if(!empty($request->image)) {
            $image = $request->file('image');

            // Delete existing img file
            if (File::exists(public_path('/storage/uploads/' . Question::find($id)->image))) {
                File::delete(public_path('/storage/uploads/' . Question::find($id)->image));
                File::delete(public_path('/storage/uploads/thumb/' . Question::find($id)->image));
            }

            $image_url = $this->saveImage($image, 'upload', true);
            $update_data['image'] = $image_url;
        }

        // Attachment
        if(!empty($request->attachment)) {
            $file = $request->file('attachment');

            // Delete existing img file
            if (File::exists(public_path('/storage/uploads/' . Question::find($id)->attachment))) {
                File::delete(public_path('/storage/uploads/' . Question::find($id)->attachment));
                File::delete(public_path('/storage/uploads/thumb/' . Question::find($id)->attachment));
            }

            $attachment_url = $this->saveFile($file);
            $update_data['attachment'] = $attachment_url;
        }

        try {
            Question::find($id)->update($update_data);
            $question = Question::find($id);

            if($update_data['model_type'] == Test::class) {
                $html = $this->getTestHtml($question);
            }

            if($update_data['model_type'] == Quiz::class) {
                $data = $request->all();

                // update section score
                $scores = $this->setTotalMarks($update_data['model_id'], $question->group_id);
                $html = $this->storeOptions($data, $question);

                return response()->json([
                    'success' => true,
                    'question' => $question,
                    'html' => $html,
                    'section_score' => $scores['section_score']
                ]);

            } else {
                return response()->json([
                    'success' => true,
                    'question' => $question,
                    'html' => $html
                ]);
            }
            
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * Delete a Question
     */
    public function delete($id) {

        try {

            // update question group
            $question = Question::find($id);
            $model_id = $question->model_id;
            $group_id = $question->group_id;
            $question->delete();

            $scores = $this->setTotalMarks($model_id, $group_id);

            return response()->json([
                'success' => true,
                'action' => 'destroy',
                'section_score' => $scores['section_score']
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Restore Question
     */
    public function restore($id) {

        //
    }

    /**
     * Add Question Section
     */
    public function addSection(Request $request)
    {

        if($request->action == 'new') {
            // $section = QuestionGroup::create([
            //     'title' => $request->section_title,
            //     'score' => $request->section_marks,
            //     'model_id' => $request->model_id,
            //     'model_type' => Quiz::class
            // ]);

            $section = QuestionGroup::create([
                'title' => $request->section_title,
                'score' => 0, // Init value of section
                'model_id' => $request->model_id,
                'model_type' => Quiz::class
            ]);
    
            $html = $this->getSectionHtml($section);
    
            return response()->json([
                'success' => true,
                'section' => $section,
                'html' => $html
            ]);
        } else {
            $section = QuestionGroup::find($request->group_id);
            $section->title = $request->section_title;
            $section->score = $request->section_marks;
            $section->save();

            return response()->json([
                'success' => true,
                'title' => $request->section_title,
                'score' => $request->section_marks
            ]);
        }
        
    }

    function storeOptions($data, $question)
    {
        $options = $data['option_text'];
        
        if($question->type == 0) {
            $answer_type = 'Single Answer';
        }

        switch($question->type) {
            case 0:
                $answer_type = 'Single Answer';
            break;

            case 1:
                $answer_type = 'Multi Answer';
            break;

            case 2:
                $answer_type = 'Fill in Blank';
            break;

            default:
                $answer_type = 'Single Answer';
        }

        $question_count = Question::where('model_type', Quiz::class)->where('group_id', $question->group_id)->count();

        // Delete options if already exist
        foreach($question->options as $option)
        {
            $option->delete();
        }

        $edit_route = route('admin.getQuestionByAjax', $question->id);
        $update_route = route('admin.questions.update', $question->id);
        $delete_route = route('admin.questions.delete', $question->id);

        $html = '<li class="list-group-item d-flex quiz-item" data-id="'. $question->id .'">
                    <div class="flex d-flex flex-column">
                        <div class="card-title mb-16pt">' . $question_count . '. '. $question->question .'</div>
                        <div class="text-right">
                            <div class="chip chip-outline-secondary">'. $answer_type .'</div>
                            <div class="chip chip-outline-secondary">Score: '. $question->score .'</div>
                        </div>
                        <div class="options-wrap">
                        
                            <div class="form-group">
                                <div class="custom-controls-stacked">';
                                    foreach($options as $idx => $value) {
                                        $correct = 0;

                                        if(isset($data['option_single'])) {
                                            if($idx == (int)$data['option_single']) {
                                                $correct = 1;
                                            }
                                        } 

                                        if(isset($data['option_multi'])) {
                                            if(in_array($idx, $data['option_multi'])) {
                                                $correct = 1;
                                            }
                                        }

                                        if(isset($data['option_fill'])) {
                                            if(in_array($idx, $data['option_fill'])) {
                                                $correct = 1;
                                            }
                                        }
                                        
                                        $optionData = [
                                            'question_id' => $question->id,
                                            'option_text' => $value,
                                            'correct' => $correct
                                        ];
                                        
                                        $option = QuestionOption::create($optionData);
                                        $html .= $this->getOptionHtml($option, $idx);
                                    }
                                    $html .= '
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" data-caret="false" class="text-muted"><i
                                class="material-icons">more_horiz</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="'. $edit_route .'" data-update="'. $update_route .'" class="dropdown-item question-edit" group_id="'. $data['group_id'] .'">Edit Question</a>
                            <div class="dropdown-divider"></div>
                            <a href="'. $delete_route .'" class="dropdown-item text-danger question-delete" group_id="'. $data['group_id'] .'">Delete Question</a>
                        </div>
                    </div>
                </li>';

        return $html;
    }

    function getOptionHtml($option, $idx) {

        $option_count = QuestionOption::where('question_id', $option->question_id)->count();
        $checked_str = ($option->correct == 1) ? 'checked' : '';

        $option_type = $option->question->type;

        if($option_type == 0) {
            return '<div class="custom-control custom-radio mb-8pt">
                <input id="option_s'. $option->id .'_q'. $option->question_id .'" name="option_single_q'. $option->question_id .'" type="radio" class="custom-control-input" '. $checked_str .' value="'. $idx .'">
                <label for="option_s'. $option->id .'_q'. $option->question_id .'" class="custom-control-label">'. $option->option_text .'</label>
            </div>';
        }

        if($option_type == 1) {
            return '<div class="custom-control custom-checkbox mb-8pt">
                <input id="option_m'. $option->id .'_q'. $option->question_id .'" name="option_multi_q'. $option->question_id .'[]" type="checkbox" class="custom-control-input" '. $checked_str .'>
                <label for="option_m'. $option->id .'_q'. $option->question_id .'" class="custom-control-label">'. $option->option_text .'</label>
            </div>';
        }

        if($option_type == 2) {
            if($option->correct == 1) {
                return '<input type="text" class="form-control form-control-flush font-size-16pt text-70 border-bottom-1 inline pl-8pt" 
                        style="width: fit-content; display: inline; border-color: #333;" placeholder="Add Correct Word"
                        value="">';
            } else {
                return '<label class="text-70 font-size-16pt">'. $option->option_text .'</label>';
            }
        }
    }

    function getSectionHtml($section)
    {
        $count = QuestionGroup::where('model_id', $section->model_id)->where('model_type', Quiz::class)->count();

        return '<div class="group-wrap py-32pt mb-16pt border-bottom-1" group-id="'. $section->id .'">
                    <div class="d-flex align-items-center page-num-container">
                        <div class="page-num">'. $count .'</div>
                        <div class="flex">
                            <div class="d-flex">
                                <h4 class="flex mb-0">'. $section->title .'</h4>
                                <h5 class="badge badge-pill font-size-16pt badge-accent">'. $section->score .'</h4>
                            </div>
                        </div>
                        <button type="button" class="btn btn-outline-primary ml-16pt btn-edit" data-id="'. $section->id .'">Edit</button>
                        <button type="button" class="btn btn-outline-primary ml-16pt btn-question" data-id="'. $section->id .'">Add Quesion</button>
                    </div>
                </div>';
    }

    function getTestHtml($question) {

        $test = Test::find($question->model_id);
        $count = $test->questions->count();
        $update_route = route('admin.questions.update', $question->id);
        $delete_route = route('admin.questions.delete', $question->id);

        $doc = '';

        if(!empty($question->attachment)) {
            $ext = pathinfo($question->attachment, PATHINFO_EXTENSION);
            $src = ($ext == 'pdf') ? asset('/images/pdf.png') : asset('/images/docx.png');
            $doc = '<div class="form-group">
                        <label class="form-label">Document</label>
                        <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                            <div class="w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                                <img class="img-fluid rounded" src="'. $src .'" alt="document">
                            </div>
                            <div class="flex">
                                <a href="'. asset('/storage/attachments/' . $question->attachment) .'">
                                    <div class="form-label mb-4pt">'. $question->attachment .'</div>
                                    <p class="card-subtitle text-black-70">Click to See Attached Document.</p>
                                </a>
                            </div>
                        </div>
                    </div>';
        }

        return '<li class="list-group-item d-flex quiz-item" data-id="'. $question->id .'">
                    <div class="flex d-flex flex-column">
                        <div class="card-title mb-16pt">Question ' . $count . '</div>
                        <div class="card-subtitle text-70 paragraph-max mb-8pt tute-question">'. $question->question .'</div>
                        '. $doc .'
                        <input type="hidden" name="score" value="'. $question->score .'">
                    </div>

                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" data-caret="false" class="text-muted"><i
                                class="material-icons">more_horiz</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="'. $update_route .'" class="dropdown-item edit">Edit Question</a>
                            <div class="dropdown-divider"></div>
                            <a href="'. $delete_route .'" class="dropdown-item text-danger delete">Delete Question</a>
                        </div>
                    </div>
                </li>';
    }

    function getQuizHtml($question) {

        $quiz = Quiz::find($question->model_id);
        $count = $quiz->questions->count();
        $answer_str = ($question->type == 1) ? 'Single Answer' : 'Multi Answer';
        $edit_route = route('admin.questions.edit', $question->id);
        $delete_route = route('admin.questions.delete', $question->id);

        return '<li class="list-group-item d-flex quiz-item">
                    <div class="flex d-flex flex-column">
                        <div class="card-title mb-16pt">Question ' . $count . '</div>
                        <div class="card-subtitle text-70 paragraph-max mb-16pt" id="content_quiz_'. $question->id . '"></div>

                        <div class="work-area d-none">
                            <div id="editor_quiz_'. $question->id . '"></div>
                            <textarea id="quiz_'. $question->id . '" class="quiz-textarea">'. $question->question .'</textarea>
                        </div>
                        
                        <div class="text-right">
                            <div class="chip chip-outline-secondary">'. $answer_str .'</div>
                            <div class="chip chip-outline-secondary">Score: '. $question->score .'</div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <a href="#" data-toggle="dropdown" data-caret="false" class="text-muted"><i
                                class="material-icons">more_horiz</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="'. $edit_route .'" class="dropdown-item">Edit Question</a>
                            <div class="dropdown-divider"></div>
                            <a href="'. $delete_route .'" class="dropdown-item text-danger">Delete Question</a>
                        </div>
                    </div>
                </li>';
    }

    function getArrayData($questions) {

        $data = [];
        $i = 0;

        foreach($questions as $question) {

            $inserts = '';
            // Get question title
            foreach(json_decode($question->question) as $item) {
                $inserts .= $item->insert;
            }

            $title = (strlen($inserts) > 50) ? mb_substr($inserts, 0, 50) . '...' : $inserts;

            $i++;
            $temp = [];
            $temp['index'] = '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input js-check-selected-row" data-domfactory-upgraded="check-selected-row">
                        <label class="custom-control-label"><span class="text-hide">Check</span></label>
                    </div>';
            $temp['no'] = $i;
            $temp['title'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">'
                                        . mb_substr($title, 0, 2) .
                                    '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <p class="mb-0"><strong>' . $title . '</strong></p>
                                        <small class="text-50">Test Question</small>
                                    </div>
                                </div>
                            </div>';

            $temp['options'] = $question->options->count();

            $show_route = route('admin.questions.show', $question->id);
            $edit_route = route('admin.questions.edit', $question->id);
            $delete_route = route('admin.questions.destroy', $question->id);

            $btn_show = view('backend.buttons.show', ['show_route' => $show_route]);
            $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
            $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);

            if($question->trashed()) {
                $restore_route = route('admin.questions.restore', $question->id);
                $btn_delete = '<a href="'. $restore_route. '" class="btn btn-info btn-sm" data-action="restore" data-toggle="tooltip"
                    data-original-title="Recover"><i class="material-icons">restore_from_trash</i></a>';
            }

            $temp['action'] = $btn_show . '&nbsp;' . $btn_edit . '&nbsp;' . $btn_delete;
            $temp['more'] = '<a href="javascript:void(0)" class="text-50"><i class="material-icons">more_vert</i></a>';

            array_push($data, $temp);
        }

        return $data;
    }

    function setTotalMarks($model_id, $group_id = NULL) {

        $scores = [];

        if($group_id) {
            $question_group = QuestionGroup::find($group_id);
            $section_score = Question::where('model_id', $model_id)->where('group_id', $group_id)->sum('score');
            $question_group->score = $section_score;
            $question_group->save();
            $scores['section_score'] = $section_score;
        }
            
        $total_score = Question::where('model_id', $model_id)->sum('score');
        $quiz = Quiz::find($model_id);
        $quiz->score = $total_score;
        $quiz->save();

        $scores['total_score'] = $total_score;

        return $scores;
    }
}
