<?php
class Kwanso_Getresponse_Model_Cron
{	
	static public function importContacts() 
	{
		$checkEnabled = Mage::getStoreConfig('getresponse/general/enable');

		if($checkEnabled) {
			$getResponseConfig = unserialize(Mage::getStoreConfig('getresponse/general/campains'));
		
			if($getResponseConfig) {
				foreach ($getResponseConfig as $key => $value) {

					$getResponse = new Kwanso_Getresponse_Model_Getresponse($value['key']);
					$getResponse->enterprise_domain = 'gr360.cpscompany.com';
					$getResponse->api_url = 'https://api3.getresponse360.com/v3'; //for PL domains
					
					$campains = $getResponse->getCampaigns();

					foreach ($campains as $key => $campain) {
						if($value["name"] == $campain->name) {
							$campainId = $campain->campaignId;
						}
					}

					Mage::log("Step 1 - Get Response Key ".$value['key']." Worked", null, 'getresponse_'.$campainId.'.log');

					if(!$campainId) {
						Mage::log("Campain Id doesn't exist", null, 'getresponse_'.$campainId.'.log');
						return;
					}

					Mage::log("Step 2 - Campain Id Retrived ".$campainId."", null, 'getresponse_'.$campainId.'.log');

					$customFields = self::getCustomFields($getResponse,$campainId);

					$createdAtId = $customFields['created_at'];

					$isSubscribedId = $customFields['is_subscribed'];

					Mage::log("Step 3 - Custom Ids Retrived for Campain Id : ".$campainId."", null, 'getresponse_'.$campainId.'.log');
					
					$website = $value['website'];

					if($website == "limogesjewelry.com") {
						$websiteId = 5;
					} elseif ($website == "tvstoybox.com") {
						$websiteId = 1;
					} elseif ($website == "personalizedplanet.com") {
						$websiteId = 4;
					} else {
						$websiteId = 2;
					}

					Mage::log("Step 4 - Starting Import ", null, 'getresponse_'.$campainId.'.log');

					Mage::log("Memory Usage Before getting list from get response : ".(memory_get_peak_usage(true)/1024/1024)." MB", null, 'getresponse_'.$campainId.'.log');

					$lists = self::getListsFromGetResponse($getResponse,$campainId);

					$existingEmails = $lists[0];
					$toUpdateContact = $lists[1];

					Mage::log("Memory Usage After getting list from get response : ".(memory_get_peak_usage(true)/1024/1024)." MB", null, 'getresponse_'.$campainId.'.log');

					self::startSubscriberImport($getResponse,$campainId,$websiteId,$createdAtId,$isSubscribedId,$existingEmails,$toUpdateContact);

					Mage::log("Memory Usage After Subscriber Import : ".(memory_get_peak_usage(true)/1024/1024)." MB", null, 'getresponse_'.$campainId.'.log');

					self::startCustomerImport($getResponse,$campainId,$websiteId,$createdAtId,$isSubscribedId,$existingEmails,$toUpdateContact);

					Mage::log("Memory Usage After Customer Import : ".(memory_get_peak_usage(true)/1024/1024)." MB", null, 'getresponse_'.$campainId.'.log');

					unset($lists);

					Mage::log("Memory Usage After Unset list : ".(memory_get_peak_usage(true)/1024/1024)." MB", null, 'getresponse_'.$campainId.'.log');

				}
			}
		} else {
			Mage::log("Get Response is Disabled from Configurations", null, 'getresponse.log');
		}
	} 

	private function getListsFromGetResponse($getResponse,$campainId)
	{
		Mage::log("Step 5 - Checking Existing Contacts ", null, 'getresponse_'.$campainId.'.log');

		for($i = 1; $i <= 10000; $i++) {
			$contact = $getResponse->getContacts(
										array(
											"perPage"=>1000,
											"fields" => "email,is_subscribed",
											"page" => $i,
											"query" => array(
											    "campaignId" => $campainId
											),
										));

			if(count((array)$contact) == 0) {
				break;
			} else {
				$contacts[] = (array) $contact;
			}

			Mage::log("Contacts Page ".$i." Retrived", null, 'getresponse_'.$campainId.'.log');
			Mage::log("Memory Usage After Contacts Page : ".$i.(memory_get_peak_usage(true)/1024/1024)." MB", null, 'getresponse_'.$campainId.'.log');

			$count = (array) $contact;
			$count = count($count);

			if($count <= 999) {
				break;
			}

			unset($contact);
		}

		foreach ($contacts as $key => $emails) {
			$emailsAll = (array) $emails;
			foreach ($emailsAll as $key => $value) {
				$valueArr = (array) $value;
				$existingEmails[] = $valueArr['email'];
				$toUpdateContact[] = $valueArr['contactId'];
			}
		}

		$result[0] = $existingEmails;
		$result[1] = $toUpdateContact;

		return $result;
	}

	private function startCustomerImport($getResponse,$campainId,$websiteId,$createdAtId,$isSubscribedId,$existingEmails,$toUpdateContact) 
	{
		try {

			$customerCollection = Mage::getModel('customer/customer')
											->getCollection()
										    ->addAttributeToSelect('*')
										    ->addFieldToFilter('website_id', $websiteId);

			$customerCollection->getSelect()->where("created_at BETWEEN '2016-05-31 00:00:00' AND CURDATE()");

			$existingEmails = array_unique($existingEmails);
			$toUpdateContact = array_unique($toUpdateContact);

			Mage::log("Step 6 - Customer Collection Retrived, Total Number of Customers : ".$customerCollection->count().", Estimated Time : ".gmdate("H:i:s", $customerCollection->count()*2), null, 'getresponse_'.$campainId.'.log');

			foreach ($customerCollection as $key => $customer) {
				$emails[] =  $customer->getEmail();
			}

			$status = array();
			$status['update'] = 0;
			$status['skip'] = 0;
			$status['import'] = 0;
			foreach ($customerCollection as $key => $customer) {

				$subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($customer->getEmail());
				if ($subscriber->getId()) {
				    $subscribed = 1;
				} else {
					$subscribed = 0;
				}

				$returnedKey = array_search($customer->getEmail(), $existingEmails);
				
				if($returnedKey) {

					$contactDeials = $getResponse->getContact($toUpdateContact[$returnedKey]); // loading sigle customer again becuase of the fact that get response cannot return customer data along with custom fields, so if we need custom fields we need to get every single customer even if we need to skip it later

					if(!empty($contactDeials->customFieldValues)) {
						foreach ($contactDeials->customFieldValues as $key => $contactDeial) {
							if($contactDeial->name == "is_subscribed") {
								$contactIsSubscribed = $contactDeial->values[0];
							}
						}
					}

					if(isset($contactIsSubscribed)) {

						if($contactIsSubscribed == $subscribed) {
							$status['skip'] += 1;
							Mage::log("Customer ".$customer->getEmail()." Skipped", null, 'getresponse_'.$campainId.'.log');
							continue;
						}	
					}

					$result = $getResponse->updateContact(
												$toUpdateContact[$returnedKey],
												array(
												    'name'              => $customer->getName(),
												    'email'             => $customer->getEmail(),
												    'campaign'          => array('campaignId' => $campainId),
												    'customFieldValues' => array(
													        array('customFieldId' => $createdAtId,
													            'value' => array(
													                $customer->getCreatedAt()
													            )),
													        array('customFieldId' => $isSubscribedId,
													            'value' => array(
													                $subscribed
													            ))
												    )
												));

					if(!empty($result)) {
						if($result->context[0]->errorDescription ) {
							Mage::log("Customer Update : ".$customer->getEmail().PHP_EOL." Message = ".$result->message.PHP_EOL."Description = ".$result->codeDescription.PHP_EOL." More Info = ".$result->moreInfo.PHP_EOL." Context = ".$result->context[0]->errorDescription, null, 'getresponse_'.$campainId.'.log');
						} elseif($result->codeDescription) {
							Mage::log("Customer Update : ".$customer->getEmail().PHP_EOL." Message = ".$result->message.PHP_EOL."Description = ".$result->codeDescription.PHP_EOL." More Info = ".$result->moreInfo.PHP_EOL, null, 'getresponse_'.$campainId.'.log');
						}
					} else {
						$status['update'] += 1;
						Mage::log("Customer ".$customer->getEmail()." Updated", null, 'getresponse_'.$campainId.'.log');
					}

					unset($contactDeials);
					unset($returnedKey);
				} else {
					
					$result = $getResponse->addContact(
												array(
												    'name'              => $customer->getName(),
												    'email'             => $customer->getEmail(),
												    'campaign'          => array('campaignId' => $campainId),
												    'customFieldValues' => array(
													        array('customFieldId' => $createdAtId,
													            'value' => array(
													                date('Y-m-d', strtotime($customer->getCreatedAt()))
													            )),
													        array('customFieldId' => $isSubscribedId,
													            'value' => array(
													                $subscribed
													            ))
												    )
												));

					if(!empty($result)) {
						if($result->context[0]->errorDescription ) {
							Mage::log("Customer Import : ".$customer->getEmail().PHP_EOL." Message = ".$result->message.PHP_EOL."Description = ".$result->codeDescription.PHP_EOL." More Info = ".$result->moreInfo.PHP_EOL." Context = ".$result->context[0]->errorDescription, null, 'getresponse_'.$campainId.'.log');
						} elseif($result->codeDescription) {
							Mage::log("Customer Import : ".$customer->getEmail().PHP_EOL." Message = ".$result->message.PHP_EOL."Description = ".$result->codeDescription.PHP_EOL." More Info = ".$result->moreInfo.PHP_EOL, null, 'getresponse_'.$campainId.'.log');
						}
					} else {
						$status['import'] += 1;
						Mage::log("Customer ".$customer->getEmail()." Imported", null, 'getresponse_'.$campainId.'.log');
					}

				}

				unset($result);
			}
			unset($status);
		} catch (Mage_Core_Exception $e) {
		  	$message = $e->getMessage();
			Mage::log(print_r($message), null, 'getresponse_'.$campainId.'.log');
		} catch (Exception $e) {
			$message = $e->getMessage();
			Mage::log(print_r($message), null, 'getresponse_'.$campainId.'.log');
		}
	}

	private function startSubscriberImport($getResponse,$campainId,$storeId,$createdAtId,$isSubscribedId,$existingEmails,$toUpdateContact) 
	{
		try {

			$subscriberCollection = Mage::getModel('newsletter/subscriber')->getCollection()
												->addFieldToFilter('store_id', $storeId)
												->addFieldToFilter('customer_id', 0); // get only those subscribers which aren't customers

			if($storeId != 1 || $storeId != 4) {
				$subscriberCollection->getSelect()->where("created_at BETWEEN '2016-10-20 00:00:00' AND CURDATE()");
			}

			$existingEmails = array_unique($existingEmails);

			Mage::log("Step 7 - Subscribers Collection Retrived, Total Number of Subscribers : ".$subscriberCollection->count().", Estimated Time : ".gmdate("H:i:s", $subscriberCollection->count()*2), null, 'getresponse_'.$campainId.'.log');

			$i = 1;
			foreach ($subscriberCollection as $key => $subscriber) {

				if($subscriber->getCreatedAt() == null) {
					$createdDate = "2000-01-01";
				} else {
					$createdDate = $subscriber->getCreatedAt();
				}

				$emails[$i.'-'.$subscriber->getSubscriberStatus().'-'.strtotime($createdDate)] =  $subscriber->getSubscriberEmail();
				$i = $i + 1;
			}

			Mage::log("Get Response Emails : ".count($existingEmails).", ", null, 'getresponse_'.$campainId.'.log');

			Mage::log("Local Emails : ".count($emails).", ", null, 'getresponse_'.$campainId.'.log');

			$subscriberMerged = array_diff($emails, $existingEmails);

			Mage::log("After Skiping Emails : ".count($subscriberMerged).", ", null, 'getresponse_'.$campainId.'.log');

			$status = 0;

			foreach ($subscriberMerged as $key => $subscriber) {

				$data = explode("-", $key);
				$createdDate = $data[2];
				$subscribed = (int) $data[1];

				if(is_null($subscribed)) {
					$subscribed = 0;
				}

				$result = $getResponse->addContact(
											array(
											    'name'              => "subscriber",
											    'email'             => $subscriber,
											    'campaign'          => array('campaignId' => $campainId),
											    'customFieldValues' => array(
												        array('customFieldId' => $createdAtId,
												            'value' => array(
												                date('Y-m-d', $createdDate)
												            )),
												        array('customFieldId' => $isSubscribedId,
												            'value' => array(
												                $subscribed
												            ))
											    )
											));

				if(!empty($result)) {
					if($result->context[0]->errorDescription ) {
						Mage::log("Subscriber Import : ".$subscriber.PHP_EOL." Message = ".$result->message.PHP_EOL."Description = ".$result->codeDescription.PHP_EOL." More Info = ".$result->moreInfo.PHP_EOL." Context = ".$result->context[0]->errorDescription, null, 'getresponse_'.$campainId.'.log');
					} elseif($result->codeDescription) {
						Mage::log("Subscriber Import : ".$subscriber.PHP_EOL." Message = ".$result->message.PHP_EOL."Description = ".$result->codeDescription.PHP_EOL." More Info = ".$result->moreInfo.PHP_EOL, null, 'getresponse_'.$campainId.'.log');
					} else {
						$status += 1;
						Mage::log("Subscriber ".$subscriber." Imported", null, 'getresponse_'.$campainId.'.log');	
					}
				} else {
					$status += 1;
					Mage::log("Subscriber ".$subscriber." Imported", null, 'getresponse_'.$campainId.'.log');
				}

				unset($return);
				unset($subscribed);
				unset($createdDate);
			}

			unset($status);

		} catch (Mage_Core_Exception $e) {
		  	$message = $e->getMessage();
			Mage::log(print_r($message), null, 'getresponse_'.$campainId.'.log');
		} catch (Exception $e) {
			$message = $e->getMessage();
			Mage::log(print_r($message), null, 'getresponse_'.$campainId.'.log');
		}
	}

	private function getCustomFields($getResponse,$campainId)
	{
		$customFields = (array) $getResponse->getCustomFields();

		$isSubscribedId = false;
		$createdAtId = false;

		foreach ($customFields as $key => $custom) {
			var_dump($custom->name); 
			if($custom->name == "created_at") {
				$createdAtId = $custom->customFieldId;	
			} 

			if($custom->name == "is_subscribed") {
				$isSubscribedId = $custom->customFieldId;	
			}
		}

		if($createdAtId === false) {
			
			Mage::log("Custom Field Create Id Doesnot Exist for Campain Id : ".$campainId, null, 'getresponse_'.$campainId.'.log');

			$result = $getResponse->setCustomField(array(
				                'name' => 'created_at',
				                'type' => 'date',
				                'hidden' => 'false',
				                'values' => array('2016-05-31 00:00:00')
				    		));

			if(method_exists($result, 'context')) {
				if($result->context[0]->errorDescription) {
					Mage::log("Customer Update ".$customer->getEmail()." : Message = ".$result->message.", Description = ".$result->codeDescription.", More Info = ".$result->moreInfo.", Context = ".$result->context[0]->errorDescription, null, 'getresponse_'.$campainId.'.log');
				}
			} else {
				Mage::log("Custom Field Create Id Added for Campain Id : ".$campainId, null, 'getresponse_'.$campainId.'.log');
			}
		}

		if($isSubscribedId === false) {

			Mage::log("Custom Field Is Subscribed Doesnot Exist for Campain Id : ".$campainId, null, 'getresponse_'.$campainId.'.log');

			$result = $getResponse->setCustomField(array(
				                'name' => 'is_subscribed',
				                'type' => 'text',
				                'hidden' => 'false',
				                'fieldType' => "multi_select",
				                'values' => array("true","false") 
				    		));

			if(method_exists($result, 'context')) {
				if($result->context[0]->errorDescription) {
					Mage::log("Customer Update ".$customer->getEmail()." : Message = ".$result->message.", Description = ".$result->codeDescription.", More Info = ".$result->moreInfo.", Context = ".$result->context[0]->errorDescription, null, 'getresponse_'.$campainId.'.log');
				}
			} else {
				Mage::log("Custom Field Is Subscribed Added for Campain Id : ".$campainId, null, 'getresponse_'.$campainId.'.log');
			}
		}

		$customFields = $getResponse->getCustomFields();

		foreach ($customFields as $key => $custom) {
			if($custom->name == "created_at") {
				$createdAtId = $custom->customFieldId;	
			}

			if($custom->name == "is_subscribed") {
				$isSubscribedId = $custom->customFieldId;	
			}
		}

		$return = array(
			'created_at' => $createdAtId,
			'is_subscribed' => $isSubscribedId,
			);

		return $return;
	}
}