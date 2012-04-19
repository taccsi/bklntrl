<?php
require_once "paymentgateway.php";

$paymentgatewayErrorMessage = "";
$amount = rand(10, 100);
$orderId = "order".date("YmdHis");
$userId = "user".date("YmdHis");

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	/**
	 * Compile response URL.
     * Payment Gateway will send back the purchaser to this address after the payment.
	 */
	$responseUrl = (($_SERVER["HTTPS"] == "on") ? "https" : "http")."://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
	$responseUrl = str_replace("test_start.php", "test_response.php", $responseUrl);
	
	$paymentgateway = new PaymentGateway();
	
	/**
	 * Initializing transaction
	 */
	$responseArray = array();
    $responseArray = $paymentgateway->init(
        $_POST["provider"], //provider (mandatory)
        $responseUrl, //response URL (mandatory)
        $_POST["amount"], //amount of money (mandatory)
        $_POST["orderId"], //order ID in your system (optional, but recommended)
        $_POST["userId"], //user ID in your system (optional, but recommended)
        $_POST["currency"], //currency (optional, default value: HUF)
        $_POST["language"], //language (optional, default value: HU)
        "", //MPP Phone Number (not used)
        $_POST["OtpCardNumber"], //card number (optional, only used with OTP two party payment)
        $_POST["OtpExpiration"], //expiration date (optional, only used with OTP two party payment)
        $_POST["OtpCvc"], //CVC code (optional, only used with OTP two party payment)
        $_POST["AutoCommit"] //automatic charge or just block the money (optional, default value: true)
    );

    if ($responseArray['ResultCode'] == "SUCCESSFUL" && $responseArray['TransactionId'])
    {
		/**
		 * If successful feedback is received from the Payment Gateway system, the purchaser can be sent to the payment page.
		 * IMPORTANT! Before you call the Start method, save / store the Transaction ID received from Payment Gateway to your database!
		 */
        $paymentgateway->start($responseArray["TransactionId"]);
    }
	else
	{
		/**
		 * If an error occurred during the initialization, the error message should be displayed for the customer.
		 */
    	$paymentgatewayErrorMessage = $responseArray["ResultCode"].": ".$responseArray["ResultMessage"];
        $paymentgatewayErrorMessage.= "<br/><br/><xmp>".print_r($responseArray, true)."</xmp>";
	}
}
?>
<html>
<head>
    <title>Start a test transaction - BIG FISH Payment Gateway</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="BIG FISH Internet - www.bigfish.hu" />
    <link href="https://www.paymentgateway.hu/css/website/main.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="https://www.paymentgateway.hu/css/website/subpage.css" rel="stylesheet" type="text/css" media="screen" />
    <script type="text/javascript">
        function changeProvider(element)
        {
            var opt2div = document.getElementById("Otp2Div");
            opt2div.style.display = "none";
            if (element.value == "OTP2")
            {
                opt2div.style.display = "block";
            }
        }
    </script>
</head>
<body>
<div id="wrapper">
    <div id="owner">
        <div id="inner">
            <div id="innercontent">
                <a href="index.php"><img src="https://www.paymentgateway.hu/images/website/logo_paymentgateway.png" border="0"/></a>
                <div id="greenline"></div><br/>
                <div id="topcontent">
                    <div id="inside">
                        <div id="inside-content">
                            <form method="post">
                            <div>
                                <h1>Start a test transaction</h1>
                                    <?php
                                    if (!empty($paymentgatewayErrorMessage))
                                    {
                                        echo '<div style="background-color:#FFFFFF;color:#FF0000;padding:10px 5px 10px 5px;">';
                                        echo $paymentgatewayErrorMessage;
                                        echo '</div>';
                                    }
                                    ?>
                                    Amount:<br/><input type="text" name="amount" value="<?=$amount;?>"/>
                                    <select name="currency">
                                        <option value="HUF">HUF</option>
                                        <option value="EUR">EUR</option>
                                        <option value="USD">USD</option>
                                    </select>
                                    <br/><br/>
                                    Provider:<br/>
                                    <select name="provider" onchange="changeProvider(this);">
                                        <option value="CIB">CIB Bank</option>
                                        <option value="KHB">K&H Bank</option>
                                        <option value="OTP">OTP Bank</option>
                                        <option value="OTP2">OTP Bank (two party)</option>
                                        <option value="Abaqoos">Abaqoos</option>
                                        <option value="MPP">MobilePayment</option>
                                        <option value="PayPal">PayPal</option>
                                        <option value="SMS">SMS</option>
                                    </select>
                                    <br/><br/>
                                    Order ID in your system:<br/>
                                    <input type="text" name="orderId" value="<?=$orderId;?>"/>
                                    <br/><br/>
                                    User ID in your system:<br/>
                                    <input type="text" name="userId" value="<?=$userId;?>"/>
                                    <br/><br/>
                                    Language:<br/>
                                    <select name="language">
                                        <option value="EN">English</option>
                                        <option value="HU">Hungarian</option>
                                        <option value="DE">German</option>
                                    </select>
                                    <br/><br/>
                                    Immediate charge:<br/>
                                    <select name="AutoCommit">
                                        <option value="true">Yes</option>
                                        <option value="false">No, later</option>
                                    </select>
                                    <br/><br/>
                                    <div id="Otp2Div" style="display: none;">
                                        Card number:<br/>
                                        <input type="text" name="OtpCardNumber" value="5016253399000013"/>
                                        <br/><br/>
                                        Expiration date (mmyy):<br/>
                                        <input type="text" name="OtpExpiration" value="0404"/>
                                        <br/><br/>
                                        CVC/CVV verification code:<br/>
                                        <input type="text" name="OtpCvc" value="111"/>
                                        <br/><br/>
                                    </div>
                                    <input type="submit" name="submit" value="Submit"/>
                                    <br/><br/>

                                    Credit card numbers and other test data can be found in <a href="test_data.txt" target="_blank">test_data.txt</a>.

                                    <br/><br/>

                                    <a href="index.php">&laquo; Back to main page</a>
                            </div>
                            </form>
                        </div>
                    </div>
                    <div id="inside-footer"><!-- --></div>
                </div>
            </div>
                <div id="footer" style="margin-left:-100px">
                    <div id="footer_text">
						&copy; BIG FISH Internet Technology Ltd.
                    </div>
                </div>
        </div>
    </div>
</div>
</body>
</html>