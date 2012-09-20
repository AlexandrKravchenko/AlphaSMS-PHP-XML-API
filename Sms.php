<?php
    if(!defined('SMS_KEY'))
    {
        define('SMS_KEY', ''); //put your sms key here
    }
    
    /*
     * Sms api for https://alphasms.com.ua/storage/files/AlphaSMS_XML_v1.3.pdf
     * */
    class Sms
    {
        private $url = 'https://alphasms.com.ua/api/xml.php';
        private $key = '';
        private $curl;
        
        public function __construct($key = SMS_KEY)
        {
            $this->key = $key;
            $this->curl = curl_init($this->url);
        }
        
        public function __destruct()
        {
            curl_close($this->curl);
        }
        
        /*
         * Examples:
         *  $results = $sms->send(array(
         *      'id' => 123,
         *      'recipient' => '+79021234567',
         *      'sender' => 'PHPScript',
         *      'date_beg' => '2009-12-27T15:55',
         *      'date_end' => '2010-12-28T15:55',
         *      'url' => 'http://www.url.com/"',
         *      'type' => 0,
         *      'text' => 'Hello World!'
         *  ));
         *
         *  OR, multiple:
         *  $results = $sms->send(array(
         *      'id' => 123,
         *      'recipient' => '+79021234567',
         *      'sender' => 'PHPScript',
         *      'date_beg' => '2009-12-27T15:55',
         *      'date_end' => '2010-12-28T15:55',
         *      'url' => 'http://www.url.com/"',
         *      'type' => 0,
         *      'text' => 'Hello World!'
         *  ), array(
         *      'id' => 123,
         *      'recipient' => '+79021234567',
         *      'sender' => 'PHPScript',
         *      'date_beg' => '2009-12-27T15:55',
         *      'date_end' => '2010-12-28T15:55',
         *      'url' => 'http://www.url.com/"',
         *      'type' => 0,
         *      'text' => 'Hello World!'
         *  ));
         * */
        public function send($data = array())
        {
            $messages = array();
            if(!empty($data['recipient'])) //Make one sms to multiple style
            {
                $data = array($data);
            }
            
            foreach($data as $sms)
            {
                $text = $sms['text'];
                unset($sms['text']);
                $messages[] = $this->_tag('msg', $text, $sms);
            }
            
            return $this->_post('<?xml version="1.0" encoding="utf-8"?><package key="' . 
                $this->key . '"><message>' . implode("\n", $messages) . '</message></package>');
        }
        
        /*
         * Examples:
         *  $results = $sms->status(array(
         *      'id' => 123
         *  ));
         *  $results = $sms->status(array(
         *      'sms_id' => 1546423
         *  ));
         *
         *  OR, multiple:
         *  $results = $sms->send(array(
         *      'id' => 123
         *  ), array(
         *      'sms_id' => 54654653
         *  ), array(
         *      'id' => 5123
         *  ));
         * */
        public function status($data = array())
        {
            $statuses = array();
            if(!empty($data['id']) || !empty($data['sms_id'])) //Make to multiple style
            {
                $data = array($data);
            }
            
            foreach($data as $sms)
            {
                $statuses[] = $this->_tag('msg', null, $sms);
            }
            
            return $this->_post('<?xml version="1.0" encoding="utf-8"?><package key="' . 
                $this->key . '"><status>' . implode("\n", $statuses) . '</status></package>');
        }
        
        private function _post($data)
        {
            curl_setopt($this->curl, CURLOPT_MUTE, true);
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($this->curl, CURLOPT_POST, true);
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
            return json_decode(json_encode(simplexml_load_string(curl_exec($this->curl))), true);
        }
        
        private function _tag($name, $text = '', $attrs = array())
        {
            $A = "";
            if(count($attrs))
            {
                $ats = array();
                foreach($attrs as $key => $val)
                {
                    $ats[] = $key . '="' . $val . '"';
                }
                $A = " " . implode(" ", $ats);
            }
            if(empty($text) && $text === null)
            {
                return "<" . $name . $A .  "/>";
            }
            else
            {
                return "<" . $name . $A .  ">" . $text . "<" . $name . ">";
            }
        }
    }
?>
