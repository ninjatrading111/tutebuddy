<?php

/**
 * This controller is for All of actions for Frontend
 */

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\FileUploadTrait;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

use App\Models\Quiz;
use App\Models\Question;
use App\Models\QuizResults;
use App\Models\QuizResultAnswers;
use App\Models\ChapterStudent;
use App\Models\Test;
use App\Models\TestResult;
use App\Models\Assignment;
use App\Models\AssignmentResult;

class StudentController extends Controller
{
    use FileUploadTrait;

    /**
     * Show a Assignment for Student
     */
    public function startAssignment($lesson_slug, $assignment_id)
    {
        $assignment = Assignment::find($assignment_id);
        return view('frontend.assignment.start', compact('assignment'));
    }

    public function saveAssignment(Request $request)
    {
        $data = $request->all();

        // Find Assignment Result
        $assignment = Assignment::find($request->assignment_id);
        $result = $assignment->result;
        $data['submit_date'] = Carbon::now()->format('Y-m-d H:i:s');

        if(!$result) {
            // Attachment URL
            if(!empty($request->doc_file)) {
                $file = $request->file('doc_file');
                $file_url = $this->saveFile($file);
                $data['attachment_url'] = $file_url;
            }
            $data['user_id'] = auth()->user()->id;
            
            AssignmentResult::create($data);
        } else {

            // Attachment URL
            if(!empty($request->doc_file)) {
                $file = $request->file('doc_file');

                // Delete existing file
                if (File::exists(public_path('/storage/attachments/' . $result->attachment_url))) {
                    File::delete(public_path('/storage/attachments/' . $result->attachment_url));
                }

                $file_url = $this->saveFile($file);
                $result->attachment_url = $file_url;
            }

            $result->content = $request->content;
            $result->submit_date = $data['submit_date'];
            $result->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Successfully Saved',
        ]);
    }

    /**
     * Show Assignment Result
     */
    public function assignmentResult($lesson_slug, $assignment_id)
    {
        $assignment = Assignment::find($assignment_id);
        return view('frontend.assignment.result', compact('assignment'));
    }

    /**
     * Show Quiz For Student
     */
    public function startQuiz($lesson_slug, $quiz_id)
    {
        $quiz = Quiz::find($quiz_id);
        $status = 'stop';
        if($quiz->result) {
            return redirect()->route('student.quiz.result', [$lesson_slug, $quiz->id]);
        } else {
            if($quiz->type == 2) {
                $start_time = timezone()->convertFromTimezone($quiz->start_date, $quiz->timezone);
                $now = timezone()->convertToLocal(Carbon::now());
                $diff = strtotime($start_time) - strtotime($now);
                if($diff > 0) {
                    $duration = $quiz->duration * 60;
                } else {
                    $duration = $quiz->duration * 60 + $diff;
                    if($duration > 0) {
                        $status = 'started';
                    }
                }
            } else {
                $duration = $quiz->duration * 60;
            }
            return view('frontend.quiz.start', compact('quiz', 'duration', 'status'));
        }
    }

    /**
     * Save Quiz
     */
    public function saveQuiz(Request $request)
    {
        $data = $request->all();

        $quiz_result = QuizResults::create([
            'quiz_id' => $data['quiz_id'],
            'user_id' => auth()->user()->id,
        ]);

        foreach($data as $key => $value) {
            
            if (strpos($key, 'option') !== false) {
                
                if(strpos($key, 'option_single_q') !== false) {
                    $question_id = (int)mb_substr($key, strlen('option_single_q'));
                    $option_id = (int)$value;

                    $this->completeQuestion($quiz_result->id, $question_id, $option_id);
                }

                if(strpos($key, 'option_multi_q') !== false) {
                    $question_id = (int)mb_substr($key, strlen('option_multi_q'));
                    $option_ids = $value;
                    foreach($option_ids as $option_id) {
                        $this->completeQuestion($quiz_result->id, $question_id, $option_id);
                    }
                }

                if(strpos($key, 'option_blank_q') !== false) {
                    $question_id = (int)mb_substr($key, strlen('option_multi_q'), strpos($key, '__option'));
                    $option_id = (int)mb_substr($key, strpos($key, '__option') + 8);
                    $this->completeQuestion($quiz_result->id, $question_id, $option_id, $value);
                }
            }
        }

        $quiz = Quiz::find($data['quiz_id']);
        $update_data = [
            'model_type' => Quiz::class,
            'model_id' => $data['quiz_id'],
            'user_id' => auth()->user()->id,
            'course_id' => $quiz->course->id,
            'lesson_id' => $quiz->lesson->id
        ];

        try {
            ChapterStudent::updateOrCreate($update_data, $update_data);
            $this->setQuizScore($quiz);
            return response()->json([
                'success' => true,
                'action' => 'complete'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    function setQuizScore($quiz)
    {
        // Quiz Score
        $corrects = [];
        $total_score = 0;
        $quiz_score = 0;
        $questions = $quiz->questions;

        foreach($questions as $question) {
            if(!empty($question->score)) {
                $total_score += $question->score;
            }
            $corrects = $question->options->where('correct', 1)->pluck('id')->toArray();
            $answers = $quiz->result->answers->where('question_id', $question->id)->pluck('option_id')->toArray();
            $result = array_diff($answers, $corrects);

            if(empty($result) && !empty($question->score)) {
                $quiz_score += $question->score;
            }
        }

        $score = floor(( $quiz_score / $total_score ) * 100);
        
        $quiz->result->quiz_score = $quiz_score;
        $quiz->result->quiz_result = $score;
        $quiz->result->save();
    }

    /**
     * Complete a Question
     */
    function completeQuestion($quiz_results_id, $question_id, $option_id, $answer = null)
    {
        // Find existing results and add to TestResult
        $answer_data = [
            'quiz_results_id' => $quiz_results_id,
            'question_id' => $question_id,
            'option_id' => $option_id
        ];

        if(!empty($answer)) {
            $answer_data['answer'] = $answer;
        }

        $answer = QuizResultAnswers::create($answer_data);

        return true;
    }

    /**
     * Show Quiz Result
     */
    public function quizResult($lesson_slug, $quiz_id)
    {
        $quiz = Quiz::find($quiz_id);
        return view('frontend.quiz.result', compact('quiz'));
    }

    /**
     * Show Test for Student
     */
    public function startTest($lesson_slug, $test_id)
    {
        $test = Test::find($test_id);
        if($test->result) {
            return redirect()->route('student.test.result', [$lesson_slug, $test->id]);
        } else {
            if($test->type == 2) {
                $start_time = timezone()->convertFromTimezone($test->start_date, $test->timezone, 'H:i:s');
                $now = timezone()->convertFromTimezone(Carbon::now(), $test->timezone, 'H:i:s');
                $diff = strtotime($start_time) - strtotime($now);
                $duration = $test->duration * 60 - $diff;
            } else {
                $duration = $test->duration * 60;
            }
            return view('frontend.test.start', compact('test', 'duration'));
        }
    }

    /**
     * Save a Test
     */
    public function saveTest(Request $request)
    {
        $data = $request->all();

        $test = Test::find($data['test_id']);
        $test_result = $test->result;

        if(!$test_result) {
            $result_data = [
                'test_id' => $data['test_id'],
                'content' => $data['content'],
                'due_time' => $data['due_time'],
                'user_id' => auth()->user()->id
            ];
    
            // Attachment URL
            if(!empty($request->doc_file)) {
                $file = $request->file('doc_file');
                $file_url = $this->saveFile($file);
                $result_data['attachment'] = $file_url;
            }
    
            $test_result = TestResult::create($result_data);
    
            return response()->json([
                'success' => true,
                'message' => 'Successfully Saved',
            ]);
        } else {
            // Attachment URL
            if(!empty($request->doc_file)) {
                $file = $request->file('doc_file');

                // Delete existing file
                if (File::exists(public_path('/storage/attachments/' . $test_result->attachment))) {
                    File::delete(public_path('/storage/attachments/' . $test_result->attachment));
                }

                $file_url = $this->saveFile($file);
                $test_result->attachment = $file_url;
            }

            $test_result->content = $data['content'];
            $test_result->save();

            return response()->json([
                'success' => true,
                'message' => 'Successfully Updated',
            ]);
        }
    }

    /**
     * Show Test Result
     */
    public function testResult($lesson_slug, $test_id)
    {
        $test = Test::find($test_id);
        return view('frontend.test.result', compact('test'));
    }
}
