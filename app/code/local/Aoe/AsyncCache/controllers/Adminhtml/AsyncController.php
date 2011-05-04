<?php

/**
 * Async controller
 * 
 * @author Fabrizio Branca
 */
class Aoe_AsyncCache_Adminhtml_AsyncController extends Mage_Adminhtml_Controller_Action {
	
	/**
	 * Process the queue
	 * This action is called from a button in the "Cache Management" module.
	 * Afterwards it redirects back to that module
	 * 
	 * @return void
	 */
	public function processAction() {
		$processedJobs = Mage::getModel('aoeasynccache/cleaner')->processQueue();
		foreach ($processedJobs as $job) {
			$this->_getSession()->addSuccess(Mage::helper('adminhtml')->__('Cleared cache with mode "%s" using tags "%s" (Duration: %s sec)', $job['mode'], implode(', ',$job['tags']), $job['duration']));
		}
		$this->_redirect('*/cache/index');
	}
	
	/**
	 * Delete a async entry
	 * This action is called from a button in the "Cache Management" module.
	 * Afterwards it redirects back to that module
	 * 
	 * @return void
	 */
	public function deleteAction() {
		$id = $this->getRequest()->getParam('id');
		$async = Mage::getModel('aoeasynccache/asynccache')->load($id);
		$async->setStatus('deleted');
		$async->setProcessed(time());
		$async->save();
		$this->_getSession()->addSuccess(Mage::helper('adminhtml')->__('Deleted item "%s"', $id));
		$this->_redirect('*/cache/index');
	}
	
}

