<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\EmailTemplate;
use Mail;
use App\Mail\SendMail;
use App\Jobs\SendEmail;

use App\Http\Controllers\Traits\FileUploadTrait;

class EmailtemplateController extends Controller
{
    use FileUploadTrait;
    
    public function index()
    {
        return view('backend.templates.index');
    }

    public function getListByAjax()
    {
        $templates = EmailTemplate::all();
        $data = [];
        foreach($templates as $item) {
            $temp = [];
            $temp['index'] = '';

            $bg_color = ($item->type == 0) ? 'bg-accent' : 'bg-primary';

            $temp['name'] = '<div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded '. $bg_color .' text-white">'
                                        . mb_substr($item->name, 0, 2) .
                                    '</span>
                                </div>
                                <div class="media-body">
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-project">
                                            <strong>' . $item->name . '</strong></small>
                                        <small class="text-muted">'. $item->slug .'</small>
                                    </div>
                                </div>
                            </div>';
            $temp['subject'] = '<strong>'. $item->subject .'</strong>';
            
            if($item->published == 1) {
                $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Published</small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                </div>';
            } else {
                $temp['status'] = '<div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Unpublished</small>
                                    <span class="indicator-line rounded bg-warning"></span>
                                </div>';
            }

            if($item->type == 0) {
                $edit_route = route('admin.mailedits.template_edit', $item->id);
                $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
                $temp['action'] = $btn_edit . '&nbsp;';
            } else {
                $edit_route = route('admin.mailedits.edit', $item->id);
                $delete_route = route('admin.mailedits.destroy', $item->id);
                $btn_edit = view('backend.buttons.edit', ['edit_route' => $edit_route]);
                $btn_delete = view('backend.buttons.delete', ['delete_route' => $delete_route]);
                $temp['action'] = $btn_edit . '&nbsp;' . $btn_delete;
            }
            array_push($data, $temp);
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Create a new discussion
     */
    public function create()
    {
        $header = EmailTemplate::where('slug', 'header')->first();
        $footer = EmailTemplate::where('slug', 'footer')->first();
        return view('backend.templates.create', compact('header', 'footer'));
    }

    /**
     * Store a new discussion
     */
    public function store(Request $request)
    {
        $data = [
            'name' => config('mail.email_events')[$request->name],
            'slug' => $request->name,
            'subject' => $request->subject,
            'content' => $request->content,
            'html_content' => $request->html_content,
            'type' => 1
        ];
        $template = EmailTemplate::create($data);
        return redirect()->route('admin.mailedits.edit', $template->id);
    }

    public function edit($id)
    {
        $header = EmailTemplate::where('slug', 'header')->first();
        $footer = EmailTemplate::where('slug', 'footer')->first();
        $template = EmailTemplate::find($id);
        return view('backend.templates.edit', compact('template', 'header', 'footer'));
    }

    public function editTemplate($id)
    {
        $template = EmailTemplate::find($id);
        return view('backend.templates.' . $template->slug, compact('template'));
    }

    public function update(Request $request, $id)
    {
        $template = EmailTemplate::find($id);

        $template->html_content = $request->html_content;

        if($request->template_type == 'body') {
            $template->name = config('mail.email_events')[$request->name];
            $template->slug = $request->name;
            $template->subject = $request->subject;
        }

        if($template->slug == 'header') {
            if(!empty($request->logo)) {
                $image = $request->file('logo');
                $logo_image_url = $this->saveImage($image, 'upload', true);
                $template->content = $logo_image_url;
            }
        } else {
            $template->content = $request->content;
        }

        $template->save();

        return response()->json([
            'success' => true
        ]);

    }

    public function sendTestEmail(Request $request)
    {
        $data = [
            'template_type' => $request->template_type,
            'mail_type' => 'test',
            'mail_data' => [
                'email' => $request->email
            ]
        ];

        // for($i = 0; $i < 30; $i++) {
        //     SendEmail::dispatch($data)->onQueue('emails');
        // }

        // return response()->json([
        //     'success' => true
        // ]);

        try {
            Mail::to($data['mail_data']['email'])->send(new SendMail($data));

            return response()->json([
                'success' => true
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            EmailTemplate::find($id)->delete();

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
}
