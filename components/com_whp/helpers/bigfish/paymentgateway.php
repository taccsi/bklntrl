<?php
/*
 * Version: 1.5.4
 */
require_once(dirname(__FILE__).'/config.php');

if ( ! function_exists('curl_init'))
{
    die("Error! The CURL PHP module is not loaded!");
}

if ( ! function_exists('openssl_public_encrypt'))
{
    die("Error! The OpenSSL PHP module is not loaded!");
}

class PaymentGateway
{
    var $storeName;
    var $testMode;
    var $gatewayUrl;
    var $responseArray;
    var $curlResponse;
    var $curlStatus;
    var $curlInfo;
    var $curlErrno;
    var $curlError;

    function __construct($testMode = BIGFISH_PAYMENTGATEWAY_TEST_MODE)
    {
        $this->storeName = BIGFISH_PAYMENTGATEWAY_STORE_NAME;
        $this->testMode = $testMode;
        
        if ($this->testMode === true)
        {
            $this->gatewayUrl = "http://test.paymentgateway.hu";
            if ($_SERVER["HTTP_HOST"] == "www.work") {
                $this->gatewayUrl = "http://paymentgateway.work";
            }
        }
        else
        {
            $this->gatewayUrl = "https://www.paymentgateway.hu";
        }
    }

    function init($providerName, $responseUrl, $amount, $orderId="", $userId="", $currency="", $language="",
        $mppPhoneNumber="", $otpCardNumber="", $otpExpiration="", $otpCvc="", $autoCommit = true)
    {
        $urldecodedResponseUrl = urldecode($responseUrl);
        if ($responseUrl == $urldecodedResponseUrl)
        {
            $responseUrl = urlencode($responseUrl);
        }

        $initUrl = $this->gatewayUrl."/Init?ProviderName=".$providerName
            ."&StoreName=".$this->storeName
            ."&Amount=".$amount
            ."&Currency=".($currency ? $currency : 'HUF')
            ."&Language=".($language ? $language : 'HU')
            ."&OrderId=".$orderId
            ."&UserId=".$userId
            ."&ResponseUrl=".$responseUrl
            ."&ResponseMode=UriString"
            ."&MppPhoneNumber=".$mppPhoneNumber
            ."&AutoCommit=".$autoCommit
            ;

        if ($providerName == "OTP2" && !empty($otpCardNumber) && !empty($otpExpiration) && !empty($otpCvc))
        {
            $extra = "|".$otpCardNumber."|".$otpExpiration."|".$otpCvc."|";
            openssl_public_encrypt($extra, $encrypted, BIGFISH_PAYMENTGATEWAY_OTP_PUBLIC_KEY);
            $initUrl.="&Extra=".  $this->urlsafe_b64encode($encrypted);
        }
        
        $this->makeCurl($initUrl);

        $responseArray = array();
        
        if (!is_array($this->responseArray) ||
            sizeof($this->responseArray)==0)
        {
            $responseArray['ResultCode'] = "ERROR";
            $responseArray['ResultMessage'] = "Error! The answer is not adequate: ".$this->curlResponse;
            return $responseArray;
        }
        if (array_key_exists("TransactionId", $this->responseArray) &&
            $this->responseArray["TransactionId"])
        {
            $responseArray = $this->responseArray;
            return $responseArray;
        }
        elseif (array_key_exists("ResultCode", $this->responseArray) &&
            $this->responseArray["ResultCode"])
        {
            $responseArray = $this->responseArray;
            return $responseArray;
        }
        else
        {
            $responseArray['ResultCode'] = "ERROR";
            $responseArray['ResultMessage'] = $this->curlResponse;
            return $responseArray;
        }
    }

    function start($transactionId)
    {
        $startUrl = $this->gatewayUrl."/Start?TransactionId=".$transactionId;

        header("Location: ".$startUrl);
        exit;
    }

    function result($transactionId, $responseMode = "UriString")
    {
        $responseArray = array();

        if (empty($transactionId))
        {
            $responseArray['ResultCode'] = "ERROR";
            $responseArray['ResultMessage'] = "Missing parameter: Transaction ID!";
            return $responseArray;
        }

        $resultUrl = $this->gatewayUrl."/Result?TransactionId=".$transactionId."&ResponseMode=".$responseMode;

        $this->makeCurl($resultUrl);

        if (!is_array($this->responseArray) || sizeof($this->responseArray)==0)
        {
            $responseArray['ResultCode'] = "ERROR";
            $responseArray['ResultMessage'] = "Error! The answer is not adequate: ".$this->curlResponse;
            return $responseArray;
        }
        if (array_key_exists("ResultCode", $this->responseArray) && $this->responseArray["ResultCode"])
        {
            $responseArray = $this->responseArray;
            return $responseArray;
        }
        else
        {
            $responseArray['ResultCode'] = "ERROR";
            $responseArray['ResultMessage'] = $this->curlResponse;
            return $responseArray;
        }
    }

    function close($transactionId, $approved = true)
    {
        $responseArray = array();

        if (empty($transactionId))
        {
            $responseArray['ResultCode'] = "ERROR";
            $responseArray['ResultMessage'] = "Missing parameter: Transaction ID!";
            return $responseArray;
        }

        $closeUrl = $this->gatewayUrl."/Close?TransactionId=".$transactionId."&Approved=".$approved."&ResponseMode=UriString";

        $this->makeCurl($closeUrl);

        if (!is_array($this->responseArray) || sizeof($this->responseArray)==0)
        {
            $responseArray['ResultCode'] = "ERROR";
            $responseArray['ResultMessage'] = "Error! The answer is not adequate: ".$this->curlResponse;
            return $responseArray;
        }
        if (array_key_exists("ResultCode", $this->responseArray) && $this->responseArray["ResultCode"])
        {
            $responseArray = $this->responseArray;
            return $responseArray;
        }
        else
        {
            $responseArray['ResultCode'] = "ERROR";
            $responseArray['ResultMessage'] = $this->curlResponse;
            return $responseArray;
        }
    }

    function getResponseText()
    {
        return $this->curlResponse;
    }

    function makeCurl($url)
    {
        $curl = new MyCurl($url);
        $curl->createCurl();

        $this->curlResponse = trim(urldecode($curl->getResponse()));
        $this->curlStatus = $curl->getHttpStatus();
        $this->curlInfo = $curl->curlInfo;

        if($this->curlResponse === false)
        {
            $this->curlErrno = $curl->getErrno();
            $this->curlError = $curl->getError();
            return false;
        }
        else
        {
            if (BIGFISH_PAYMENTGATEWAY_OUT_CHARSET != "UTF-8")
            {
                $this->curlResponse = iconv("UTF-8", BIGFISH_PAYMENTGATEWAY_OUT_CHARSET, $this->curlResponse);
            }
            $responseArrayTmp = explode("&", $this->curlResponse);
            $responseArray = array();
            if (is_array($responseArrayTmp) && count($responseArrayTmp) > 0)
            {
                foreach ($responseArrayTmp as $key => $val)
                {
                    $tmp = explode("=", $val);
                    if (is_array($tmp) && array_key_exists(1, $tmp))
                    {
                        $responseArray[$tmp[0]] = $tmp[1];
                    }
                }
            }
            $this->responseArray = $responseArray;
        }
    }

    private function urlsafe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_','.'),$data);
        return $data;
    }
}

class MyCurl
{
    var $_useragent;
    var $_url;
    var $_followlocation;
    var $_timeout;
    var $_maxRedirects;
    var $_post;
    var $_postFields;
    var $_referer;
    var $_port;

    var $_session;
    var $_webpage;
    var $_includeHeader;
    var $_noBody;
    var $_status;
    var $_binaryTransfer;

    var $_errno;
    var $_error;
    var $curlInfo;

    function MyCurl($url,$followlocation = true,$timeOut = 30,$maxRedirecs = 4,$binaryTransfer = false,$includeHeader = false,$noBody = false)
    {
        $this->_url = $url;
        $this->_useragent = 'BIG FISH Payment Gateway Client ('.$_SERVER['HTTP_HOST'].')';
        $this->_referer = $_SERVER['HTTP_HOST'];
        $this->_followlocation = $followlocation;
        $this->_timeout = $timeOut;
        $this->_maxRedirects = $maxRedirecs;
        $this->_noBody = $noBody;
        $this->_includeHeader = $includeHeader;
        $this->_binaryTransfer = $binaryTransfer;
    }

    function setReferer($referer)
    {
        $this->_referer = $referer;
    }

    function setPost ($postFields)
    {
        $this->_post = true;
        $this->_postFields = $postFields;
    }

    function setUserAgent($userAgent)
    {
        $this->_useragent = $userAgent;
    }

    function setPort($port)
    {
        $this->_port = $port;
    }

    function createCurl()
    {
        $s = curl_init();

        curl_setopt($s,CURLOPT_URL,$this->_url);
        curl_setopt($s,CURLOPT_HTTPHEADER,array('Except:'));
        curl_setopt($s,CURLOPT_TIMEOUT,$this->_timeout);
        curl_setopt($s,CURLOPT_MAXREDIRS,$this->_maxRedirects);
        curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($s, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);

        if($this->_post)
        {
            curl_setopt($s,CURLOPT_POST, true);
            curl_setopt($s,CURLOPT_POSTFIELDS, $this->_postFields);
        }

        if($this->_port)
        {
            curl_setopt($s, CURLOPT_PORT, $this->_port);
        }

        if($this->_includeHeader)
        {
            curl_setopt($s,CURLOPT_HEADER,true);
        }

        if($this->_noBody)
        {
            curl_setopt($s,CURLOPT_NOBODY,true);
        }
        if($this->_binaryTransfer)
        {
            curl_setopt($s,CURLOPT_BINARYTRANSFER,true);
        }

        curl_setopt($s,CURLOPT_USERAGENT,$this->_useragent);
        curl_setopt($s,CURLOPT_REFERER,$this->_referer);

        $this->_webpage = curl_exec($s);
        $this->_status = curl_getinfo($s,CURLINFO_HTTP_CODE);

        $this->curlInfo = curl_getinfo($s);

        if($this->_webpage === false)
        {
            $this->_errno = curl_errno($s);
            $this->_error = curl_error($s);
            curl_close($s);
            return false;
        }

        curl_close($s);
        return true;
    }


    function getHttpStatus()
    {
        return $this->_status;
    }

    function getError()
    {
        return $this->_error;
    }

    function getErrno()
    {
        return $this->_errno;
    }

    function getResponse()
    {
        return $this->_webpage;
    }

    function __tostring()
    {
        return $this->_webpage;
    }

}

?>