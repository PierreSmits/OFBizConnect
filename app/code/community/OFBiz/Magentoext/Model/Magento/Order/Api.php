<?php
class OFBiz_Magentoext_Model_Magento_Order_Api extends Mage_Api_Model_Resource_Abstract {
    public function editSalesOrderAddress($orderIncrementId, $orderData) {
        if($orderIncrementId) {
            $order = Mage::getModel('sales/order')->load($orderIncrementId, 'increment_id');
            if ($order && $orderData) {
                try {
                    $address_type = $orderData->address_type;
                    if($address_type == "Shipping") {
                        $address = $order->getShippingAddress();
                    } else {
                        $address = $order->getBillingAddress();
                    }
                    $address->setFirstname($orderData->firstname)
                            ->setLastname($orderData->lastname)
                            ->setStreet(array($orderData->address1, $orderData->address2))
                            ->setCity($orderData->city)
                            ->setPostcode($orderData->postcode)
                            ->setRegion($orderData->region_id)
                            ->setCountryId($orderData->country_id)
                            ->setTelephone($orderData->telephone)
                            ->setFax($orderData->fax)
                            ->save();
                    return true;
                } catch (Mage_Core_Exception $e) {
                    $this->_getSession()->addError($e->getMessage());
                } catch (Exception $e) {
                    $this->_getSession()->addException(
                        Mage::helper('sales')->__('An error occurred while updating the order address. The address has not been changed.')
                    );
                }
            }
        }
        return false;
    }
}