<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;

use App\Services\ShortcodeService;

class QueueMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $data;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(ShortcodeService $shortcodeService)
    {
        $content = $this->getContent($this->data, $shortcodeService);
        return $this->subject($this->subject)->view('emails.sendEmail', compact('content'));
    }

    /**
     * Get content
     */
    function getContent($data, $shortcodeService)
    {
        $template_type = $data['template_type'];
        $template = EmailTemplate::where('slug', $template_type)->first();
        $this->subject = $template->subject;
        
        if(!empty($this->data['mail_type']) && $this->data['mail_type'] == 'test') {
            $html = $this->getHeader() . $template->html_content . $this->getFooter();
        } else {
            $html_content = $shortcodeService->replace($data, $template->html_content);
            $html = $this->getHeader() . $html_content . $this->getFooter();
        }
        
        return $html;
    }

    function getHeader()
    {
        $header = EmailTemplate::where('slug', 'header')->first();
        $header_view = view('emails.parts.header', ['header_html' => $header->html_content])->render();
        return $header_view;
    }

    function getFooter()
    {
        $footer = EmailTemplate::where('slug', 'footer')->first();
        $footer_view = view('emails.parts.footer', ['footer_html' => $footer->html_content])->render();
        return $footer_view;
    }
}
