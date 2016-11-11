<?php
require __DIR__ . '/app/bootstrap.php';
class ZeoApp
	extends \Magento\Framework\App\Http
	implements \Magento\Framework\AppInterface 
	{
	
	
	public function execute(){
	    $objectManager =   \Magento\Framework\App\ObjectManager::getInstance();
	    
    	    $collection = $objectManager ->create('Magento\Catalog\Model\Category')->getCollection();
    	    $collection->addAttributeToSelect("name");
    	    $collection->addAttributeToFilter("parent_id",417);
    	    $collection->setOrder("name","asc");
    	    foreach ($collection as $item) {
    	        print $item->getEntityId().",".$item->getName()."<br/>";
    	    }
	    
	    
	}
	public function launch()
	{
        $this->execute();
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