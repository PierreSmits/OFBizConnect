<?php
class OFBiz_Magentoext_Model_Magento_Store_Api extends Mage_Api_Model_Resource_Abstract {
    public function address($store) {
        $storeAddressInfo = array();

        $storeAddressInfo['store_name'] = Mage::getStoreConfig('general/store_information/name', $store);
        $storeAddressInfo['contact_number'] = Mage::getStoreConfig('general/store_information/phone', $store);
        $storeAddressInfo['country'] = Mage::getStoreConfig('general/store_information/merchant_country', $store);
        $storeAddressInfo['address'] = Mage::getStoreConfig('general/store_information/address', $store);
        $storeAddressInfo['email_address'] = Mage::getStoreConfig('trans_email/ident_general/email');
        return $storeAddressInfo;
    }

    public function shippingMethods($store) {
        $carrierAndAllowedMethods = array();
        $shippingOptions = array();
        $carriers = Mage::getSingleton('shipping/config')->getAllCarriers();
        foreach ($carriers as $carrierCode=>$carrierModel) {
            $path = 'carriers/'.$carrierCode;
            $carrierAndAllowedMethods['allowedMethods'] = Mage::getStoreConfig($path.'/allowed_methods', $store);
            $carrierAndAllowedMethods['active'] = Mage::getStoreConfig($path.'/active', $store);
            $carrierAndAllowedMethods['username'] = Mage::getStoreConfig($path.'/username', $store);
            $carrierAndAllowedMethods['password'] = Mage::getStoreConfig($path.'/password', $store);
            $carrierAndAllowedMethods['gatewayUrl'] = Mage::getStoreConfig($path.'/gateway_url', $store);
            $carrierAndAllowedMethods['gatewayXmlUrl'] = Mage::getStoreConfig($path.'/gateway_xml_url', $store);
            $carrierAndAllowedMethods['trackingXmlUrl'] = Mage::getStoreConfig($path.'/tracking_xml_url', $store);
            $carrierAndAllowedMethods['shipConfirmXmlUrl'] = Mage::getStoreConfig($path.'/shipconfirm_xml_url', $store);
            $carrierAndAllowedMethods['shipAcceptXmlUrl'] = Mage::getStoreConfig($path.'/shipaccept_xml_url', $store);
            $carrierAndAllowedMethods['accessLicenseNumber'] = Mage::getStoreConfig($path.'/access_license_number', $store);
            $carrierAndAllowedMethods['gatewaySecureUrl'] = Mage::getStoreConfig($path.'/gateway_secure_url', $store);
            $carrierAndAllowedMethods['userId'] = Mage::getStoreConfig($path.'/userid', $store);
            $carrierAndAllowedMethods['account'] = Mage::getStoreConfig($path.'/account', $store);
            $carrierAndAllowedMethods['meterNumber'] = Mage::getStoreConfig($path.'/meter_number', $store);
            $carrierAndAllowedMethods['key'] = Mage::getStoreConfig($path.'/key', $store);
            $carrierAndAllowedMethods['id'] = Mage::getStoreConfig($path.'/id', $store);
            $carrierAndAllowedMethods['carrierCode'] = $carrierCode;
            $shippingOptions[] = $carrierAndAllowedMethods;
        }
        return $shippingOptions;
      }

      public function currency($store) {
          $currency = array();
          $currency['default'] = Mage::getStoreConfig('currency/options/default', $store);
          $currency['base'] = Mage::getStoreConfig('currency/options/base', $store);
          return $currency;
      }
      
      public function shippingOrigin($store) {
          $shippingOrigin = array();
          $shippingOrigin['country_id'] = Mage::getStoreConfig('shipping/origin/country_id', $store);

          $regionId = Mage::getStoreConfig('shipping/origin/region_id', $store);
          $regionModel = Mage::getModel('directory/region/')->load($regionId);
          $shippingOrigin['region_code'] = $regionModel->getCode();

          $shippingOrigin['postcode'] = Mage::getStoreConfig('shipping/origin/postcode', $store);
          $shippingOrigin['city'] = Mage::getStoreConfig('shipping/origin/city', $store);
          $shippingOrigin['street_line1'] = Mage::getStoreConfig('shipping/origin/street_line1', $store);
          $shippingOrigin['street_line2'] = Mage::getStoreConfig('shipping/origin/street_line2', $store);
          return $shippingOrigin;
      }

      public function config() {
        $config = array();
        $storeConfig = array();
        $websites = Mage::app()->getWebsites();
        foreach ($websites as $website) {
            $websiteId = $website->getWebsiteId();
            $website = Mage::app()->getWebsite($websiteId);
            $storeGroups = $website->getGroups();
            foreach ($storeGroups as $storeGroup) {
                $defaultStoreId = $storeGroup->getDefaultStoreId();
                $store = Mage::app()->getStore($defaultStoreId);
                $storeConfig['website_id'] = $websiteId;
                $storeConfig['default_store_id'] = $store->getId();
                $storeConfig['group_id'] = $storeGroup->getId();
                $storeConfig['store_group_name'] = $storeGroup->getName();
                $storeConfig['root_category_id'] = $storeGroup->getRootCategoryId();
                $storeConfig['code'] = $store->getCode();
                $storeConfig['name'] = $store->getName();
                $storeConfig['is_active'] = $store->getIsActive();
                $storeConfig['shipping_origin'] = $this->shippingOrigin($store);
                $storeConfig['currency'] = $this->currency($store);
                $storeConfig['shipping_methods'] = $this->shippingMethods($store);
                $storeConfig['address'] = $this->address($store);
                $config[] = $storeConfig;
            }
        }
        return $config;
      }
}
