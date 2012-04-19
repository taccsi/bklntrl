<html>
<head>
    <title>Test Package - BIG FISH Payment Gateway</title>
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
                <img src="https://www.paymentgateway.hu/images/website/logo_paymentgateway.png" border="0"/>
                <div id="greenline"></div><br/>
                <div id="topcontent">
                    <div id="inside">
                        <div id="inside-content">
                            <div>
                                <h1>BIG FISH Payment Gateway Test Package</h1>
                                <div>By the help of the attached files you can test the functionality of Payment Gateway and can easily integrate it into your system. Instead of massive documentation try it and look into the code!</div>
                                <br/>
                                <h2><a href="test_start.php">Start a test transaction</a></h2>
                            </div>
                            <div>
                                <h2><a href="test_close.php">Close a pending test transaction</a></h2>
                            </div>
                            <div>
                                The following files can be found in the package:
                                <ul>
                                    <li><span><b>index.php:</b> This file.</span></li>
                                    <li><span><b>test_start.php:</b> It is good for starting a test transaction. You will use it only during the testing period, it is not needed in a production environment, but the function calls have to be built into your page.</span></li>
                                    <li><span><b>test_response.php:</b> The response about test transactions arrives to here from Payment Gateway. You will use it only during the testing period, it is not needed in a production environment, but the function calls have to be built into your page.</span></li>
                                    <li><span><b>test_data.txt:</b> Credit card numbers and other test data.</span></li>
                                    <li><span><b>config.php:</b> It contains the settings for you. Feel free to look into it. <b>You will need it in a production environment, so it will be copied to a folder under your website.</b></span></li>
                                    <li><span><b>paymentgateway.php:</b> PaymentGateway class implements the communication with the Payment Gateway system. <b>You will need it in a production environment, so it will be copied to a folder under your website.</b></span></li>
                                </ul>
                                <a href="http://test.paymentgateway.hu/technikai_informaciok.html" target="_blank">A description of the transaction process and the paramteres can be found here (hungarian).</a>
                                <br/><br/>
                                <a href="http://translate.google.com/translate?hl=en&sl=hu&tl=en&u=http://test.paymentgateway.hu/technikai_informaciok.html" target="_blank">A description of the transaction process and the paramteres can be found here (Google translation).</a>
                            </div>
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