<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Variables;
use App\Models\ViewsLog;

class ApiController extends Controller
{

    public function install_app_shopify()
    {
        // Set variables for our request
        $shop = "midilatino";
        $api_key = "6d8506ddaf4e77949b1e2847cc4bd101";
        $scopes = "read_products";
        $redirect_uri = "https://midilatino.portaldev.xyz/get_access_token";

        // Build install/approval URL to redirect to
        $install_url = "https://" . $shop . ".myshopify.com/admin/oauth/authorize?client_id=" . $api_key . "&scope=" . $scopes . "&redirect_uri=" . urlencode($redirect_uri);

        // Redirect
        header("Location: " . $install_url);
        die();
    }

    public function get_access_token(Request $request)
    {
        $query = array(
            "client_id" => '6d8506ddaf4e77949b1e2847cc4bd101', // Your API key
            "client_secret" => 'cd251abcd2e0a5b5872cffd8f78e8b18', // Your app credentials (secret key)
            "code" => $request->code // Grab the access key from the URL
        );

        // Generate access token URL
        $access_token_url = "https://" . $request->shop . "/admin/oauth/access_token";

        // Configure curl client and execute request
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $access_token_url);
        curl_setopt($ch, CURLOPT_POST, count($query));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
        $result = curl_exec($ch);
        curl_close($ch);

        // Store the access token
        $result = json_decode($result, true);
        $access_token = $result['access_token'];
        echo ($access_token);
    }

    public function get_products()
    {


        $shopify = new Shopify();

        $products = $shopify->get_products();
        $products_object = json_decode($products);

        foreach ($products_object->products as $product) {


            echo ($product->id . '<br>');
            $findproduct = Products::where('product_id', $product->id)->first();

            if (empty($findproduct)) {
                $new_product = new Products();
                $new_product->product_id = $product->id;
                $new_product->title = $product->title;
                if (!empty($product->image->src)) {
                    $new_product->image_url = $product->image->src;
                }
                $new_product->save();
            } else {
                $findproduct->product_id = $product->id;
                $findproduct->title = $product->title;
                if (!empty($product->image->src)) {
                    $findproduct->image_url = $product->image->src;
                }
                $findproduct->save();
            }
        }


        $products_count = $shopify->get_products_count();
        echo ($products_count);
        dd($products_object);
    }





    public function app(Request $request)
    {
        $info = $request->all();

        if (!empty($info['customer_id'])) {

            if (!empty($info['action'])) {
                if ($info['action'] == 'download' && !empty($info['product_id']) && !empty($info['order_id'])) {

                    $shopify = new Shopify();
                    $customer = $shopify->get_customer($info['customer_id']);
                    $json_customer = json_decode($customer);

                    if (!empty($json_customer->customer)) {

                        $views_log = new ViewsLog();
                        $views_log->customer_id = $json_customer->customer->id;
                        $views_log->customer_name = $json_customer->customer->first_name . ' ' . $json_customer->customer->last_name;
                        $views_log->customer_email = $json_customer->customer->email;
                        $views_log->license_id = 0;
                        $views_log->order_id = $info['order_id'];
                        $views_log->product_id = $info['product_id'];
                        $views_log->type = 'download';
                        $views_log->save();
                    }
                }
            }


            $shopify = new Shopify();
            $orders = json_decode($shopify->get_customer_orders($info['customer_id']));
            $data = array();

            //dd($orders);

            foreach ($orders->orders as $order) {

                if ($order->fulfillment_status == "fulfilled") {

                    foreach ($order->line_items as $item) {

                        $product = Products::where('product_id', $item->product_id)->first();
                        $file_size = '';
                        $download_url = '';
                        $license_url = '';
                        if (!empty($product)) {
                            $file_size = $product->file_size;
                            $download_url = $product->download_url;

                            if (!empty($product->license_id)) {
                                $license_url = 'https://midilatino.portaldev.xyz/license/' . $item->product_id . '/' . $order->id . '/' . time();
                            }
                        }

                        $count_downloads = count(ViewsLog::where('customer_id', $info['customer_id'])->where('type', 'download')->where('product_id', $item->product_id)->get());
                        $limit_downloads = Variables::where('name', 'limit_downloads')->first()->value;

                        $itemm = [
                            'order_url' => 'https://midilatin.com/account/orders/' . $order->token,
                            'order_name' => $order->name,
                            'order_id' => $order->id,
                            'product_id' => $item->product_id,
                            'name' => $item->name,
                            'file_size' => $file_size,
                            'license_url' => $license_url,
                            'download_url' => $download_url,
                            'count_downloads' => $count_downloads,
                            'limit_downloads' => $limit_downloads,
                        ];

                        array_push($data, $itemm);
                    }
                }
            }

            return response()->json($data, 200);
        }
    }
}
