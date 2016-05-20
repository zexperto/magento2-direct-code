<?php
require __DIR__ . '/app/bootstrap.php';
class ZeoApp
	extends \Magento\Framework\App\Http
	implements \Magento\Framework\AppInterface 
	{
	protected $shipmentLoader;
	
	public function launch()
	{
        $this->_state->setAreaCode("frontend");
        $this->shipmentLoader=$this->_objectManager->create('\Magento\Shipping\Controller\Adminhtml\Order\ShipmentLoader');
        
        $increment_id="2000000008";
        $items=array();
        $items[]=array("order_item_id"=>21,"qty"=>1);
        
        // Shipment Data
        $data = array ();
        $data["increment_id"]=$increment_id;
        $data ["items"]=array();
        foreach ($items as $item){
        	
        	$data["items"][$item["order_item_id"]]=$item["qty"];
        }
       /* $data ["items"] = array (
        		"21" => 1
        );
        */
        $data ["comment_text"] = "Comment by Zeo";
        $data ['comment_customer_notify']=true;
        $data ['comment_customer_notify']=true;
        $data ['is_visible_on_front']=true;
        $data ['tracks']=array(
        		array("carrier_code"=>"custom","title"=>"Carrier Title","number"=>"10")
        		);
        		
        $tracks=$data["tracks"];
        
        $order = $this->_objectManager->create ( '\Magento\Sales\Model\Order' );
        $order->loadByIncrementId ( $data["increment_id"] );
        $order_id = $order->getEntityId ();
			$this->shipmentLoader->setOrderId ( $order_id );
			$this->shipmentLoader->setShipmentId ( null );
			$this->shipmentLoader->setShipment ( $data );
			$this->shipmentLoader->setTracking($tracks);
        
			$shipment = $this->shipmentLoader->load ();
			
			if (! empty ( $data ['comment_text'] )) {
				$shipment->addComment ( $data ['comment_text'], isset ( $data ['comment_customer_notify'] ), isset ( $data ['is_visible_on_front'] ) );
			
				$shipment->setCustomerNote ( $data ['comment_text'] );
				$shipment->setCustomerNoteNotify ( isset ( $data ['comment_customer_notify'] ) );
			}
	
			$shipment->register ();
			$transaction = $this->_objectManager->create ( 'Magento\Framework\DB\Transaction' );
			$transaction->addObject ( $shipment )->addObject ( $shipment->getOrder () )->save ();
			
			
      echo $shipment->getIncrementId();
        
        
		//the method must end with this line
		return $this->_response;
	}

	public function catchException(\Magento\Framework\App\Bootstrap $bootstrap, \Exception $exception)
	{
		return false;
	}

}
// Start your App
$bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
$app = $bootstrap->createApplication('ZeoApp');
$bootstrap->run($app);