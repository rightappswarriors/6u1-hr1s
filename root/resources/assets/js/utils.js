const LocalStorage = {};

const Util = {};

const ErrorCodes = {};

ErrorCodes.CODE_DELETED = 216;
ErrorCodes.CODE_EXISTS = 215;

LocalStorage.EMPLOYEES = "EMPLOYEES";

LocalStorage.getItem = (key) => {
	return JSON.parse(window.localStorage.getItem(key));
}

LocalStorage.setItem = (key, value) => {
	window.localStorage.setItem(key, JSON.stringify(value));
}

LocalStorage.getEmployees = (officeId) => {
	let employees = LocalStorage.getItem(LocalStorage.EMPLOYEES);

	if (employees && employees[officeId]) {
		return employees[officeId];
	}

	return [];
}

LocalStorage.setEmployees = (officeId, employees) => {
	let localData = LocalStorage.getItem(LocalStorage.EMPLOYEES);

	// initialize localData
	if (!localData) {
		localData = {};
	}

	localData[officeId] = employees;

	LocalStorage.setItem(LocalStorage.EMPLOYEES, localData);
}

Util.initSelect = ($jQuerySelector, initialOption = null) => {
	// remove all options
	$jQuerySelector.html("");

	if (initialOption != null) {
		Util.appendOption($jQuerySelector, initialOption);
	}
}

Util.appendOption = ($jQuerySelector, optionData) => {
	var option = document.createElement('option');

	option.innerText = optionData.text;
	for (attribute in optionData) {
		if (attribute !== 'text') {
			option.setAttribute(attribute, optionData[attribute]);
		}
	}

	$jQuerySelector.append(option);
}