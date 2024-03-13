<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;

class Shopify
{

    private $shop_name;
    private $shop_token;
    private $shopify_version;

    function __construct()
    {
        $this->shop_name = env("SHOPIFY_SHOP_NAME", "");
        $this->shop_token = env("SHOPIFY_TOKEN", "");
        $this->shopify_version = env("SHOPIFY_API_VERSION", "");
    }

    public function get_customer_orders($customer_id){
        $response = $this->shopify_request('GET', 'https://' . $this->shop_name . '.myshopify.com/admin/api/' . $this->shopify_version . '/customers/'.$customer_id.'/orders.json?status=any');
        return $response;
    }


    public function get_orders(){
        $response = $this->shopify_request('GET', 'https://' . $this->shop_name . '.myshopify.com/admin/api/' . $this->shopify_version . '/orders.json');
        return $response;
    }

    public function get_order($order_id)
    {
        $response = $this->shopify_request('GET', 'https://' . $this->shop_name . '.myshopify.com/admin/api/' . $this->shopify_version . '/orders/'.$order_id.'.json');
        return $response;
    }

    public function get_products_count()
    {
        $response = $this->shopify_request('GET', 'https://' . $this->shop_name . '.myshopify.com/admin/api/' . $this->shopify_version . '/products/count.json');
        return $response;
    }

    public function get_products()
    {
        $response = $this->shopify_request('GET', 'https://' . $this->shop_name . '.myshopify.com/admin/api/' . $this->shopify_version . '/products.json?limit=250&ids=5653428764823');
        return $response;
    }

    public function get_product($product_id)
    {
        $response = $this->shopify_request('GET', 'https://' . $this->shop_name . '.myshopify.com/admin/api/' . $this->shopify_version . '/products/' . $product_id . '.json');
        return $response;
    }

    public function get_customer($customer_id)
    {
        $response = $this->shopify_request('GET', 'https://' . $this->shop_name . '.myshopify.com/admin/api/' . $this->shopify_version . '/customers/' . $customer_id . '.json');
        return $response;
    }

    public function create_customer($body)
    {
        /*$body = '{
            "customer": {
                "first_name": "Steve",
                "last_name": "Lastnameson",
                "email": "steve.lastnameson@example.com",
                "phone": "+15142546011",
                "verified_email": true,
                "addresses": [
                    {
                        "address1": "123 Oak St",
                        "city": "Ottawa",
                        "province": "ON",
                        "phone": "555-1212",
                        "zip": "123 ABC",
                        "last_name": "Lastnameson",
                        "first_name": "Mother",
                        "country": "CA"
                    }
                ],
                "password": "newpass",
                "password_confirmation": "newpass",
                "send_email_welcome": false
            }
        }';*/
        $response = $this->shopify_request('POST', 'https://' . $this->shop_name . '.myshopify.com/admin/api/' . $this->shopify_version . '/customers.json', $body);
        return $response;
    }

    public function update_customer($customer_id, $body)
    {
        /*$body = '{
            "customer": {
                "id": 207119551,
                "metafields": [
                    {
                        "key": "new",
                        "value": "newvalue",
                        "type": "single_line_text_field",
                        "namespace": "global"
                    }
                ]
            }
        }';*/
        $response = $this->shopify_request('PUT', 'https://' . $this->shop_name . '.myshopify.com/admin/api/' . $this->shopify_version . '/customers/' . $customer_id . '.json', $body);
        return $response;
    }

    public function create_price_rule($body)
    {

        /*$body = '{
            "price_rule":
            {
                "title":"Buy2iPodsGetiPodTouchForFree",
                "value_type":"percentage",
                "value":"-100.0",
                "customer_selection":"all",
                "target_type":"line_item",
                "target_selection":"entitled",
                "allocation_method":"each",
                "starts_at":"2018-03-22T00:00:00-00:00",
                "prerequisite_collection_ids":[841564295],
                "entitled_product_ids":[921728736],
                "prerequisite_to_entitlement_quantity_ratio":
                {
                    "prerequisite_quantity":2,
                    "entitled_quantity":1
                },
                "allocation_limit":3
            }
        }';*/

        $response = $this->shopify_request('POST', 'https://' . $this->shop_name . '.myshopify.com/admin/api/' . $this->shopify_version . '/price_rules.json', $body);
        return $response;
    }

    public function create_discount_code($price_rule_id, $code)
    {

        $body = '{"discount_code":{"code":"' . $code . '"}}';

        $response = $this->shopify_request('POST', 'https://' . $this->shop_name . '.myshopify.com/admin/api/' . $this->shopify_version . '/price_rules/' . $price_rule_id . '/discount_codes.json', $body);
        return $response;
    }

    private function shopify_request($method, $url, $body = '')
    {

        try {
            $curl = curl_init();

            if ($curl === false) {
                throw new Exception('failed to initialize');
            }

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 68,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $body,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'X-Shopify-Access-Token: ' . $this->shop_token
                ),
            ));

            $response = curl_exec($curl);

            if ($response === false) {
                throw new Exception(curl_error($curl), curl_errno($curl));
            }

            curl_close($curl);


            return $response;
        } catch (Exception $e) {

            trigger_error(
                sprintf(
                    'Curl failed with error #%d: %s',
                    $e->getCode(),
                    $e->getMessage()
                ),
                E_USER_ERROR
            );
        } finally {
            // Close curl handle unless it failed to initialize
            if (is_resource($curl)) {
                curl_close($curl);
            }
        }
    }
}
