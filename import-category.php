<?php

require __DIR__ . '/app/bootstrap.php';
class ZeoApp
	extends \Magento\Framework\App\Http
	implements \Magento\Framework\AppInterface 
	{
	
	
	public function execute(){
	    $objectManager =   \Magento\Framework\App\ObjectManager::getInstance();
	     
	    
	    $cats = [
	        "cat1","cat2"
	   ];
	    
	    $parent_id = 322;
	    foreach($cats as $cat) {
    	    $data = [
    	        'data' => [
    	            "parent_id" => $parent_id,
    	            'name' => $cat,
    	            "is_active" => true,
    	            "position" => 10,
    	            "include_in_menu" => false,
    	        ]
    	        
    	     ];
    	    $category = $objectManager ->create('Magento\Catalog\Model\Category', $data);
    	    $repository = $objectManager->get(\Magento\Catalog\Api\CategoryRepositoryInterface::class);
    	    $result = $repository->save($category);
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