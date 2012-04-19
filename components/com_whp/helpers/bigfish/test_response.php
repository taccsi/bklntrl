<?php
require_once "paymentgateway.php";

$paymentgatewayErrorMessage = "";
$paymentgatewayResponseMessage = "";

if ( ! array_key_exists("TransactionId", $_GET))
{
	$paymentgatewayErrorMessage = "Nem érkezett tranzakció azonosító!";
}
else
{
    $paymentgateway = new PaymentGateway();

	/**
	 * Query transaction results
	 */
	$responseArray = array();
    $responseArray = $paymentgateway->result($_GET["TransactionId"]);

    if ($responseArray['ResultCode'] == "SUCCESSFUL")
    {
		/**
		 * If successful feedback is received from the Payment Gateway system,
         * the transaction was successful, the order can be completed.
         * To store the results to your database!
		 */
        $paymentgatewayErrorMessage = "";
    	$paymentgatewayResponseMessage = "<b>".$responseArray["ResultMessage"]."</b>";
    	$paymentgatewayResponseMessage.= "<br/>Transaction ID: <b>".$responseArray["ProviderTransactionId"]."</b>";
    	$paymentgatewayResponseMessage.= "<br/>Authorization number: <b>".$responseArray["Anum"]."</b>";
        $paymentgatewayResponseMessage.= "<br/><br/><xmp>".print_r($responseArray, true)."</xmp>";
    }
    else
    {
		/**
		 * If an error occurred during the payment, the error message should be displayed for the customer.
		 */
    	$paymentgatewayErrorMessage = $responseArray["ResultCode"].": ".$responseArray["ResultMessage"];
    	$paymentgatewayErrorMessage.= "<br/><br/><xmp>".print_r($responseArray, true)."</xmp>";
    }
}
?>
<html>
<head>
    <title>Test transaction response - BIG FISH Payment Gateway</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="BIG FISH Internet - www.bigfish.hu" />
    <link href="https://www.paymentgateway.hu/css/website/main.css" rel="stylesheet" type="text/css" media="screen" />
    <link href="https://www.paymentgateway.hu/css/website/subpage.css" rel="stylesheet" type="text/css" media="screen" />
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
                            <div>
                                <h1>Test transaction response</h1>
                                <?php
                                if (!empty($paymentgatewayErrorMessage))
                                {
                                    echo '<div style="background-color:#FFFFFF;color:#FF0000;padding:10px 5px 10px 5px;">';
                                    echo $paymentgatewayErrorMessage;
                                    echo '</div>';
                                }
                                if (!empty($paymentgatewayResponseMessage))
                                {
                                    echo '<div style="background-color:#FFFFFF;color:#9BB02A;padding:10px 5px 10px 5px;">';
                                    echo $paymentgatewayResponseMessage;
                                    echo '</div>';
                                }
                                ?>
                                <br/>
                                <h2><a href="test_start.php">Start a new test transaction</a></h2>
                                <? if ($responseArray["AutoCommit"] == "false" && $responseArray["CommitState"] == "PENDING"): ?>
                                    <h2><a href="test_close.php?TransactionId=<?=$responseArray["TransactionId"];?>">Close the transaction</a></h2>
                                <? endif ?>
                                <br/>
                                <a href="index.php">&laquo; Back to main page</a>
                            </div>
                        </div>
                    </div>
                    <div id="inside-footer"><!-- --></div>
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