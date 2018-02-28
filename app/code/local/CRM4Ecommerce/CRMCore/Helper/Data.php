<?php

/**
 * Helper Data
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_CRMCore
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_CRMCore_Helper_Data extends Mage_Core_Helper_Abstract {

    const CRM4ECOMMERCE_NOTIFICATION_DESTINATION = 'http://www.crm4ecommerce.com/index.php/adminmessagenotificationserver/';
    const CRM4ECOMMERCE_NOTIFICATION_CONFIG_KEY = 'crm4ecommerce_adminmessagenotification/message_id';

    public function clearAllRegisterInformation($module) {
        try {
            $module = 'crm4ecommerce_' . strtolower($module);
            $resource = Mage::getSingleton('core/resource');
            $write = $resource->getConnection('core_write');
            $sql = "DELETE FROM `" . $resource->getTableName('core_config_data') . "` WHERE `path` LIKE '$module/general/%'";
            $write->query($sql);
            if (method_exists(Mage::app(),'getCacheInstance')) {
                Mage::app()->getCacheInstance()->cleanType('config');
            } else {
                Mage::app()->cleanCache('config');
            }
        } catch (Exception $e) {
            return false;
        }
        return true;
    }

    public function getModuleVersion($module) {
        $xml = new Zend_Config_Xml(Mage::getBaseDir() . DS . 'app' . DS . 'code' . DS . 'local' . DS . 'CRM4Ecommerce' . DS . $module . DS . 'etc' . DS . 'config.xml');
        $module = 'CRM4Ecommerce_' . $module;
        return $xml->modules->$module->version;
    }

    public function getModuleInstalledVersion($module) {
        $module = strtolower('CRM4Ecommerce_' . $module);
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');
        $select = 'SELECT `version` FROM `' . $resource->getTableName('core_resource') . '` WHERE `code` = \'' . $module . '_setup\'';
        $rs = $read->fetchAll($select);
        $version = array();
        foreach ($rs as $row) {
            $version = $row;
            break;
        }
        return $version['version'];
    }

    public function getStoreConfig($path, $admin = true) {
        if ($admin) {
            $resource = Mage::getSingleton('core/resource');
            $read = $resource->getConnection('core_read');
            $select = $read->select()->from($resource->getTableName('core_config_data'))->where('path = ?', $path)
                    ->where('scope_id = ?', 0);
            $rs = $read->fetchAll($select);
            foreach ($rs as $row) {
                return $row['value'];
            }
            return '';
        } else {
            Mage::getConfig()->reinit();
            return Mage::getStoreConfig($path);
        }
    }
    
    public function isColumnExisted($tableName, $columnName) {
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');
        $rs = $read->fetchAll('SHOW COLUMNS FROM ' . $resource->getTableName($tableName));
        $existed = false;
        foreach ($rs as $row) {
            foreach ($row as $k => $v) {
                if ($v == $columnName) {
                    $existed = true;
                    break;
                }
            }
        }
        return $existed;
    }

    public function isTableExisted($tableName) {
        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');
        $rs = $read->fetchAll('SHOW TABLES');
        $existed = false;
        $tableNameWithPrefix = $resource->getTableName($tableName);
        foreach ($rs as $row) {
            foreach ($row as $k => $v) {
                if ($v == $tableName || $v == $tableNameWithPrefix) {
                    $existed = true;
                    break;
                }
            }
        }
        return $existed;
    }

    public function loadNotification() {
        try {
            $url = self::CRM4ECOMMERCE_NOTIFICATION_DESTINATION;
            $message_id = Mage::getStoreConfig(self::CRM4ECOMMERCE_NOTIFICATION_CONFIG_KEY);
            if ($message_id != '') {
                $url .= 'index/index/id/' . $message_id;
            }
            $parameter = "";
            $response = $this->sendCurlRequest($url, $parameter);

            $json = json_decode($response);
            $json = $json->melonkat;
            $messages = $json->message;

            if (count($messages)) {
                foreach ($messages as $message) {
                    Mage::getModel('adminnotification/inbox')
                            ->setTitle($message->title)
                            ->setDescription($message->description)
                            ->setUrl($message->url)
                            ->setSeverity($message->severity)
                            ->setDateAdded($message->date_added)
                            ->save();
                    $message_id = $message->id;
                }

                Mage::getSingleton('core/config')->saveConfig(
                        self::CRM4ECOMMERCE_NOTIFICATION_CONFIG_KEY, $message_id
                );
                Mage::app()->cleanCache(array('CONFIG'));
            }
        } catch (Exception $e) {
            
        }
    }

    public function isCommunity() {
        $modules = array_keys((array) Mage::getConfig()->getNode('modules')->children());
        foreach ($modules as $module) {
            if (is_int(strpos($module, 'Enterprise_'))) {
                return false;
            }
        }
        return true;
    }

    public function setParameter($key, $value, $parameter) {
        $parameter[$key] = $value;
        return $parameter;
    }

    public function sendCurlRequest($url, $parameter) {
        try {
            $config = null;
            if (strpos('url: ' . $url, 'https://') > 0) {
                $config = array(
                    'adapter' => 'Zend_Http_Client_Adapter_Socket',
                    'ssltransport' => 'tls'
                );
            } else {
                $config = array(
                );
            }

            $client = new Zend_Http_Client($url, $config);
            foreach ($parameter as $param => $value) {
                $client->setParameterPost($param, $value);
            }
            $response = $client->request(Zend_Http_Client::POST);

            if ($response->getStatus() == 404) {
                throw new Exception(Mage::helper('crmcore')->__("Error %s: System can't post data to url %s.", $response->getStatus(), $url));
            } else {
                return $response->getBody();
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }

}