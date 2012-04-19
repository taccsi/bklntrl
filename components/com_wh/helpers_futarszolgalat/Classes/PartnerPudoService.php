<?php

if (!class_exists("PartnerPudoService")) {

class PartnerPudoService extends SoapClient {
	
	private static $classmap = array(
		"RegisterShipment" => "RegisterShipment",
		"RegisterShipmentResponse" => "RegisterShipmentResponse",
		"RegisterReturningPackages" => "RegisterReturningPackages",
		"RegisterReturningPackagesResponse" => "RegisterReturningPackagesResponse",
		"char" => "char",
		"duration" => "duration",
		"guid" => "guid",
		"RegisterShipmentRequest" => "RegisterShipmentRequest",
		"RegisterReturningPackagesRequest" => "RegisterReturningPackagesRequest",
		"Package" => "Package",
		"PackageResult" => "PackageResult",
		"CustomerType" => "CustomerType",
		"PackageType" => "PackageType",
		"Supplier" => "Supplier",
	);

	
	public function __construct($wsdl, $options=array()) {
		foreach(self::$classmap as $wsdlClassName => $phpClassName) {
		    if(!isset($options['classmap'][$wsdlClassName])) {
		        $options['classmap'][$wsdlClassName] = $phpClassName;
		    }
		}
		parent::__construct($wsdl, $options);
	}

	
	public function _checkArguments($arguments, $validParameters) {
		$variables = "";
		foreach ($arguments as $arg) {
		     $type = gettype($arg);
		    if ($type == "object") {
		        $type = get_class($arg);
		    }
		    $variables .= "(".$type.")";
		}
		if (!in_array($variables, $validParameters)) {
         
		    throw new Exception("Hibás paraméter típus: ".str_replace(")(", ", ", $variables));
		}
		return true;
	}

	
	public function RegisterShipment($mixed = null) {
		$validParameters = array(
			"(RegisterShipment)",
		);
		$args = func_get_args();
		//print_r($args); die();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("RegisterShipment", $args);
	}


	
	public function RegisterReturningPackages($mixed = null) {
		$validParameters = array(
			"(RegisterReturningPackages)",
		);
		$args = func_get_args();
	//	print_r($args); die();
		$this->_checkArguments($args, $validParameters);
		return $this->__soapCall("RegisterReturningPackages", $args);
	}


}}

?>