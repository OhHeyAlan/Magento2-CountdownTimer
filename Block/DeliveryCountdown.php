<?php 
namespace Harrigo\DeliveryCountdown\Block;

class DeliveryCountdown extends \Magento\Framework\View\Element\Template 
{
	protected $scopeConfig;
	
	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
		$this->scopeConfig = $scopeConfig;
		parent::__construct(
            $context
        );
    }
	
	public function getDeliveryDate() {
		$deliverydaysadmin = $this->scopeConfig->getValue('deliverycountdown/general/deliverytime', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$currenttime = $this->getCurrentTime();
		$deliverydate = date('d/m/Y', strtotime(date('d-m-Y') . ' + ' . $deliverydaysadmin . ' weekdays'));
		//if ($currenttime >= $this->getCufOffTime()) {
			//$deliverydate+= 86400;
		//}
		return $deliverydate;
	}
	
	public function getCutOffTime() {
		$cuttoffadmin = $this->scopeConfig->getValue('deliverycountdown/delivery/cutofftimemon', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$currenttime = $this->getCurrentTime();
		$cutofftime = strtotime(date('h:i:s A', strtotime(date('d-m-Y') . ' + ' . $cuttoffadmin . ' hours - 0 minutes')));
		//if ($currenttime>$cutofftime) {
			//$cutofftime+= 86400;
		//}
		return $cutofftime;
	}
	
	public function getTimeRemaining() {
		$interval = date('H:i:s', mktime(0, 0, $this->getCufOffTime() - $this->getCurrentTime()));
		return $interval;
	}
	
	public function getCurrentTime() {
		$currenttime = strtotime(date("h:i:s A"));
		return $currenttime;
	}
	
	public function getTimeRemainingSeconds() {
		return $interval = $this->getCutOffTime() - $this->getCurrentTime();
	}
	
	public function buildString() {
		$string = $this->scopeConfig->getValue('deliverycountdown/general/string', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
		$string = str_replace("{{delivery_date}}",'<span id="date">' . $this->getDeliveryDate() . "</span>",$string);
		$string = str_replace("{{time_remaining}}",'<span id="time">' . $this->getTimeRemaining() . "</span>",$string);
		return $string;
	}
	
	public function excludeDays() {
		//want to use https://www.gov.uk/bank-holidays.json to get bank holidays for uk/bank-holidays
		//will create an array for other countries and slowly add api for large countries. USA etc.
		
	}
	
} 

// need to check if cutoff include excludedays.

//issues need to get correct initial time left

//need to check delivery date includes exclude days
