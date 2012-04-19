<?php
require_once "paymentgateway.php";

$paymentgatewayErrorMessage = "";
$paymentgatewayResponseMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
	$paymentgateway = new PaymentGateway();
	/**
	 * Close transaction
	 */
	$responseArray = array();
    $responseArray = $paymentgateway->close(
        $_POST["TransactionId"], //Transaction ID
        $_POST["Approved"] //approve or decline (true/false)
    );

    if ($responseArray['ResultCode'] == "SUCCESSFUL" && $responseArray['TransactionId'])
    {
		/**
		 * If successful feedback is received from the Payment Gateway system,
         * the transaction was successfully closed.
         * To store the results to your database!
		 */
        $paymentgatewayErrorMessage = "";
    	$paymentgatewayResponseMessage = "CLOSE ".$responseArray["ResultCode"];
    	$paymentgatewayResponseMessage.= "<br/><br/><xmp>".print_r($responseArray, true)."</xmp>";
        
        $responseArray = $paymentgateway->result($responseArray["TransactionId"]);
        
    	$paymentgatewayResponseMessage.= "<br/><br/><xmp>".print_r($responseArray, true)."</xmp>";

    }
	else
	{
		/**
		 * If an error occurred, the error message should be displayed for the customer.
		 */
    	$paymentgatewayErrorMessage = $responseArray["ResultCode"].": ".$responseArray["ResultMessage"];
        $paymentgatewayErrorMessage.= "<br/><br/><xmp>".print_r($responseArray, true)."</xmp>";
	}
}
?>
<html>
<head>
    <title>Close a pending test transaction - BIG FISH Payment Gateway</title>
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
                            <form method="post">
                            <div>
                                <h1>Close a pending test transaction</h1>
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
                                    Transaction ID:<br/><input type="text" name="TransactionId" value="<?=$_REQUEST["TransactionId"];?>"/>
                                    <br/><br/>
                                    Authorization:<br/>
                                    <select name="Approved">
                                        <option value="true">Approve</option>
                                        <option value="false">Decline</option>
                                    </select>
                                    <br/><br/>
                                    <input type="submit" name="submit" value="Submit"/>
                                    <br/><br/>
                                    
                                    <h2><a href="test_start.php">Start a new test transaction</a></h2>
                                    <? if ($responseArray["AutoCommit"] == "false" && $responseArray["CommitState"] == "PENDING"): ?>
                                        <h2><a href="test_close.php?TransactionId=<?=$responseArray["TransactionId"];?>">Close the transaction</a></h2>
                                    <? endif ?>
                                    <br/>
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