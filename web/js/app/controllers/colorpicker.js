define(['bootstrap-colorpicker'], function() {
		return function ColorPickerConstructor(node) {
			$(node).colorpicker();
		}
	}
)