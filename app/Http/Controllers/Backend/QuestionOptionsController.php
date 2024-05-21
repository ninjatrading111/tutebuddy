<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QuestionOption;

class QuestionOptionsController extends Controller
{
    //
    public function store(Request $request) {

        $optionData = [
            'question_id' => $request->question_id,
            'option_text' => $request->option_text,
            'explanation' => $request->explanation,
            'correct' => $request->correct
        ];

        try {

            $option = QuestionOption::create($optionData);
            $option_html = $this->getOptionHtml($option);

            return response()->json([
                'success' => true,
                'option' => $option,
                'html' => $option_html
            ]);

        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
        }
    }

    /**
     * update Option
     */
    public function update(Request $request, $id) {

        $data = $request->all();
        unset($data['id']);

        try {
            QuestionOption::find($id)->update($data);

            return response()->json([
                'success' => true,
                'action' => 'update'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
        }

    }

    /** delete option */
    public function delete($id) {

        try {
            QuestionOption::find($id)->delete();

            return response()->json([
                'success' => true,
                'action' => 'destroy'
            ]);
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    function getOptionHtml($option) {

        $option_count = QuestionOption::where('question_id', $option->question_id)->count();
        $checked_str = ($option->correct == 1) ? 'checked' : '';

        return '<li class="list-group-item d-flex" data-option-id="'. $option->id .'">
                    <div class="flex d-flex flex-column">
                        <div class="card-title mb-16pt">Option '. $option_count .'</div>
                        <div class="page-separator"></div>
                        <div class="form-group mb-0">
                            <label class="form-label">Option Text*:</label>
                            <div class="card-subtitle text-70 paragraph-max mb-16pt">
                                <input type="text" class="form-control" value="'. $option->option_text .'">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Option Explain:</label>
                            <textarea class="form-control option-textarea" rows="3"
                                width="100">'. $option->explanation .'</textarea>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="chk_'. $option->id .'" '. $checked_str .'>
                                <label class="custom-control-label" for="chk_'. $option->id .'">
                                    Correct Answer
                                </label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="button" class="close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </li>';
    }
}
