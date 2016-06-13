<?php 
// configuration Data
$url="http://www.example.com/index.php/";
$token_url=$url."rest/V1/integration/admin/token";
$product_url=$url. "rest/V1/products";
$username="your admin username";
$password="your admin password";

$children_ids=array(196);

/*
 * configurable_product_options: add the attributes info with options
 */


//Authentication rest API magento2, get access token
$ch = curl_init();
$data = array("username" => $username, "password" => $password);
$data_string = json_encode($data);

$ch = curl_init($token_url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data_string))
    );
$token = curl_exec($ch);
$adminToken=  json_decode($token);

//////////////////////////////////////////////////////////////////////////////////////////////////////////

// Create the Configurable Product
$configProductData = array(
		'sku'               => 'Config_product',
		'name'              => 'Configurable' . uniqid(),
		'visibility'        => 4, /*'catalog',*/
		'type_id'           => 'configurable',
		'price'             => 99.95,
		'status'            => 1,
		'attribute_set_id'  => 4,
		'weight'            => 1,
		'custom_attributes' => array(
				array( 'attribute_code' => 'category_ids', 'value' => ["42","41","32"] ),
				array( 'attribute_code' => 'description', 'value' => 'Description' ),
				array( 'attribute_code' => 'short_description', 'value' => 'Short Description' )//,
		),
		'extension_attributes' => array(
				"configurable_product_options"=>array(
												array("attribute_id"=>90,"label"=>"color","position"=>"0","values"=>array(array("value_index"=>8),array("value_index"=>9))),
												array("attribute_id"=>131,"label"=>"size","position"=>"0","values"=>array(array("value_index"=>5),array("value_index"=>4))),
												),
				"configurable_product_links"=>$children_ids,
				
		)
		
);
$productData = json_encode(array('product' => $configProductData));
$setHaders = array('Content-Type:application/json','Authorization:Bearer '.$adminToken);

$ch = curl_init();
curl_setopt($ch,CURLOPT_URL, $product_url);
curl_setopt($ch,CURLOPT_POSTFIELDS, $productData);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_HTTPHEADER, $setHaders);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
var_dump($response);
