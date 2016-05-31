<?php
require __DIR__ . '/app/bootstrap.php';
class ZeoApp
	extends \Magento\Framework\App\Http
	implements \Magento\Framework\AppInterface 
	{
	
	public function launch()
	{
        $this->_state->setAreaCode("frontend");
        
        $increment_id="2000000008";
        $items=array();
        $items[]=array("order_item_id"=>21,"qty"=>1);
        
        // Invoice Data
        $data = array ();
        $data["increment_id"]=$increment_id;
        $data ["items"]=array();
        foreach ($items as $item){
        	$data["items"][$item["order_item_id"]]=$item["qty"];
        }
        $data ["comment_text"] = "Comment by Zeo";
        $data ['comment_customer_notify']=true;
        $data ['is_visible_on_front']=true;
	
        $order = $this->_objectManager->create ( '\Magento\Sales\Model\Order' );
        $order->loadByIncrementId ( $data ["increment_id"] );
        
        $itemsArray = $data ["items"];
        
        		
       $invoice = $this->_objectManager->create ( 'Magento\Sales\Model\Service\InvoiceService' )->prepareInvoice ( $order, $itemsArray );
			$invoice->register ();
			$invoice->addComment ( $data ['comment_text'], isset ( $data ['comment_customer_notify'] ), isset ( $data ['is_visible_on_front'] ) );
			$invoice->save ();
			$transactionSave = $this->_objectManager->create ( 'Magento\Framework\DB\Transaction' )->addObject ( $invoice )->addObject ( $invoice->getOrder () );
			$transactionSave->save ();
			
      echo $invoice->getIncrementId();
        
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