
document.addEventListener("DOMContentLoaded", function() {
	
	// wing-modal--item is the class for all the modals
	const WingModals = document.querySelectorAll('.wing-modal--item');
	// Return if modal is not found
	if(WingModals.length == 0) return;

	WingModals.forEach(popup => {
		const myModal = new bootstrap.Modal(popup)
		const thisId = popup.getAttribute('wing-popup-id');
		const { page_load_events, on_click_events } = getWingPopup(thisId);
		
		popup.addEventListener('show.bs.modal', () => {
			popup.classList.remove('hide');
		});
		popup.addEventListener('hide.bs.modal', () => {
			popup.classList.add('hide');
		});
		
		
		// Handle Page load events
		const {page_load_status, popup_delay, popup_show_again} = page_load_events;
		if (page_load_status === '1') {
			const delayTime = popup_delay * 1000; // Convert seconds to milliseconds
			const { width: showAgain, unit: showAgainUnit } = popup_show_again;
			const cleanUnit = showAgainUnit.replace("(s)", ""); // Clean up the unit string
			const realTime = wingConvertToSeconds(showAgain, cleanUnit);

			// Show Modal
			if (wingShouldShowPopup(realTime, thisId)) {
				setTimeout(() => {
					myModal.show();
					localStorage.setItem(`wingLastPopupTime_${thisId}`, Date.now());
				}, delayTime);
			}
		}
		
		// Handle OnClick events
		const { on_click_status, popup_css_selectors, prevent_default } = on_click_events;
		if (on_click_status === '1' && popup_css_selectors != '') {
			const selectors = popup_css_selectors.split(',');
			selectors.forEach(selector => {
				document.querySelector(selector)?.addEventListener('click', (e) => {
					if (prevent_default === '1') {
						e.preventDefault();
					}
					myModal.show();
				});
			});
		}
		
	});

});

