<?php

if (! defined('ABSPATH')) {
    exit;
}

class PaymentHook
{
    private static $instance = null;
    private $sheetId = "1qcvT0ZvE7DxO0d7byGbHxHJ5LJ_jzllsRhWHoO4ViyY"; //It is present in your URL
    private $client = null;
    private $service = null;
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new PaymentHook();
            require_once paymentSync_INCLUDES . 'vendor/autoload.php';
        }
        return self::$instance;
    }
    public function __construct()
    {
        add_action('rest_api_init', array($this, "api_inits"));
        add_action('wp_ajax_getpaymenthistory', array($this, 'getpaymenthistoryTable'));
        add_action('wp_ajax_nopriv_getpaymenthistory', array($this, 'getpaymenthistoryTable'));
    }
    public function getpaymenthistoryTable()
    {
        $draw = $_POST['draw'];
        $offset = $_POST['start'];
        $count = $_POST['length'];
        $order = $_POST['order'][0]['column'] ?? "";
        $direction = $_POST['order'][0]['dir'] ?? "";
        $search_value = $_POST['search']['value'] ?? "";


        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM phantom_payment_histories ORDER BY id DESC LIMIT 100");
        $return = array();
        $return['draw'] = $draw;
        $return['recordsTotal'] = count($results);
        $return['recordsFiltered'] = count($results);
        $return['data'] = array();

        foreach ($results as $row) {
            $row_obj = new stdClass();
            $row_obj->DateTime = $row->DateTime;
            $row_obj->item = $row->item;
            $row_obj->message = $row->message;
            $row_obj->amount = $row->amount;
            $row_obj->buyer_email = $row->buyer_email;
            $row_obj->buyer_name = $row->buyer_name;
            $row_obj->affiliate_id = $row->affiliate_id;
            $row_obj->payment_id = $row->payment_id;
            $row_obj->order_id = $row->order_id;
            $row_obj->payment_type = $row->payment_type;
            $return['data'][] = $row_obj;
        }
        echo json_encode($return);
        wp_die();
    }
    public function initSheetValues()
    {
        if ($this->client == null) {
            $this->client = new \Google_Client();
            $this->client->setApplicationName('Google Sheets and PHP');
            $this->client->setScopes([\Google_Service_Sheets::SPREADSHEETS, \Google_Service_Drive::DRIVE_FILE]);
            $this->client->setAccessType('offline');
            $this->client->setAuthConfig(__DIR__ . '/phantom_credentials.json');
            $this->service = new \Google_Service_Sheets($this->client);
        }
    }
    public function api_inits()
    {
        register_rest_route("PP/v1", "paypalCheckoutOrderCompleted", array(
            "methods" => "POST",
            "callback" => array($this, "paypalCheckoutOrderCompleted"),
            "permission_callback" => "__return_true",
        ));
        register_rest_route("PP/v1", "stripeCheckoutOrderCompleted", array(
            "methods" => "POST",
            "callback" => array($this, "stripeCheckoutOrderCompleted"),
            "permission_callback" => "__return_true",
        ));
        register_rest_route("PP/v1", "phantom_payment_histories", array(
            "methods" => "POST",
            "callback" => array($this, "updatesheetsManually"),
            "permission_callback" => "__return_true",
        ));

    }
    public function updatesheetsManually(WP_REST_Request $request)
    {
        try {
            $payload = $request->get_json_params();
            if (isset($payload['payment_type'])) {
                foreach ($payload['payment_type'] as $payment_type) {
                    $this->resortPaymentHistoryDB($payment_type);
                    $this->updateSheet($payment_type);
                }
            }
            return new WP_REST_Response(array(
                'message' => 'updatesheet function worked'
            ), 200);

        } catch (Exception $e) {
            return new WP_REST_Response(array(
                'message' => $e->getMessage(),
            ));
        }


    }
    public function insertToPaymentHistoryTable($newRow, $payment_type)
    {
        global $wpdb;
        $result = $wpdb->insert(
            'phantom_payment_histories', array(
                'DateTime' => $newRow[0],
                'item' => $newRow[1],
                'message' => $newRow[2],
                'amount' => $newRow[5],
                'buyer_email' => $newRow[6],
                'buyer_name' => $newRow[7],
                'affiliate_id' => $newRow[8],
                'payment_id' => $newRow[9],
                'order_id' => $newRow[10],
                'payment_type' => $payment_type
            ), array(
                '%s',
                '%s',
                '%s',
                '%f',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            )
        );
    }

    public function getFPRewards($page = 1)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://firstpromoter.com/api/v1/rewards/list?page=' . $page,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'x-api-key: 5c4ebdf7622bea1aa2c2f8be467d0c01',
            ),
        ));

        $response = json_decode(curl_exec($curl));

        curl_close($curl);

        return $response;

    }
    public function updateSheet($range)
    {
        try {
            $this->initSheetValues();

            // $rows = [$newRow]; // you can append several rows at once
            $valueRange = new \Google_Service_Sheets_ValueRange();
            global $wpdb;
            $query = "SELECT * FROM phantom_payment_histories WHERE payment_type = '" . $range . "' ORDER BY id DESC LIMIT 200";
            $results = $wpdb->get_results($query);
            $rows = [
                [
                    'DateTime(UTC)',
                    'ITEM', 'Message', 'Amount',
                    "Buyer's Email", "Buyer's Name", "Selling Affiliate ID", "Payment ID", "Order ID", "Payment Type"
                ]
            ];

            for ($i = count($results) - 1; $i >= 0; $i--) {
                $rows[] = [
                    $results[$i]->DateTime ?? '',
                    $results[$i]->item ?? '',
                    $results[$i]->message ?? '',
                    $results[$i]->amount ?? '',
                    $results[$i]->buyer_email ?? '',
                    $results[$i]->buyer_name ?? '',
                    $results[$i]->affiliate_id ?? '',
                    $results[$i]->payment_id ?? '',
                    $results[$i]->order_id ?? '',
                    $results[$i]->payment_type ?? '',
                ];
            }
            // foreach ($results as $row) {
            //     $rows[] = [
            //         $row->DateTime ?? '',
            //         $row->item ?? '',
            //         $row->message ?? '',
            //         $row->amount ?? '',
            //         $row->buyer_email ?? '',
            //         $row->buyer_name ?? '',
            //         $row->affiliate_id ?? '',
            //         $row->payment_id ?? '',
            //         $row->order_id ?? '',
            //         $row->payment_type ?? '',
            //     ];
            // }

            $valueRange->setValues($rows);
            $options = ['valueInputOption' => 'RAW'];
            $clear = new \Google_Service_Sheets_ClearValuesRequest();
            $this->service->spreadsheets_values->clear($this->sheetId, $range, $clear);
            $res = $this->service->spreadsheets_values->append($this->sheetId, $range, $valueRange, $options);
        } catch (\Exception $e) {
            $err = $e->getMessage();
        }

    }

    public function stripeCheckoutOrderCompleted($request)
    {
        // error_log('Stripe-checkoutOrderCompleted');
        $payload = $request->get_json_params();
        // error_log(print_r($payload, true));

        $newRow = [
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            'Stripe',
        ];

        if ($payload['type'] == 'invoice.payment_succeeded') {
            $newRow[0] = date('Y-m-d H:i:s', $payload['created']);
            $newRow[2] = 'Success';
            $newRow[5] = 0;
            foreach ($payload['data']['object']['lines']['data'] as $line_item) {
                $newRow[1] = $newRow . ' ' . $line_item['description'];
                $newRow[5] += $line_item['amount'];
            }
            $newRow[5] = $newRow[5] / 100;
            $newRow[6] = $payload['data']['object']['customer_email'];
            $newRow[7] = $payload['data']['object']['customer_name'];
            $newRow[9] = $payload['data']['object']['payment_intent'];


            $this->insertToPaymentHistoryTable($newRow, 'Stripe');
            $this->resortPaymentHistoryDB('Stripe');
            $this->updateSheet('Stripe');

        }

    }

    public function paypalCheckoutOrderCompleted($request)
    {
        // error_log('PayPal-checkoutOrderCompleted');
        $payload = $request->get_json_params();

        $newRow = [
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            'PayPal',
        ];

        if ($payload['resource_type'] == 'checkout-order' && $payload['event_type'] == 'CHECKOUT.ORDER.APPROVED') {
            $newRow[0] = $payload['create_time'];
            $newRow[2] = $payload['summary'];
            $resource_id = $payload['resource']['id'];

            $payer = $payload['resource']['payer'];
            $newRow[7] = $payer['name']['given_name'] . ' ' . $payer['name']['surname'];
            $amount = 0;
            $items = [];
            foreach ($payload['resource']['purchase_units'] as $unit) {
                $amount += $unit['amount']['value'];
                foreach ($unit['items'] as $item) {
                    $items[] = $item['name'];
                }
            }
            $newRow[1] = '';
            foreach ($items as $item) {
                $newRow[1] = $newRow[1] . ' ' . $item;
            }

            $newRow[5] = $amount;
            $email_address = $payer['email_address'];
            $newRow[6] = $email_address;
            $newRow[10] = $resource_id;
            $this->insertToPaymentHistoryTable($newRow, 'PayPal');
            $this->resortPaymentHistoryDB('PayPal');
            $this->updateSheet('PayPal');

        } else if ($payload['resource_type'] == 'capture' && $payload['event_type'] == 'PAYMENT.CAPTURE.COMPLETED') {
            $newRow[0] = $payload['create_time'];
            $newRow[2] = $payload['summary'];

            $resource_id = $payload['resource']['id'];
            $order_id = $payload['resource']['supplementary_data']['related_ids']['order_id'];
            $newRow[9] = $resource_id;
            $newRow[10] = $order_id;


            $this->insertToPaymentHistoryTable($newRow, 'PayPal');
            $this->resortPaymentHistoryDB('PayPal');
            $this->updateSheet('PayPal');
        }

    }
    public function resortPaymentHistoryDB($payment_type)
    {
        try {
            $return = [];
            global $wpdb;
            $results = $wpdb->get_results("SELECT * FROM phantom_payment_histories WHERE payment_type = '" . $payment_type . "' ORDER BY id DESC LIMIT 100");
            $count = count($results);
            if ($payment_type == 'PayPal') {
                for ($i = 0; $i < $count; $i++) {
                    if (empty($results[$i]->payment_id)) {
                        $return[] = $i;
                        for ($j = 0; $j < $count; $j++) {
                            if ($j == $i)
                                continue;
                            if ($results[$j]->order_id == $results[$i]->order_id) {
                                //update $i row from $j row data

                                $wpdb->update(
                                    'phantom_payment_histories', array(
                                        'DateTime' => $results[$j]->DateTime,
                                        'message' => 'Success',
                                        'payment_id' => $results[$j]->payment_id
                                    ),
                                    array(
                                        'id' => $results[$i]->id
                                    ),
                                    array(
                                        '%s',
                                        '%s'
                                    )
                                );
                                //remove $j row
                                $wpdb->delete('phantom_payment_histories', array(
                                    'id' => $results[$j]->id
                                ));

                            }
                        }
                    }

                }
            }


            $rewards = array_merge($this->getFPRewards(1), $this->getFPRewards(2));

            for ($i = 0; $i < $count; $i++) {
                if (empty($results[$i]->buyer_email) || ! empty($results[$i]->affiliate_id))
                    continue;
                foreach ($rewards as $reward) {
                    // $lead_email = $results[$i]->buyer_email;
                    $lead_email = $reward->lead->email;
                    if ($lead_email == $results[$i]->buyer_email || ucfirst($lead_email) == ucfirst($results[$i]->buyer_email)) {
                        $ref_id = $reward->promoter->default_ref_id;
                        $wpdb->update('phantom_payment_histories', array(
                            'affiliate_id' => $ref_id
                        ), array('id' => $results[$i]->id)
                        );
                        break;
                    }
                }

            }

        } catch (Exception $e) {

        }
    }

}
PaymentHook::getInstance();