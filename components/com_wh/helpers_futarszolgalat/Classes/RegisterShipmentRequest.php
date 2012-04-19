<?php

if (!class_exists("RegisterShipmentRequest")) {

class RegisterShipmentRequest {
	
	public $ArriveDate;
	
	public $IsDirectShipment;
	
	public $IsSupplementData;
	
	public $Packages=array();
	
	public $PartnerAddress;

	public $PartnerCode;
	
	public $ShipmentId;
	
	public $Supplier;
}}
?>
