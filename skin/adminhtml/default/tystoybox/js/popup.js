function openMyPopup(url, pageTitle, windWidth, windHeight) {

	if ($('browser_window') && typeof (Windows) != 'undefined') {
		Windows.focus('browser_window');
		return;
	}
	if (!windWidth) {
		windWidth = 1050;
	}
	if (!windHeight) {
		windHeight = 600;
	}
	var dialogWindow = Dialog.info(null, {
		closable : true,
		resizable : false,
		draggable : true,
		className : 'magento',
		windowClassName : 'popup-window',
		title : pageTitle,
		top : 50,
		width : windWidth,
		height : windHeight,
		zIndex : 1000,
		recenterAuto : false,
		hideEffect : Element.hide,
		showEffect : Element.show,
		id : 'browser_window',
		url : url
	});
}
function closePopup() {
	Windows.close('browser_window');
}