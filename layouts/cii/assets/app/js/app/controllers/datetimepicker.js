define(['bootstrap-datetimepicker'], function() {
		return function DatetimePickerConstructor(node) {
			$(node).datetimepicker($(node).data('options'));
		}
	}
)