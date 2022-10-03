function activate() {
	const hourOptions = [8, 9, 10, 11, 12, 13, 14, 15, 16, 17].map(numberToOption);
	const minuteOptions = [0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55].map(numberToOption);

    document.querySelectorAll(".time-picker").forEach(timePicker => {
		var timePickable = timePicker.previousSibling;
		timePicker.classList.add("time-picker");
		timePicker.innerHTML = `
			<select class="time-picker__select">
				${hourOptions.join("")}
			</select>
			:
			<select class="time-picker__select">
				${minuteOptions.join("")}
			</select>
		`;
	
		const selects = getSelectsFromPicker(timePicker);
		selects.hour.addEventListener("change", () => timePickable.value = getTimeStringFromPicker(timePicker));
		selects.minute.addEventListener("change", () => timePickable.value = getTimeStringFromPicker(timePicker));
	
		if (timePickable.value) {
			const {hour, minute} = getTimePartsFromPickable(timePickable);
	
			selects.hour.value = hour;
			selects.minute.value = minute;
		}
	});
}

function getTimePartsFromPickable(timePickable) {
	const pattern = /^(\d+):(\d+)/;
	const [hour, minute] = Array.from(timePickable.value.match(pattern)).splice(1);

	return {hour, minute};
}

function getSelectsFromPicker(timePicker) {
	const [hour, minute] = timePicker.querySelectorAll(".time-picker__select");

	return {hour, minute};
}

function getTimeStringFromPicker(timePicker) {
	const selects = getSelectsFromPicker(timePicker);

	return `${selects.hour.value}:${selects.minute.value}`;
}

function numberToOption(number) {
	const padded = number.toString().padStart(2, "0");

	return `<option value="${padded}">${padded}</option>`;
}

activate();