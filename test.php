<?php 
 

use Magento\Framework\App\Bootstrap;
require __DIR__ . '/app/bootstrap.php';

$bootstrap = Bootstrap::create(BP, $_SERVER);

$obj = $bootstrap->getObjectManager();



$state = $obj->get('Magento\Framework\App\State');
$state->setAreaCode('frontend');
$store = $obj->get('Magento\Store\Model\StoreManagerInterface')->getStore();

// $quote = $obj->get('Magento\Checkout\Model\Session')->getQuote()->load(1);
// print_r($quote->getOrigData());

/** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $productCollection */
$productCollection = $obj->create('Magento\Catalog\Model\ResourceModel\Product\Collection');
/** Apply filters here */
$collection = $productCollection->addAttributeToSelect('*')
            ->load();
$imagehelper = $obj->create('Magento\Catalog\Helper\Image');
$i = 0;




header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="facebook.csv"');
$data = array(
        'id,title,description,availability,condition,price,link,image_link,brand,google_product_category',
);



 foreach ($collection as $product){


	if($product->getPrice()!="" or $product->getPrice() > 0){


		$imageUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' . $product->getImage();

        // if($product->getId()=="3730"){

        //     echo $product->getPriceInfo()->getPrice('final_price')->getMaximalPrice()->getValue();

        //     exit;
        //    }


		array_push($data,
        ''.$product->getId().','.$product->getName().','.str_replace(","," ",$product->getDescription()).',in stock,new,'.$product->getPriceInfo()->getPrice('final_price')->getMaximalPrice()->getValue().' CAD'.','.$product->getProductUrl().','.$imageUrl.',Andrea Blais Jewellery,Jewellery'
);

}
		  

     ?>


    
 <?php
      
if ($i++ > 7000) break;
     
}    








$fp = fopen('php://output', 'wb');
foreach ( $data as $line ) {
    $val = explode(",", $line);
    fputcsv($fp, $val);
}
fclose($fp);



