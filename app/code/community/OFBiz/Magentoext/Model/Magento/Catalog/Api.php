<?php
class OFBiz_Magentoext_Model_Magento_Catalog_Api extends Mage_Api_Model_Resource_Abstract {
    public function getProductRelation($productId) {
        $resultList = array();
        Mage::log("=======inside getProductRelation productId===========".$productId);
        $product = Mage::getModel('catalog/product')->load($productId);
        if($product) {
            $connection = Mage::getSingleton('core/resource')->getConnection('core_read');
            $sql = "Select * from catalog_product_relation where child_id={$productId}";
            $rows = $connection->fetchAll($sql);
            foreach($rows as $val) {
                $result = [];
                $result['parent_id'] = $val['parent_id'];
                $result['product_id'] = $val['child_id'];
                $parentProduct = Mage::getModel('catalog/product')->load($val['parent_id']);
                $result['parent_type'] = $parentProduct->getTypeID();
                array_push($resultList, $result);
            }
            Mage::log($resultList, null, 'system.log');
        }
        return $resultList;
    }
}