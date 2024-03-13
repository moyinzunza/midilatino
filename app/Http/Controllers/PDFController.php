<?php

namespace App\Http\Controllers;

use App\Models\Licenses;
use App\Models\Products;
use App\Models\ViewsLog;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class PDFController extends Controller
{

    public function license_preview($product_id)
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '120');
        $data = array();


        $file_title = 'preview.pdf';

        $product = Products::where('product_id', $product_id)->first();

        if (!empty($product)) {

            $license = Licenses::where('id', $product->license_id)->first();

            if (!empty($license)) {


                //{{customer.name}} {{customer.email}} {{product.name}} {{order.id}}
                $data['content'] = $license->content;
                $data['content'] = str_replace("{{customer.name}}", "John Doe", $data['content']);
                $data['content'] = str_replace("{{customer.email}}", "johndoe@midilatino.com", $data['content']);
                $data['content'] = str_replace("{{product.name}}", $product->title, $data['content']);
                $data['content'] = str_replace("{{order.id}}", '1234567890', $data['content']);

                $pdf = PDF::loadView('previewPDF', $data)->setPaper('letter', 'portrait');
                return $pdf->stream($file_title);
            }
        }
    }

    public function license($product_id, $order_id, $unixtimestamp)
    {
        if($unixtimestamp < (time() - 80000)){
            return;
        }

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', '120');
        $data = array();

        $file_title = 'license_' . $order_id . $product_id . '.pdf';

        $product = Products::where('product_id', $product_id)->first();

        if (!empty($product)) {

            $license = Licenses::where('id', $product->license_id)->first();

            if (!empty($license)) {

                $shopify = new Shopify();

                $order = $shopify->get_order($order_id);
                $json_order = json_decode($order);
                //dd($json_order);
                if (!empty($json_order->order)) {

                    $in_order = false;
                    foreach($json_order->order->line_items as $item){
                        if($item->product_id == $product_id){
                            $in_order = true;
                        }
                    }

                    if(!$in_order){
                        return;
                    }

                    $customer = $shopify->get_customer($json_order->order->customer->id);
                    $json_customer = json_decode($customer);

                    
                    if (!empty($json_customer->customer)) {

                        $views_log = new ViewsLog();
                        $views_log->customer_id = $json_customer->customer->id;
                        $views_log->customer_name = $json_customer->customer->first_name . ' ' . $json_customer->customer->last_name;
                        $views_log->customer_email = $json_customer->customer->email;
                        $views_log->license_id = $license->id;
                        $views_log->order_id = $order_id;
                        $views_log->product_id = $product_id;
                        $views_log->type = 'license_view';
                        $views_log->save();


                        //{{customer.name}} {{customer.email}} {{product.name}} {{order.id}}
                        $data['content'] = $license->content;
                        $data['content'] = str_replace("{{customer.name}}", $json_customer->customer->first_name . ' ' . $json_customer->customer->last_name, $data['content']);
                        $data['content'] = str_replace("{{customer.email}}", $json_customer->customer->email, $data['content']);
                        $data['content'] = str_replace("{{product.name}}", $product->title, $data['content']);
                        $data['content'] = str_replace("{{order.id}}", $order_id, $data['content']);

                        $pdf = PDF::loadView('previewPDF', $data)->setPaper('letter', 'portrait');
                        return $pdf->stream($file_title);
                    }
                }
            }
        }
    }
}
