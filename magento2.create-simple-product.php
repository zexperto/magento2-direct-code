<?php 
// configuration Data
$url="http://www.example.com/index.php/";
$token_url=$url."rest/V1/integration/admin/token";
$product_url=$url. "rest/V1/products";
$username="your admin username";
$password="your admin password";



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

$sampleProductData = array(
        'sku'               => 'config_product-small-red',
        'name'              => 'Simple Product ' . uniqid(),
        'visibility'        => 4, /*'catalog',*/
        'type_id'           => 'simple',
        'price'             => 99.95,
        'status'            => 1,
        'attribute_set_id'  => 4,
        'weight'            => 1,
        'custom_attributes' => array(
            array( 'attribute_code' => 'category_ids', 'value' => ["42","41","32"] ),
            array( 'attribute_code' => 'description', 'value' => 'Simple Description' ),
            array( 'attribute_code' => 'short_description', 'value' => 'Simple Short Description' ),
        	array( 'attribute_code' => 'size', 'value' => '5' ), //5=small
        	array( 'attribute_code' => 'color', 'value' => '8' ), //8-red
            ),
		'extension_attributes' => array(
				"stock_item"=>array(
						"qty"=>10,
						"is_in_stock"=>true,
				),
		
		)
    );
$productData = json_encode(array('product' => $sampleProductData));

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
