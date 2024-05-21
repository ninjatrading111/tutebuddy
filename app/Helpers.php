<?php

use App\Helpers\General\Timezone;
use App\Helpers\General\HtmlHelper;

/*
 * Global helpers file with misc functions.
 */
if (!function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (!function_exists('gravatar')) {
    /**
     * Access the gravatar helper.
     */
    function gravatar()
    {
        return app('gravatar');
    }
}

if (!function_exists('timezone')) {
    /**
     * Access the timezone helper.
     */
    function timezone()
    {
        return resolve(Timezone::class);
    }
}

if (!function_exists('include_route_files')) {

    /**
     * Loops through a folder and requires all PHP files
     * Searches sub-directories as well.
     *
     * @param $folder
     */
    function include_route_files($folder)
    {
        try {
            $rdi = new recursiveDirectoryIterator($folder);
            $it = new recursiveIteratorIterator($rdi);

            while ($it->valid()) {
                if (!$it->isDot() && $it->isFile() && $it->isReadable() && $it->current()->getExtension() === 'php') {
                    require $it->key();
                }

                $it->next();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

if (!function_exists('home_route')) {

    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function home_route()
    {
        if (auth()->check()) {
            if (auth()->user()->can('view backend') && auth()->user()->isAdmin()) {
                return 'admin.dashboard';
            } else {
                return 'frontend.index';
            }
        }

        return 'frontend.index';
    }
}

if (!function_exists('style')) {

    /**
     * @param       $url
     * @param array $attributes
     * @param null $secure
     *
     * @return mixed
     */
    function style($url, $attributes = [], $secure = null)
    {
        return resolve(HtmlHelper::class)->style($url, $attributes, $secure);
    }
}

if (!function_exists('script')) {

    /**
     * @param       $url
     * @param array $attributes
     * @param null $secure
     *
     * @return mixed
     */
    function script($url, $attributes = [], $secure = null)
    {
        return resolve(HtmlHelper::class)->script($url, $attributes, $secure);
    }
}

if (!function_exists('form_cancel')) {

    /**
     * @param        $cancel_to
     * @param        $title
     * @param string $classes
     *
     * @return mixed
     */
    function form_cancel($cancel_to, $title, $classes = 'btn btn-danger ')
    {
        return resolve(HtmlHelper::class)->formCancel($cancel_to, $title, $classes);
    }
}

if (!function_exists('form_submit')) {

    /**
     * @param        $title
     * @param string $classes
     *
     * @return mixed
     */
    function form_submit($title, $classes = 'btn btn-success pull-right')
    {
        return resolve(HtmlHelper::class)->formSubmit($title, $classes);
    }
}

if (!function_exists('camelcase_to_word')) {

    /**
     * @param $str
     *
     * @return string
     */
    function camelcase_to_word($str)
    {
        return implode(' ', preg_split('/
          (?<=[a-z])
          (?=[A-Z])
        | (?<=[A-Z])
          (?=[A-Z][a-z])
        /x', $str));
    }
}

if (!function_exists('contact_data')) {

    /**
     * @param $str
     *
     * @return array
     */
    function contact_data($str)
    {
        $newElements = [];
        $elements = json_decode($str);
        foreach ($elements as $key => $item) {
            $newElements[$item->name] = ['value' => $item->value, 'status' => $item->status];
        }
        return $newElements;
    }
}

if (!function_exists('section_filter')) {

    /**
     * @param $str
     * Filter according to type selected.
     * 1 = Popular Categories
     * 2 = Featured Course
     * 3 = Trending Courses
     * 4 = Popular Courses
     * 5 = Custom Links
     * @return array
     */
    function section_filter($section)
    {
        $type = $section->type;
        $section_data = "";
        $section_title = "";
        $content = [];

        if ($type == 1) {
            $section_content = \App\Models\Category::has('courses', '>', 7)
                ->where('status', '=', 1)->get()->take(6);
            $section_title = trans('labels.frontend.footer.popular_categories');
            foreach ($section_content as $item) {
                $single_item = [
                    'label' => $item->name,
                    'link' => route('courses.category', ['category' => $item->slug])
                ];
                $content[] = $single_item;
            }
        } else if ($type == 2) {
            $section_content = \App\Models\Course::where('featured', '=', 1)
                ->has('category')
                ->where('published', '=', 1)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
            $section_title = trans('labels.frontend.footer.featured_courses');
            foreach ($section_content as $item) {
                $single_item = [
                    'label' => $item->title,
                    'link' => route('courses.show', [$item->slug])
                ];
                $content[] = $single_item;
            }

        } else if ($type == 3) {
            $section_content = \App\Models\Course::where('trending', '=', 1)
                ->has('category')
                ->where('published', '=', 1)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
            $section_title = trans('labels.frontend.footer.trending_courses');
            foreach ($section_content as $item) {
                $single_item = [
                    'label' => $item->title,
                    'link' => route('courses.show', [$item->slug])
                ];
                $content[] = $single_item;
            }

        } else if ($type == 4) {
            $section_content = \App\Models\Course::where('popular', '=', 1)
                ->has('category')
                ->where('published', '=', 1)
                ->orderBy('created_at', 'desc')
                ->take(6)
                ->get();
            $section_title = trans('labels.frontend.footer.popular_courses');
            foreach ($section_content as $item) {
                $single_item = [
                    'label' => $item->title,
                    'link' => route('courses.show', [$item->slug])
                ];
                $content[] = $single_item;
            }

        } else if ($type == 5) {
            $section_title = trans('labels.frontend.footer.useful_links');
            $section_content = $section->links;
            foreach ($section_content as $item) {
                $single_item = [
                    'label' => $item->label,
                    'link' => $item->link
                ];
                $content[] = $single_item;
            }
        }

        return ['section_content' => $content, 'section_title' => $section_title];
    }
}


if (!function_exists('generateInvoice')) {

    function generateInvoice($order)
    {
        $invoice = new \App\Http\Controllers\Traits\InvoiceGenerator();
        $invoice->number($order->id);

        foreach ($order->items as $item) {
            $title = $item->item->title;
            $price = $item->item->price;
            $qty = 1;
            $id = 'prod-'.$item->item->id;
            $invoice->addItem($title, $price, $qty, $id);
        }
//        $invoice->number($order->id);
        $total = $order->items->sum('price');
        $coupon = \App\Models\Coupon::find($order->coupon_id);
        if($coupon != null){
            $discount =  $order->items->sum('price') * $coupon->amount/100;
            $invoice->addDiscountData($discount);
            $total = $total - $discount;
        }
        $taxes = \App\Models\Tax::where('status','=',1)->get();
        $rateSum = \App\Models\Tax::where('status','=',1)->sum('rate');
        if($taxes != null){
            $taxData = [];
            foreach ($taxes as $tax){

                $taxData [] = ['name'=>$tax->name,'amount' => $total * $tax->rate/100];
            }
            $invoice->addTaxData($taxData);
            $total =  $total + ($total * $rateSum/100);
        }
        $invoice->addTotal($total);
        $user = \App\Models\Auth\User::find($order->user_id);

        $invoice->customer([
                'name' => $user->full_name,
                'id' => $user->id,
                'email' => $user->email
            ])
            ->save('public/invoices/invoice-'.$order->id.'.pdf');
//                ->download('invoice-'.$order->id.'.pdf');
//                ->show('invoice-'.$order->id.'.pdf');

        $invoiceEntry = \App\Models\Invoice::where('order_id','=',$order->id)->first();
        if($invoiceEntry == ""){
            $invoiceEntry = new \App\Models\Invoice();
            $invoiceEntry->user_id = $order->user_id;
            $invoiceEntry->order_id = $order->id;
            $invoiceEntry->url = 'invoice-'.$order->id.'.pdf';
            $invoiceEntry->save();
        }
    }
}

if (!function_exists('trashUrl')) {

    /**
     * @param $str
     *
     * @return array
     */
    function trashUrl($request)
    {
        $currentQueries = $request->query();

//Declare new queries you want to append to string:
        $newQueries = ['show_deleted' => 1];

//Merge together current and new query strings:
        $allQueries = array_merge($currentQueries, $newQueries);

//Generate the URL with all the queries:
        return $request->fullUrlWithQuery($allQueries);

    }
}

if (!function_exists('getCurrency')) {

    /**
     * @param $str
     *
     * @return array
     */
    function getCurrency($short_code)
    {
        $currencies = config('currencies');
        $currency = "";
            foreach ($currencies as $key => $val) {
                if ($val['short_code'] == $short_code) {
                    $currency = $val;
                }
            }
       return $currency;
    }
}

if (!function_exists('menuList')) {


    function menuList($array)
    {
        $temp_array = array();
        foreach ($array as $item) {
            if ($item->getsons($item->id)->except($item->id)) {
                $item->subs = menuList($item->getsons($item->id)->except($item->id)); // here is the recursion
                $temp_array[] = $item;
            }
        }
        return $temp_array;
    }
}

if (!function_exists('str_random')) {

    function str_random($length = 16) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('live_schedule')) {

    function live_schedule($schedule) {

        $now = Carbon\Carbon::now($schedule->timezone);

        $data = [
            'status' => false,
            'today' => false,
            'result' => 0, // 0: not finished, 1: finished
            'diff' => 0,
            'timestr' => ''
        ];

        if(Carbon\Carbon::parse($schedule->date)->dayOfWeek == Carbon\Carbon::now($schedule->timezone)->dayOfWeek) {

            $data['today'] = true;
            $start = Carbon\Carbon::parse($schedule->start_time, $schedule->timezone);
            $end = Carbon\Carbon::parse($schedule->end_time, $schedule->timezone);
            $available_start = Carbon\Carbon::parse($schedule->start_time, $schedule->timezone)->subMinutes(10);

            if($now > $available_start && $now < $end) {
                $data['status'] = true;
            }

            if($now > $end) {
                $data['result'] = 1;
            } else {
                $data['diff'] = $now->diffInSeconds($available_start);
                $hours = $now->diffInHours($now->copy()->addSeconds($data['diff']));
                $minutes = $now->diffInMinutes($now->copy()->addSeconds($data['diff'])->subHours($hours));
                $seconds = $now->diffInSeconds($now->copy()->addSeconds($data['diff'])->subHours($hours)->subMinutes($minutes));

                $hours = $hours < 10 ? '0' . $hours : $hours;
                $minutes = $minutes < 10 ? '0' . $minutes : $minutes;
                $seconds = $seconds < 10 ? '0' . $seconds : $seconds;

                // $data['timestr'] = Carbon\CarbonInterval::seconds($data['diff'])->cascade()->forHumans();
                $data['timestr'] = 'Start In ' . $hours . ':' . $minutes . ':' . $seconds;
            }
        }

        return $data;
    }
}

if (!function_exists('is_mobile')) {
    function is_mobile() {
        
        $useragent=$_SERVER['HTTP_USER_AGENT'];

        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) {
            return true;
        } else {
            return false;
        }
    }
}

if (!function_exists('get_country_list')) {
    function get_country_list() {
        return [
            "Afghanistan","Albania","Algeria","Andorra","Angola","Anguilla","Antigua &amp; Barbuda","Argentina","Armenia",
            "Aruba","Australia","Austria","Azerbaijan","Bahamas","Bahrain","Bangladesh","Barbados","Belarus","Belgium","Belize",
            "Benin","Bermuda","Bhutan","Bolivia","Bosnia &amp; Herzegovina","Botswana","Brazil","British Virgin Islands","Brunei",
            "Bulgaria","Burkina Faso","Burundi","Cambodia","Cameroon","Cape Verde","Cayman Islands","Chad","Chile","China",
            "Colombia","Congo","Cook Islands","Costa Rica","Cote D Ivoire","Croatia","Cruise Ship","Cuba","Cyprus","Czech Republic",
            "Denmark","Djibouti","Dominica","Dominican Republic","Ecuador","Egypt","El Salvador","Equatorial Guinea","Estonia",
            "Ethiopia","Falkland Islands","Faroe Islands","Fiji","Finland","France","French Polynesia","French West Indies",
            "Gabon","Gambia","Georgia","Germany","Ghana","Gibraltar","Greece","Greenland","Grenada","Guam","Guatemala","Guernsey",
            "Guinea","Guinea Bissau","Guyana","Haiti","Honduras","Hong Kong","Hungary","Iceland","India","Indonesia","Iran",
            "Iraq","Ireland","Isle of Man","Israel","Italy","Jamaica","Japan","Jersey","Jordan","Kazakhstan","Kenya","Kuwait",
            "Kyrgyz Republic","Laos","Latvia","Lebanon","Lesotho","Liberia","Libya","Liechtenstein","Lithuania","Luxembourg",
            "Macau","Macedonia","Madagascar","Malawi","Malaysia","Maldives","Mali","Malta","Mauritania","Mauritius","Mexico",
            "Moldova","Monaco","Mongolia","Montenegro","Montserrat","Morocco","Mozambique","Namibia","Nepal","Netherlands",
            "Netherlands Antilles","New Caledonia","New Zealand","Nicaragua","Niger","Nigeria","Norway","Oman","Pakistan",
            "Palestine","Panama","Papua New Guinea","Paraguay","Peru","Philippines","Poland","Portugal","Puerto Rico",
            "Qatar","Reunion","Romania","Russia","Rwanda","Saint Pierre &amp; Miquelon","Samoa","San Marino","Satellite",
            "Saudi Arabia","Senegal","Serbia","Seychelles","Sierra Leone","Singapore","Slovakia","Slovenia","South Africa",
            "South Korea","Spain","Sri Lanka","St Kitts &amp; Nevis","St Lucia","St Vincent","St. Lucia","Sudan","Suriname",
            "Swaziland","Sweden","Switzerland","Syria","Taiwan","Tajikistan","Tanzania","Thailand","Timor L'Este","Togo",
            "Tonga","Trinidad &amp; Tobago","Tunisia","Turkey","Turkmenistan","Turks &amp; Caicos","Uganda","Ukraine",
            "United Arab Emirates","United Kingdom", "United States", "Uruguay","Uzbekistan","Venezuela","Vietnam",
            "Virgin Islands (US)", "Yemen","Zambia","Zimbabwe"];
    }
}