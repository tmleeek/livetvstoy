<?php
class Celigo_Connector_Block_Pushorderbutton extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
		$params = Mage::app()->getRequest()->getParams();
		unset($params['section']); unset($params['key']);
		
        $this->setElement($element);
        $url = $this->getUrl('connector/adminhtml_connector/pushorder', $params);
		
        $html = '<input type="text" class="input-text" value="" name="connector_order_number" id="connector_order_number" style="width:185px;" placeholder="Order Increment ID"> ';
		$html .= $this->getLayout()->createBlock('adminhtml/widget_button')
					->setType('button')
					->setClass('scalable')
					->setLabel('Push Order')
					->setOnClick("javascript: return validateOrderNumber();")
					->toHtml();
        $html .= '<div id="ondemand-push-msg" style="display:none;"></div>';
        $html .= $this->_addOrderNumberValidator($url);
		
        return $html;
    }
	
	
    /**
     * Prototype validation
     *
     * @return string
     */
    protected function _addOrderNumberValidator($url)
    {
		return '<script type="text/javascript">
	//&lt;![CDATA[
		function validateOrderNumber() {
		
			var elem = $("ondemand-push-msg");
			
			if($("connector_order_number").value == "") {
				elem.addClassName("validation-advice").show();
				$("ondemand-push-msg").update("Please enter Order Number");
				$("connector_order_number").focus();
				return false;
			} else {
				elem.removeClassName("validation-advice").hide();
				$("ondemand-push-msg").update("");
			}
	
			params = {
				ordernumber: $("connector_order_number").value
			};
	
			new Ajax.Request("' . $url .'", {
				parameters: params,
				onSuccess: function(response) {
					response = response.responseText;
					elem.removeClassName("validation-advice").setStyle({color: "#3D6611"}).show();
					$("ondemand-push-msg").update("<strong>Result:</strong> " + response);
				}
			});
			return false;
		}
	//]]&gt;
	</script>';
	}
	
}