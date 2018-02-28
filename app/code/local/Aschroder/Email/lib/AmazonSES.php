<?php

/**
 * Amazon Simple Email Service (SES) connection object
 *
 * Integration between Zend Framework and Amazon Simple Email Service
 *
 * @category    Zend
 * @package     Zend_Mail
 * @subpackage  Transport
 * @author      Christopher Valles <info@christophervalles.com>
 * @license     http://framework.zend.com/license/new-bsd New BSD License
 */
class App_Mail_Transport_AmazonSES extends Zend_Mail_Transport_Abstract
{
    /**
     * Template of the webservice body request
     *
     * @var string
     */
    protected $_bodyRequestTemplate = 'Action=SendRawEmail&Source=%s&%s&RawMessage.Data=%s';


    /**
     * Remote smtp hostname or i.p.
     *
     * @var string
     */
    protected $_host;


    /**
     * Amazon Access Key
     *
     * @var string|null
     */
    protected $_accessKey;


    /**
     * Amazon private key
     *
     * @var string|null
     */
    protected $_privateKey;

    private $endpoints = array(
        'US-EAST-1' => 'email.us-east-1.amazonaws.com',
        'US-WEST-2' => 'email.us-west-2.amazonaws.com',
        'EU-WEST-1' => 'email.eu-west-1.amazonaws.com',
    );


    /**
     * Constructor.
     *
     * @param  array|null $config (Default: null)
     * @param  string $host (Default: https://email.us-east-1.amazonaws.com)
     * @return void
     * @throws Zend_Mail_Transport_Exception if accessKey is not present in the config
     * @throws Zend_Mail_Transport_Exception if privateKey is not present in the config
     */
    public function __construct(Array $config = array(), $region = 'US-EAST-1')
    {
        if(!array_key_exists('accessKey', $config)){
            throw new Zend_Mail_Transport_Exception('This transport requires the Amazon access key');
        }
        
        if(!array_key_exists('privateKey', $config)){
            throw new Zend_Mail_Transport_Exception('This transport requires the Amazon private key');
        }
        
        $this->_accessKey = $config['accessKey'];
        $this->_privateKey = $config['privateKey'];
        $this->setRegion($region);
    }

    public function setRegion($region) {
        if(!isset($this->endpoints[$region])) {
            throw new InvalidArgumentException('Region unrecognised');
        }
        return $this->_host = Zend_Uri::factory("https://" . $this->endpoints[$region]);
    }

    /**
     * Send an email using the amazon webservice api
     *
     * @return void
     */
    public function _sendMail()
    {
        $date = gmdate('D, d M Y H:i:s O');
        
        //Send the request
        $client = new Zend_Http_Client($this->_host);
        $client->setMethod(Zend_Http_Client::POST);
        $client->setHeaders(array(
            'Date' => $date,
            'X-Amzn-Authorization' => $this->_buildAuthKey($date)
        ));
        
        //Build the parameters
        $params = array(
            'Action' => 'SendRawEmail',
            'Source' => $this->_mail->getFrom(),
            'RawMessage.Data' => base64_encode(sprintf("%s\n%s\n", $this->header, $this->body))
        );
        
        $recipients = explode(',', $this->recipients);
        while(list($index, $recipient) = each($recipients)){
            $params[sprintf('Destinations.member.%d', $index + 1)] = $recipient;
        }
        
        $client->resetParameters();
        $client->setEncType(Zend_Http_Client::ENC_URLENCODED);
        $client->setParameterPost($params);
        $response = $client->request(Zend_Http_Client::POST);
        
        if($response->getStatus() != 200){
            throw new Exception($response->getBody());
        }
    }

    public function getSendStats()
    {
        $date = gmdate('D, d M Y H:i:s O');

        //Send the request
        $client = new Zend_Http_Client($this->_host);
        $client->setMethod(Zend_Http_Client::POST);
        $client->setHeaders(array(
            'Date' => $date,
            'X-Amzn-Authorization' => $this->_buildAuthKey($date)
        ));

        //Build the parameters
        $params = array(
            'Action' => 'GetSendStatistics'
        );

        $client->resetParameters();
        $client->setEncType(Zend_Http_Client::ENC_URLENCODED);
        $client->setParameterPost($params);
        $response = $client->request(Zend_Http_Client::POST);

        if($response->getStatus() != 200){
            throw new Exception($response->getBody());
        }

        return $response->getBody();
    }


    /**
     * Format and fix headers
     *
     * Some SMTP servers do not strip BCC headers. Most clients do it themselves as do we.
     *
     * @access  protected
     * @param   array $headers
     * @return  void
     * @throws  Zend_Transport_Exception
     */
    protected function _prepareHeaders($headers)
    {
        if (!$this->_mail) {
            /**
             * @see Zend_Mail_Transport_Exception
             */
            throw new Zend_Mail_Transport_Exception('_prepareHeaders requires a registered Zend_Mail object');
        }
        
        unset($headers['Bcc']);
        
        // Prepare headers
        parent::_prepareHeaders($headers);
    }


    /**
     * Returns header string containing encoded authentication key
     * 
     * @param   date $date
     * @return  string
     */
    private function _buildAuthKey($date){
        return sprintf('AWS3-HTTPS AWSAccessKeyId=%s,Algorithm=HmacSHA256,Signature=%s', $this->_accessKey, base64_encode(hash_hmac('sha256', $date, $this->_privateKey, TRUE)));
    }
}