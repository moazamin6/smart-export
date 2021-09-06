<?php

namespace App\Http\Controllers;

use App\Config;
use App\Mail\ContactUsMail;
use App\ShopApi\Orders;
use App\ShopApi\TrackingMore;
use App\Trigger;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;


class AppController extends Controller
{
    public $track;

    public function __construct()
    {
        $this->middleware('auth.shopify');
//        $this->middleware('CheckIfUserSetup')->except(['settingSave', 'fetchData', 'trialAccepted']);
        $this->track = new TrackingMore();
    }

    public function exportForm()
    {
        return view('export-form');
    }

    public function exportFormToExcel(Request $request)
    {
        $export_for = $request->btn_export;
        $order_status = $request->order_status;
        $user_id = Auth::user()->id;
        $orders = Orders::getOrdersToExport($user_id, $order_status);
        $export_data = [];

        foreach ($orders as $order) {
            $address = $order->shipping_address->address1 . ' ' . $order->shipping_address->address2;
            $phone = str_replace(' ', '', $order->shipping_address->phone) . '';
            $city = strtoupper($order->shipping_address->city);
            $city = rtrim($city, ' ');
            $city = ltrim($city, ' ');
            $product_detail = '';
            $line_items = $order->line_items;
            foreach ($line_items as $item) {

                $product_detail = $item->title . ',' . $product_detail;
            }
            $product_detail = rtrim($product_detail, ',');

            if ($export_for === EXPORT_BTN_LABEL_TCS) {

                $export_data[] = [

                    HEADER_A => $order->shipping_address->name,
                    HEADER_B => $address,
                    HEADER_C => $phone,
                    HEADER_D => $order->contact_email,
                    HEADER_E => $city,
                    HEADER_F => 1,
                    HEADER_G => 0.5,
                    HEADER_H => $order->current_total_price,
                    HEADER_I => $order->id . '',
                    HEADER_J => 'NO',
                    HEADER_K => 'O',
                    HEADER_L => $product_detail,
                    HEADER_M => '',
                    HEADER_N => '',
                ];

            } elseif ($export_for === EXPORT_BTN_LABEL_LCS) {
                $export_data[] = [

                    HEADER_A_LCS => 'BEAUTIIVO',
                    HEADER_B_LCS => '03117118700',
                    HEADER_C_LCS => '8-PIR GHAZI ROAD INFRONT MINDIR BROSTAN NEAR WAQAS HARDWARE ICHRA LAHORE',
                    HEADER_D_LCS => 'beautiivo@hotmail.com',
                    HEADER_E_LCS => 'LAHORE',
                    HEADER_F_LCS => $order->shipping_address->name,
                    HEADER_G_LCS => $order->contact_email,
                    HEADER_H_LCS => $phone,
                    HEADER_I_LCS => $address,
                    HEADER_J_LCS => $city,
                    HEADER_K_LCS => $order->current_total_price,
                    HEADER_L_LCS => $order->id . '',
                    HEADER_M_LCS => $product_detail,
                    HEADER_N_LCS => '500',
                    HEADER_O_LCS => 'overnight',
                    HEADER_P_LCS => '1',
                ];
            }
        }

        if ($export_for === EXPORT_BTN_LABEL_TCS) {

            $this->exportDataToExcelForTCS($export_data, $order_status);
        } elseif ($export_for === EXPORT_BTN_LABEL_LCS) {

            $this->exportDataToExcelForLCS($export_data, $order_status);
        }
    }

    public function exportDataToExcelForTCS($data, $file_name_part)
    {

        $file = new Spreadsheet();
        $file_type = 'Xlsx';
        $active_sheet = $file->getActiveSheet();

        $active_sheet->setCellValue('A1', HEADER_A);
        $active_sheet->setCellValue('B1', HEADER_B);
        $active_sheet->setCellValue('C1', HEADER_C);
        $active_sheet->setCellValue('D1', HEADER_D);
        $active_sheet->setCellValue('E1', HEADER_E);
        $active_sheet->setCellValue('F1', HEADER_F);
        $active_sheet->setCellValue('G1', HEADER_G);
        $active_sheet->setCellValue('H1', HEADER_H);
        $active_sheet->setCellValue('I1', HEADER_I);
        $active_sheet->setCellValue('J1', HEADER_J);
        $active_sheet->setCellValue('K1', HEADER_K);
        $active_sheet->setCellValue('L1', HEADER_L);
        $active_sheet->setCellValue('M1', HEADER_M);
        $active_sheet->setCellValue('N1', HEADER_N);

        $count = 2;

        foreach ($data as $row) {
            $active_sheet->setCellValue('A' . $count, $row[HEADER_A]);
            $active_sheet->setCellValue('B' . $count, $row[HEADER_B]);
            $active_sheet->setCellValue('C' . $count, $row[HEADER_C]);
            $active_sheet->setCellValue('D' . $count, $row[HEADER_D]);
            $active_sheet->setCellValue('E' . $count, $row[HEADER_E]);
            $active_sheet->setCellValue('F' . $count, $row[HEADER_F]);
            $active_sheet->setCellValue('G' . $count, $row[HEADER_G]);
            $active_sheet->setCellValue('H' . $count, $row[HEADER_H]);
            $active_sheet->setCellValue('I' . $count, $row[HEADER_I]);
            $active_sheet->setCellValue('J' . $count, $row[HEADER_J]);
            $active_sheet->setCellValue('K' . $count, $row[HEADER_K]);
            $active_sheet->setCellValue('L' . $count, $row[HEADER_L]);
            $active_sheet->setCellValue('M' . $count, $row[HEADER_M]);
            $active_sheet->setCellValue('N' . $count, $row[HEADER_N]);

            $active_sheet->getCell('C' . $count)->setDataType(DataType::TYPE_STRING2);
            $active_sheet->getCell('I' . $count)->setDataType(DataType::TYPE_STRING2);

            $count = $count + 1;
        }

        $total_rows = count($data) + 20;

        $active_sheet->getColumnDimension('A')->setWidth(30);
        $active_sheet->getColumnDimension('B')->setWidth(40);
        $active_sheet->getColumnDimension('C')->setWidth(20);
        $active_sheet->getColumnDimension('D')->setWidth(30);
        $active_sheet->getColumnDimension('E')->setWidth(25);
        $active_sheet->getColumnDimension('F')->setWidth(20);
        $active_sheet->getColumnDimension('G')->setWidth(20);
        $active_sheet->getColumnDimension('H')->setWidth(15);
        $active_sheet->getColumnDimension('I')->setWidth(15);
        $active_sheet->getColumnDimension('J')->setWidth(15);
        $active_sheet->getColumnDimension('K')->setWidth(20);
        $active_sheet->getColumnDimension('L')->setWidth(40);
        $active_sheet->getColumnDimension('M')->setWidth(20);
        $active_sheet->getColumnDimension('N')->setWidth(20);
        $active_sheet->getColumnDimension('O')->setWidth(20);


        $active_sheet->getStyle('A1:A' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('C1:C' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('D1:D' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('E1:E' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('F1:F' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('G1:G' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('H1:H' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('I1:I' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('J1:J' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('K1:K' . $total_rows)->getAlignment()->setHorizontal('center');

        $active_sheet
            ->getStyle('A1:O1')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('fcd537');

        $active_sheet
            ->getStyle('A1:O1')
            ->getFont()
            ->getColor()
            ->setARGB('000000');

//        $active_sheet->getStyle('C')->getNumberFormat()->applyFromArray([
//            'formatCode' => NumberFormat::FORMAT_TEXT
//        ]);

        $writer = IOFactory::createWriter($file, $file_type);

        $file_name = 'cod_' . $file_name_part . '_' . time() . '.' . strtolower($file_type);

        $writer->save($file_name);

        header('Content-Type: application/x-www-form-urlencoded');
        header('Content-Transfer-Encoding: Binary');
        header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
        readfile($file_name);
        unlink($file_name);
        exit;
    }

    public function exportDataToExcelForLCS($data, $file_name_part)
    {
        $file = new Spreadsheet();
        $file_type = 'Xlsx';
        $active_sheet = $file->getActiveSheet();

        $active_sheet->setCellValue('A1', HEADER_A_LCS);
        $active_sheet->setCellValue('B1', HEADER_B_LCS);
        $active_sheet->setCellValue('C1', HEADER_C_LCS);
        $active_sheet->setCellValue('D1', HEADER_D_LCS);
        $active_sheet->setCellValue('E1', HEADER_E_LCS);
        $active_sheet->setCellValue('F1', HEADER_F_LCS);
        $active_sheet->setCellValue('G1', HEADER_G_LCS);
        $active_sheet->setCellValue('H1', HEADER_H_LCS);
        $active_sheet->setCellValue('I1', HEADER_I_LCS);
        $active_sheet->setCellValue('J1', HEADER_J_LCS);
        $active_sheet->setCellValue('K1', HEADER_K_LCS);
        $active_sheet->setCellValue('L1', HEADER_L_LCS);
        $active_sheet->setCellValue('M1', HEADER_M_LCS);
        $active_sheet->setCellValue('N1', HEADER_N_LCS);
        $active_sheet->setCellValue('O1', HEADER_O_LCS);
        $active_sheet->setCellValue('P1', HEADER_P_LCS);

        $count = 2;

        foreach ($data as $row) {
            $active_sheet->setCellValue('A' . $count, $row[HEADER_A_LCS]);
            $active_sheet->setCellValue('B' . $count, $row[HEADER_B_LCS]);
            $active_sheet->setCellValue('C' . $count, $row[HEADER_C_LCS]);
            $active_sheet->setCellValue('D' . $count, $row[HEADER_D_LCS]);
            $active_sheet->setCellValue('E' . $count, $row[HEADER_E_LCS]);
            $active_sheet->setCellValue('F' . $count, $row[HEADER_F_LCS]);
            $active_sheet->setCellValue('G' . $count, $row[HEADER_G_LCS]);
            $active_sheet->setCellValue('H' . $count, $row[HEADER_H_LCS]);
            $active_sheet->setCellValue('I' . $count, $row[HEADER_I_LCS]);
            $active_sheet->setCellValue('J' . $count, $row[HEADER_J_LCS]);
            $active_sheet->setCellValue('K' . $count, $row[HEADER_K_LCS]);
            $active_sheet->setCellValue('L' . $count, $row[HEADER_L_LCS]);
            $active_sheet->setCellValue('M' . $count, $row[HEADER_M_LCS]);
            $active_sheet->setCellValue('N' . $count, $row[HEADER_N_LCS]);
            $active_sheet->setCellValue('O' . $count, $row[HEADER_O_LCS]);
            $active_sheet->setCellValue('P' . $count, $row[HEADER_P_LCS]);

            $active_sheet->getCell('H' . $count)->setDataType(DataType::TYPE_STRING2);
            $active_sheet->getCell('L' . $count)->setDataType(DataType::TYPE_STRING2);

            $count = $count + 1;
        }

        $total_rows = count($data) + 20;

        $active_sheet->getColumnDimension('A')->setWidth(20);
        $active_sheet->getColumnDimension('B')->setWidth(20);
        $active_sheet->getColumnDimension('C')->setWidth(40);
        $active_sheet->getColumnDimension('D')->setWidth(30);
        $active_sheet->getColumnDimension('E')->setWidth(25);
        $active_sheet->getColumnDimension('F')->setWidth(30);
        $active_sheet->getColumnDimension('G')->setWidth(30);
        $active_sheet->getColumnDimension('H')->setWidth(25);
        $active_sheet->getColumnDimension('I')->setWidth(40);
        $active_sheet->getColumnDimension('J')->setWidth(22);
        $active_sheet->getColumnDimension('K')->setWidth(25);
        $active_sheet->getColumnDimension('L')->setWidth(40);
        $active_sheet->getColumnDimension('M')->setWidth(30);
        $active_sheet->getColumnDimension('N')->setWidth(22);
        $active_sheet->getColumnDimension('O')->setWidth(20);
        $active_sheet->getColumnDimension('P')->setWidth(20);


        $active_sheet->getStyle('A1:A' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('B1:B' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('D1:D' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('E1:E' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('F1:F' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('G1:G' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('H1:H' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('J1:J' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('K1:K' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('L1:L' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('M1:M' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('N1:N' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('O1:O' . $total_rows)->getAlignment()->setHorizontal('center');
        $active_sheet->getStyle('P1:P' . $total_rows)->getAlignment()->setHorizontal('center');

        $active_sheet
            ->getStyle('A1:O1')
            ->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('fcd537');

        $active_sheet
            ->getStyle('A1:O1')
            ->getFont()
            ->getColor()
            ->setARGB('000000');

//        $active_sheet->getStyle('C')->getNumberFormat()->applyFromArray([
//            'formatCode' => NumberFormat::FORMAT_TEXT
//        ]);

        $writer = IOFactory::createWriter($file, $file_type);

        $file_name = 'lcs_cod_' . $file_name_part . '_' . time() . '.' . strtolower($file_type);

        $writer->save($file_name);

        header('Content-Type: application/x-www-form-urlencoded');
        header('Content-Transfer-Encoding: Binary');
        header("Content-disposition: attachment; filename=\"" . $file_name . "\"");
        readfile($file_name);
        unlink($file_name);
        exit;
    }

    public function dashboard($startDate = null, $endDate = null)
    {
//        return redirect()->route('export-form');
        $user_id = Auth::user()->id;
        $user = User::find($user_id);

        dd(Route::currentRouteName());
//        $new_orders = $user->api()->rest('GET', '/admin/api/2021-07/orders.json');
        $orders = Orders::all($user_id, '06/01/2021', '08/31/2021');
        dd($orders);
        $filtered_date = null;
        if ($startDate !== null && $endDate !== null) {

            $tracking_data = $user->trackings()->whereBetween('order_date', [
                $startDate,
                $endDate
            ])->get();
            $filtered_date = $startDate . ' - ' . $endDate;
        } else {

            $tracking_data = $user->trackings;
        }

        $line_items = $tracking_data;

//        $line_items=[];
//        $line_items[]=$this->getDummyOrder('FAUXIE™- Fluffy Coat',TM_DELIVERED,'John','Canada');
//        $line_items[]=$this->getDummyOrder('Turtler™- Knitted Sweater',TM_DELIVERED,'Martin','Canada');
//        $line_items[]=$this->getDummyOrder('COMFY™- Hoodie Sweater',TM_DELIVERED,'raymond','Canada');
//        $line_items[]=$this->getDummyOrder('Trench™ - Double Breasted Coat',TM_EXCEPTION,'Johnson','USA');
//        $line_items[]=$this->getDummyOrder('FRAG™- Patchwork Coat',TM_TRANSIT,'Johnson','Canada');
//        $line_items[]=$this->getDummyOrder('Leopardie™ - Faux Coat',TM_PENDING,'Johnson','Canada');
//        $line_items[]=$this->getDummyOrder('Flarzer™- Long Coat',TM_DELIVERED,'Johnson','Canada');
//        $line_items[]=$this->getDummyOrder('Conifer™ - Round Neck Sweater',TM_PICKUP,'Johnson','Canada');
//        $line_items[]=$this->getDummyOrder('Conifer™ - Round Neck Bracelet',TM_DELIVERED,'Johnson','Canada');
//        $line_items[]=$this->getDummyOrder('Conifer™ - Round Neck Bracelet',TM_UNDELIVERED,'Johnson','Canada');
//        $line_items[]=$this->getDummyOrder('Conifer™ - Round Neck Bracelet',TM_UNDELIVERED,'Johnson','Canada');
//        $line_items[]=$this->getDummyOrder('Conifer™ - Round Neck Bracelet',TM_UNDELIVERED,'Johnson','Canada');
//        $line_items[]=$this->getDummyOrder('Conifer™ - Round Neck Bracelet',TM_DELIVERED,'Johnson','Canada');
//        $line_items[]=$this->getDummyOrder('Conifer™ - Round Neck Bracelet',TM_NOT_FOUND,'Johnson','Canada');
//        $line_items[]=$this->getDummyOrder('Conifer™ - Round Neck Bracelet',TM_NOT_FOUND,'Johnson','Canada');
//        $line_items[]=$this->getDummyOrder('Conifer™ - Round Neck Bracelet',TM_DELIVERED,'Johnson','Canada');
//        $line_items[]=$this->getDummyOrder('Conifer™ - Round Neck Bracelet',TM_DELIVERED,'Johnson','Canada');
//        $line_items[]=$this->getDummyOrder('Conifer™ - Round Neck Bracelet',TM_DELIVERED,'Johnson','Canada');
//        $line_items[]=$this->getDummyOrder('Conifer™ - Round Neck Bracelet',TM_EXPIRED,'Johnson','Canada');
//        $line_items[]=$this->getDummyOrder('Conifer™ - Round Neck Bracelet',TM_EXPIRED,'Johnson','Canada');
//        $line_items[]=$this->getDummyOrder('Conifer™ - Round Neck Bracelet',TM_DELIVERED,'Johnson','Canada');
//        $line_items[]=$this->getDummyOrder('Conifer™ - Round Neck Bracelet',TM_DELIVERED,'Johnson','Canada');

        $percentage = new \stdClass();
        $percentage->delivered = getOrdersAndPercentageByStatus($line_items, TM_DELIVERED)['percentage'];
        $percentage->exception = getOrdersAndPercentageByStatus($line_items, TM_EXCEPTION)['percentage'];
        $percentage->out_for_delivery = getOrdersAndPercentageByStatus($line_items, TM_PICKUP)['percentage'];// out for delivery
        $percentage->failed_attempts = getOrdersAndPercentageByStatus($line_items, TM_UNDELIVERED)['percentage'];// failed attempt
        $percentage->transit = getOrdersAndPercentageByStatus($line_items, TM_TRANSIT)['percentage'];
        $percentage->expired = getOrdersAndPercentageByStatus($line_items, TM_EXPIRED)['percentage'];
        $percentage->info_not_received = getOrdersAndPercentageByStatus($line_items, TM_NOT_FOUND)['percentage'];// info received
        $percentage->pending = getOrdersAndPercentageByStatus($line_items, TM_PENDING)['percentage'];

//        $percentage->delivered = 95;
//        $percentage->exception = 5;
//        $percentage->out_for_delivery = 75;// out for delivery
//        $percentage->failed_attempts = 9;// failed attempt
//        $percentage->transit = 80;
//        $percentage->expired = 10;
//        $percentage->info_not_received = 20;// info received
//        $percentage->pending = 69;

        $refresh_date = date('Y/m/d', strtotime($user->refresh_date));
        $is_step_by_step_config_completed = Config::getConfig(Auth::id(), CONFIG_TYPE_STEP_BY_STEP_DASHBOARD) === NULL ? false : true;
        return view('home.dashboard')
            ->with('order_items', $line_items)
            ->with('percentage', $percentage)
            ->with('refresh_date', $refresh_date)
            ->with('is_step_by_step_config_completed', $is_step_by_step_config_completed)
            ->with('filtered_date', $filtered_date);
    }

    public function stripeTest()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $customer = \Stripe\Customer::create();
        $intent = \Stripe\PaymentIntent::create([
            'amount'   => 100,
            'currency' => 'usd',
            'customer' => $customer->id,
        ]);
        return $intent;
    }

    public function getDummyOrder($title, $status, $customer, $country)
    {

        $item = new \stdClass();
        $item->order_number = '#' . rand(1000, 9999);
        $item->item_name = $title;
        $item->status = $status;
        $item->customer_name = $customer;
        $item->country = $country;
        $item->order_url = '';
        $item->fulfill_at = \date('d/m/y');
        $item->created_at = \date('d/m/y');

        return $item;
    }

    public function triggers()
    {
        $triggers = Trigger::all();
        $active_triggers = Auth::user()->triggers;
        $attached_trigger = [];
        foreach ($active_triggers as $trgr) {
            $attached_trigger[$trgr->id] = $trgr;
        }
        return view('home.triggers')
            ->with('triggers', $triggers)
            ->with('attached_trigger', $attached_trigger)
            ->with('active_triggers', $active_triggers);
    }

    public function store()
    {
        $data = request()->all();

        $triggerID = $data['trigger_id'];
        $numberOfDays = $data['days'];
        $user = Auth::user();
        $raw = [
            $triggerID => [
                'days' => $numberOfDays
            ]
        ];

        $user->triggers()->attach($raw);
        $res['status'] = true;
        return response(json_encode($res));
    }

    public function update($trigger_id)
    {
        $days = request()->all()['days'];
        $user = Auth::user();
        $user->triggers()->updateExistingPivot($trigger_id, ['days' => $days]);
        $res['status'] = true;
        return response(json_encode($res));
    }

    public function destroy($trigger_id)
    {
        $user = Auth::user();
        $user->triggers()->detach($trigger_id);
        $res['status'] = true;
        return response(json_encode($res));
    }

    public function configSave()
    {
        Config::saveConfig(CONFIG_TYPE_STEP_BY_STEP_DASHBOARD, 'completed');
        return response(['status' => true]);
    }

    public function settings()
    {
        $user = Auth::user();
        $slack_webhook = Config::getConfig($user->id, CONFIG_TYPE_SLACK_WEBHOOK);
        $slack_channel = Config::getConfig($user->id, CONFIG_TYPE_SLACK_CHANNEL_NAME);
        $payment = $user->payment;

        $card_holder_name = '';
        $card_last4 = '';
        $stripe_customer_id = '';
        if ($payment) {
            $card_holder_name = $payment->name;
            $card_last4 = $payment->last_four;
            $stripe_customer_id = $payment->customer;
        }

        return view('home.settings')
            ->with('card_holder_name', $card_holder_name)
            ->with('card_last4', $card_last4)
            ->with('stripe_customer_id', $stripe_customer_id)
            ->with('slack_channel', $slack_channel)
            ->with('slack_webhook', $slack_webhook);
    }

    public function settingSave()
    {
        $data = request()->all();
        $slack_channel = $data['slack_channel'];
        $slack_webhook = $data['slack_webhook'];
        $message = $data['slack_message'];
//        $message = 'HOORAH! Slack is connected, Have fun with OrderStalker';
        $slack_res = sendSlackMessageByHook($slack_webhook, $message);
        if ($slack_res) {
            Config::saveConfig(CONFIG_TYPE_SLACK_WEBHOOK, $slack_webhook);
            Config::saveConfig(CONFIG_TYPE_SLACK_CHANNEL_NAME, $slack_channel);
            $response = json_encode(['status' => true]);
        } else {
            $response = json_encode(['status' => false]);
        }

        return response($response);
//        dd($request);
//        $regex = '/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';
//        $request->validate([
//            'slack_channel' => 'required',
//            'slack_webhook' => 'required|regex:' . $regex,
//        ]);
//
//        $slack_channel = $request->slack_channel;
//        $slack_webhook = $request->slack_webhook;
//        $message = 'HOORAH! Slack is connected, Have fun with OrderStalker';
//        sendSlackMessage(Auth::user()->id, $message);
//        return redirect()->route('video');
    }

    public function updatePaymentInfo()
    {
        try {
            $res = request()->all();
            $stripe_customer_id = $res['customer_id'];
            $stripe_pm_id = $res['payment_method'];
            $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
            $customer = $stripe->customers->update(
                $res['customer_id'],
                [
                    'email'       => $res['email'],
                    'description' => 'Payment Method updated'
                ]
            );
            $all_payment_methods = $stripe->paymentMethods->all([
                'customer' => $res['customer_id'],
                'type'     => 'card',
            ])->data;
            $stripe->paymentMethods->attach($stripe_pm_id, ['customer' => $stripe_customer_id]);
            foreach ($all_payment_methods as $pm) {

                $stripe->paymentMethods->detach($pm['id']);
            }

            $all_payment_methods = $stripe->paymentMethods->all([
                'customer' => $res['customer_id'],
                'type'     => 'card',
            ])->data;
            $last_four = $all_payment_methods[0]['card']['last4'];

            $data = [
                'name'           => $res['name'],
                'payment_method' => $stripe_pm_id,
                'last_four'      => $last_four,
            ];

            $user = Auth::user();
            $user->payment()->updateOrCreate(['user_id' => Auth::user()->id], $data);

            return response(json_encode(['error' => false]));
        } catch (ApiErrorException $e) {

            return response(json_encode([
                'error'   => true,
                'message' => $e->getError()->message
            ]));
        }
    }

    public function fetchData()
    {
        Orders::initializeOrders();
        return response(['status' => true]);
    }

    public function billingTest()
    {
        $shop = Auth::user();


//        $active_params = [
//            "id" => 20013121707,
//        ];
//        $bill = $shop->api()->rest('POST', '/admin/api/2020-10/recurring_application_charges/20013121707/activate.json', $active_params);
//        dd($bill);


        $params = [
            "recurring_application_charge" => [
                "name"          => "Orderstalker Test Usage Payment final",
                "price"         => 29.95,
                "return_url"    => route('all-billing'),
                "capped_amount" => 10,
                "terms"         => 'Charge $10 on every 500+ orders',
                "trial_days"    => 14,
                "test"          => true
            ]
        ];

        $graph_params = '
        mutation {
  appSubscriptionCreate(
    name: "Super Duper Capped Pricing Plan"
    returnUrl: "http://super-duper.shopifyapps.com"
    lineItems: [{
      plan: {
        appUsagePricingDetails: {
              terms: "$1 for 100 emails"
              cappedAmount: { amount: 20.00, currencyCode: USD }
              test: true

        }
      }
    }]
  ){
    userErrors {
      field
      message
    }
    confirmationUrl
    appSubscription {
      id
      lineItems {
        id
        plan {
          pricingDetails {
            __typename
          }
        }
      }
    }
  }
}
        ';


//        //Graph QL call
//        $bill = $shop->api()->graph($graph_params);
//        dd($bill);
//        $confirmation_url = $bill['body']->container['data']['appSubscriptionCreate']['confirmationUrl'];
//        return redirect()->away($confirmation_url);
//        dd($confirmation_url);


//        $bill = $shop->api()->rest('POST', '/admin/api/2020-10/recurring_application_charges.json', $params);

        $params = [
            "usage_charge" => [
                "description" => "plan 500+ orders",
                "price"       => 10.0,
                "test"        => true,
            ]
        ];
        $bill = $shop->api()->rest('POST', '/admin/api/2020-10/recurring_application_charges/20013121707/usage_charges.json', $params);

        dd($bill);
        $confirmation_url = $bill['body']->container['recurring_application_charge']['confirmation_url'];
//        dd($confirmation_url);
        return redirect()->away($confirmation_url);
    }

    public function allBilling()
    {

        $params = [];
        $shop = Auth::user();

        $bill = $shop->api()->rest('GET', '/admin/api/2020-10/recurring_application_charges.json', $params);
        dd($bill);
        dump('all billing');
        dd($bill['body']->container['recurring_application_charges']);
    }

    public function support()
    {

//        Mail::to('moazamin6@gmail.com')->send(new ContactUsMail());
//        $to_name = 'Moaz Amin';
//        $to_email = 'moazamin6@gmail.com';
//        $data = array('name'=>"Sam Jose", "body" => "Test mail");
//        Mail::send('emails', $data, function($message) use ($to_name, $to_email) {
//            $message->to($to_email, $to_name)->subject('Artisans Web Testing Mail');
//            $message->from('orderstalker@gmail.com','Artisans Web');
//        });
        return view('support');
    }

    public function contactUs()
    {
        $request = json_decode(json_encode(request()->all()));
        $name = $request->name;
        $email = $request->email;
        $message = $request->message;

        dd($request);
    }

    public function trialAccepted()
    {
        $shop = Auth::user();
        $payment_id = request('charge_id');

        $activation = $shop->api()->rest('POST', '/admin/api/2020-10/recurring_application_charges/' . $payment_id . '/activate.json');

        if ($activation['errors'] === false) {
            $activation = $activation['body']->container['recurring_application_charge'];

            $data = [
                'payment_type'    => PAYMENT_TYPE_RECURRING_APPLICATION_CHARGE,
                'payment_id'      => $payment_id,
                'amount'          => 0,
                'next_payment_at' => date('y-m-d', strtotime($activation['billing_on'])),
            ];
            $shop->payment()->create($data);
            Config::saveConfig(CONFIG_TYPE_TRIAL_ACCEPTED, 'accepted');
            return redirect()->route('dashboard');
        }
        dd($activation);
    }

    public function testTriggers()
    {
        $user_id = Auth::user()->id;
        $message = "Your Order SL2598 | Slim Fit Men’s Ninja Hoodie | UK has confronted a problem Tracking Info Not Updated. This generated text is only for testing purpose.";
        sendSlackMessage($user_id, $message);
        return redirect()->route('triggers');
    }
}
