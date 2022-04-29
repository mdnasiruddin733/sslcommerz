<?php
namespace Webpane\SSLcommerz;


class SSLcommerz{
    
    
    private $payment_url=[
        "sandbox"=> "https://sandbox.sslcommerz.com/gwprocess/v4/api.php",
        "live"=>"https://securepay.sslcommerz.com/gwprocess/v4/api.php",
    ];

    private $refund_url=[
        "sandbox"=>"https://sandbox.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php",
        "live"=>"https://securepay.sslcommerz.com/validator/api/merchantTransIDvalidationAPI.php",
    ];

    private $validate_url=[
        "sandbox"=>"https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php",
        "live"=>"https://securepay.sslcommerz.com/validator/api/validationserverAPI.php",
    ];

    public function pay($data){

        $data['success_url'] = url(config('sslcommerz.success_url'));
        $data['fail_url'] = url(config('sslcommerz.fail_url'));
        $data['cancel_url'] = url(config('sslcommerz.cancel_url'));
        $data['currency']=config('sslcommerz.currency');
        $data['product_profile']=config('sslcommerz.product_profile');
        $data['shipping_method']=config('sslcommerz.shipping_method');
        $data['multi_card_name']=config('sslcommerz.multi_card_name');
        $data['product_category']=config('sslcommerz.product_category');
        $direct_api_url="";

        $payment_mode=!array_key_exists(config('sslcommerz.mode'),$this->payment_url)? "sandbox" :config('sslcommerz.mode');
        $direct_api_url =$this->payment_url[$payment_mode];
        
        $sslcommerzResponse=$this->makeRequest($data,$direct_api_url);
        

        # PARSE THE JSON RESPONSE
        $sslcz = json_decode($sslcommerzResponse, true );


        if(isset($sslcz['GatewayPageURL']) && $sslcz['GatewayPageURL']!="") {
            
            return redirect($sslcz['GatewayPageURL']);

        }else {
            return  json_encode(['status' => 'fail', 'data' => null, 'message' => "JSON Data parsing error!"]);
        }
    }

    private function makeRequest($data,$url){
        $data['store_id']=config('sslcommerz.store_id');
        $data['store_passwd']=config('sslcommerz.store_passwd');
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url );
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($handle, CURLOPT_POST, 1 );
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, config("sslcommerz.from_localhost"));
        $content = curl_exec($handle );
        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            if($code == 200 && !( curl_errno($handle))) {
                curl_close( $handle);
                return $content;
            } else {
                curl_close( $handle);
                die("FAILED TO CONNECT WITH SSLCOMMERZ API");
                
            }
    }


    public function refund($data){
        
            $bank_tran_id=urlencode($data['bank_tran_id']);
            $refund_amount=urlencode($data['refund_ammount']);
            $refund_remarks=urlencode($data['refund_remarks']);
            $store_id=urlencode(config('sslcommerz.store_id'));
            $store_passwd=urlencode(config('sslcommerz.store_passwd'));

            $payment_mode=!array_key_exists(config('sslcommerz.mode'),$this->refund_url)? "sandbox" :config('sslcommerz.mode');
            $requested_url = ($this->refund_url[$payment_mode]."?refund_amount=$refund_amount&refund_remarks=$refund_remarks&bank_tran_id=$bank_tran_id&store_id=$store_id&store_passwd=$store_passwd&v=1&format=json");

            $handle = curl_init();
            curl_setopt($handle, CURLOPT_URL, $requested_url);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false); # IF YOU RUN FROM LOCAL PC
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); # IF YOU RUN FROM LOCAL PC

            $result = curl_exec($handle);

            $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

            if($code == 200 && !( curl_errno($handle)))
            {
                $result = json_decode($result);
                $APIConnect = $result->APIConnect;
                return $result;
            } else {

                die("Failed to connect with SSLCOMMERZ");
            }
    }


    public function validate($data){
        $val_id=urlencode($data["val_id"]);
        $store_id=urlencode(config('sslcommerz.store_id'));
        $store_passwd=urlencode(config('sslcommerz.store_passwd'));
        $payment_mode=!array_key_exists(config('sslcommerz.mode'),$this->validate_url)? "sandbox" :config('sslcommerz.mode');
        $requested_url = ($this->validate_url[$payment_mode]."?val_id=".$val_id."&store_id=".$store_id."&store_passwd=".$store_passwd."&v=1&format=json");

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $requested_url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false); # IF YOU RUN FROM LOCAL PC
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false); # IF YOU RUN FROM LOCAL PC

        $result = curl_exec($handle);

        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if($code == 200 && !( curl_errno($handle)))
        {
            # TO CONVERT AS OBJECT
            $result = json_decode($result);

            # API AUTHENTICATION
            $APIConnect = $result->APIConnect;
            return $result;
            
        } else {

            echo "Failed to connect with SSLCOMMERZ";
        }
    }

    public function test(){
        return "Just testing";
    }
}
