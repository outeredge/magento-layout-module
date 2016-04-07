<?php
class Edge_Layout_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getLayoutContents($groupName = false, $type = false)
    {
        $groups  = Mage::getModel('layout/layout_groups');
        $idGroup = $groups->getGroupIdByName($groupName);
        if (!$idGroup) {
            return null;
        }

        $layout  = Mage::getModel('layout/layout_elements');
        $result  = $layout->loadByType($idGroup->getId(), $type);
        $grouped = $this->groupBy($result->getData(), 'type');
        $data    = $this->getVarienDataCollection($grouped);

        return $data;
    }

    protected function groupBy($array, $key)
    {
        $return = array();
        foreach($array as $val) {
            $return[$val[$key]][] = $val;
        }
        return $return;
    }

    protected function getVarienDataCollection($groups)
    {
        $home = new Varien_Object();
	foreach ($groups as $name => $items) {
            $home[$name] = new Varien_Data_Collection();
            foreach ($items as $item) {
                $varienObject = new Varien_Object();
                $varienObject->setData($item);
                $home[$name]->addItem($varienObject);
            }
    	}
	return $home;
    }

}