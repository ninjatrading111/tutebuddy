<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\Traits\FileUploadTrait;
use App\Models\Config;

class ConfigController extends Controller
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
     * Get Config Data
     */
    public function getGeneralSettings() {

        return view('backend.settings.general');
    }
    
    public function getTerms() {

        return view('backend.settings.term');
    }

    public function getCommissions() {

        return view('backend.settings.commission');
    }

    public function gateDetail() {

        return view('backend.settings.detail');
    }

    public function getPaygateways() {

        $btn_route=route('admin.settings.gateDetail');
        $btn_detail='<a href="'.$btn_route.'" class="btn btn-primary">Detail</a>';

        $gateways = [[
            'name' => 'Authorize.net',
            'status' => 'disabled',
        ]];
        return view('backend.settings.gateway', compact('gateways'));
    }
    public function getFeatures() {

        return view('backend.settings.feature');
    }

    public function saveGeneralSettings(Request $request) {

        if (($request->get('mail_provider') == 'sendgrid') && ($request->get('list_selection') == 2)) {
            if ($request->get('list_name') == "") {
                return back()->withErrors(['Please input list name']);
            }
            $apiKey = config('sendgrid_api_key');
            $sg = new \SendGrid($apiKey);
            try {
                $request_body = json_decode('{"name": "' . $request->get('list_name') . '"}');
                $response = $sg->client->contactdb()->lists()->post($request_body);
                if ($response->statusCode() != 201) {
                    return back()->withErrors(['Check name and try again']);
                }
                $response = json_decode($response->body());
                $sendgrid_list_id = Config::where('sendgrid_list_id')->first();
                $sendgrid_list_id->value = $response->id;
                $sendgrid_list_id->save();
            } catch (Exception $e) {
                \Log::info($e->getMessage());
            }

        }

        $requests = $this->saveLogos($request);

        if ($request->get('access_registration') == null) {
            $requests['access_registration'] = 0;
        }
        if (!$request->get('mailchimp_double_opt_in')) {
            $requests['mailchimp_double_opt_in'] = 0;
        }
        if ($request->get('access_users_change_email') == null) {
            $requests['access_users_change_email'] = 0;
        }
        if ($request->get('access_users_confirm_email') == null) {
            $requests['access_users_confirm_email'] = 0;
        }
        if ($request->get('access_captcha_registration') == null) {
            $requests['access_captcha_registration'] = 0;
        }
        if ($request->get('access_users_requires_approval') == null) {
            $requests['access_users_requires_approval'] = 0;
        }
        if ($request->get('services__stripe__active') == null) {
            $requests['services__stripe__active'] = 0;
        }
        if ($request->get('paypal__active') == null) {
            $requests['paypal__active'] = 0;
        }
        if ($request->get('payment_offline_active') == null) {
            $requests['payment_offline_active'] = 0;
        }
        if ($request->get('backup__status') == null) {
            $requests['backup__status'] = 0;
        }
        if ($request->get('access__captcha__registration') == null) {
            $requests['access__captcha__registration'] = 0;
        }
        if ($request->get('retest') == null) {
            $requests['retest'] = 0;
        }
        if ($request->get('lesson_timer') == null) {
            $requests['lesson_timer'] = 0;
        }
        if ($request->get('show_offers') == null) {
            $requests['show_offers'] = 0;
        }
        if ($request->get('onesignal_status') == null) {
            $requests['onesignal_status'] = 0;
        }

        foreach ($requests->all() as $key => $value) {
            if ($key != '_token') {
                $key = str_replace('__', '.', $key);
                $config = Config::firstOrCreate(['key' => $key]);
                if($value !== null) {
                    $config->value = $value;
                }
                $config->save();

                if($key === 'app.locale'){
                    Locale::where('short_name','!=',$value)->update(['is_default' => 0]);
                    $locale = Locale::where('short_name','=',$value)->first();
                    $locale->is_default = 1;
                    $locale->save();
                }
            }
        }

        return response()->json([
            'success' => true,
            'action' => 'update'
        ]);
    }
}
