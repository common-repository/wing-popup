/**
 * Retrieves a popup configuration object by its unique identifier.
 *
 * @param {string} id - The unique identifier of the popup.
 * @returns {Object|null} - Returns the popup configuration object if found, otherwise returns null.
 */
function getWingPopup(id) {
	return wing__cred.popups.find((d) => d.id == id) || null;
}

/**
 * Converts a given time value to seconds based on the unit provided.
 *
 * @param {number} showAgain - The numeric value representing the amount of time.
 * @param {string} showAgainUnit - The unit of time (Minute(s), Hour(s), Day(s), Week(s), Month(s)).
 * @returns {number} The total time in seconds.
 * @throws {Error} If the provided unit is invalid.
 */
function wingConvertToSeconds(showAgain, showAgainUnit) {
	// Mapping of time units to their equivalent in seconds
	const unitsInSeconds = {
		"Minute": 60,
		"Hour": 3600,
		"Day": 86400,
		"Week": 604800,
		"Month": 2592000 // Approximation assuming 30 days in a month
	};

	// Check if the unit exists in the unitsInSeconds object
	if (!unitsInSeconds[showAgainUnit]) {
		throw new Error("Invalid unit");
	}

	// Calculate the total time in seconds
	let totalTimeInSeconds = showAgain * unitsInSeconds[showAgainUnit];
	
	return totalTimeInSeconds;
}
/**
 * Checks whether the popup should be shown based on the last display time stored in local storage.
 *
 * @param {number} showAgainInSeconds - The time interval in seconds after which the popup should be shown again.
 * @param {string} id - The unique identifier of the popup.
 * @returns {boolean} - Returns true if the popup should be shown, false otherwise.
 */
function wingShouldShowPopup(showAgainInSeconds, id) {
	// Retrieve the last popup display time from local storage
	const wingLastPopupTime = localStorage.getItem(`wingLastPopupTime_${id}`);
	
	// If there's no record of the last popup display time, it means the popup should be shown
	if (!wingLastPopupTime) {
		return true;
	}
	
	// Calculate the elapsed time in seconds since the last popup was shown
	const elapsedTime = (Date.now() - wingLastPopupTime) / 1000;
	
	// Check if the elapsed time is greater than or equal to the specified interval
	return elapsedTime >= showAgainInSeconds;
}




