<?php
defined('BASEPATH') OR exit("Access Denied");//remove this line if not using with CodeIgniter

/**
 * Description of Test
 *
 * @author Amir <amirsanni@gmail.com>
 * @date 20-Dec-2016
 */
class Test extends CI_Controller{
    public function __construct(){
        parent::__construct();
        
        $this->load->library('paystack', [
            'secret_key'=>'sk_test_58caf76164c50ae6c7ff9c89a2369d67b74bea3a', 
            'public_key'=>'pk_test_6ecaf53e98d465a523aaec2a2f1a202c47e7015e']);
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * Initialise a transaction by getting only the authorised URL returned
     */
    public function getAuthURL(){
        //init($ref, $amount_in_kobo, $email, $metadata_arr=[], $callback_url="", $return_obj=false)
        $url = $this->paystack->init("SKY09D24E10", 20000, "amirsanni@gmail.com", [
            'name'=>"Amir Olalekan",
            'ID'=>"AMS10",
            "Phone"=>"07011111111"
        ], base_url('test/callback'), FALSE);
        
        //$url ? header("Location: {$url}") : "";
        $url ? redirect($url) : "";
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * Initialise a transaction by getting the whole array returned back instead of just the authorised URL
     */
    public function initTransaction(){
        //init($ref, $amount_in_kobo, $email, $metadata_arr=[], $callback_url="", $return_obj=false)
        $response_arr = $this->paystack->init("FRTTUFD24E10", 20000, "amirsanni@gmail.com", [
            'name'=>"Amir Olalekan",
            'ID'=>"AMS10",
            "Phone"=>"07011111111"
        ], base_url('test/callback'), TRUE);
        
        if($response_arr){
            redirect($response_arr->data->authorization_url);
        }
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * Initialise subscription to a predefined plan and get just the AUTH_URL returned
     */
    public function getPlanAuthURL(){
        //initSubscription($amount_in_kobo, $email, $plan, $metadata_arr=[], $callback_url="", $return_obj=false)
        $url = $this->paystack->init(20000, "amirsanni@gmail.com", "full_d4q", [], base_url('test/callback'), FALSE);
        
        //$url ? header("Location: {$url}") : "";
        $url ? redirect($url) : "";
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    /**
     * Initialise subscription to a predefined plan and get the whole initialisation array returned
     */
    public function subscribe(){
        //initSubscription($amount_in_kobo, $email, $plan, $metadata_arr=[], $callback_url="", $return_obj=false)
        $response_arr = $this->paystack->init(20000, "amirsanni@gmail.com", "full_d4q", [], base_url('test/callback'), TRUE);
        
        if($response_arr){
            redirect($response_arr->data->authorization_url);
        }
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    public function verify($ref){
        //verifyTransaction($ref) will return an array of verification details or FALSE on failure
        $ver_info = $this->paystack->verifyTransaction($ref);
        
        //do something if verification is successful e.g. save authorisation code
        if($ver_info && ($ver_info->status = TRUE) && ($ver_info->data->status == "success")){
            $auth_code = $ver_info->data->authorization->authorization_code;
            
            //do something with $auth_code. $auth_code can be used to charge the customer next time
            echo $auth_code;
        }
        
        else{
            //do something else
        }
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    public function callback(){
        $trxref = $this->input->get('trxref', TRUE);
        $ref = $this->input->get('reference', TRUE);
        
        //do something e.g. verify the transaction
        if($trxref === $ref){
            $this->verify($trxref);
        }
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    public function chargeReturningCust(){
        $amount = 500000;
        
        //chargeReturningCustomer($auth_code, $amount_in_kobo, $email, $ref="", $metadata_arr=[])
        $response = $this->paystack->chargeReturningCustomer("AUTH_mvdfipt5", $amount, "amirsanni@gmail.com", "", [
            'first_name'=>"Amir",
            'last_name'=>"Sanni",
            'Phone'=>"07086201801"
        ]);
        
        //do something if charge is successful
        if($response && ($response->status = TRUE) && ($response->data->status == "success") && ($response->data->amount == $amount)){
            //do something
        }
        
        else{
            //do something else
        }
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
    
    public function createCustomer(){
        //createCustomer($email, $first_name='', $last_name='', $phone='', $meta=[])
        print_r($this->paystack->createCustomer("johndoe@abc.xyz", "John", "Doe", "08010000111", [
            'gender'=>"Male", "age"=>26, "marital_status"=>"Single"
        ]));
    }
    
    /*
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    ********************************************************************************************************************************
    */
}