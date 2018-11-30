"use strict";


let main;
if (typeof main === "undefined") {
	main = {};
}


main.liveForm = {
	init: function () {
		LiveForm.setOptions({
			showValid: true,
			messageErrorPrefix: ""
		});
	}
};


main.todoList = {
	init: function () {
		const $tr = $("table.todo-list tr");
		if ($tr.length > 0) {
			$tr.each(this.onInit);
			$tr.click(this.onItemClick);
			$tr.find("a").click(function (e) {
				e.stopPropagation();
			});
		}
	},

	onInit: function () {
		const $tr = $(this);
		const $checkBox = $tr.find("input[type=checkbox]");
		const $label = $tr.find("label");

		if ($checkBox.length > 0 && $label.length > 0) {
			if ($checkBox.is(":checked")) {
				$tr.addClass("bg-success text-white");
				$label.wrapInner("<del></del>");
			}
		}
	},

	onItemClick: function () {
		const $tr = $(this);
		const $checkBox = $tr.find("input[type=checkbox]");
		const $label = $tr.find("label");

		if ($checkBox.length > 0 && $label.length > 0) {
			if ($checkBox.is(":checked")) {
				$checkBox.prop("checked", false);
				$tr.removeClass("bg-success text-white");
				$label.contents().contents().unwrap();
			} else {
				$checkBox.prop("checked", true);
				$tr.addClass("bg-success text-white");
				$label.wrapInner("<del></del>");
			}
		}
	}
};


main.sortable = {
	init: function () {
		$(".sortable").sortable({
			handle: ".sort-handle",
			update: this.sortChanged
		});
	},

	sortChanged: function (event, ui) {
		const $sortable = $(".sortable");
		const sortArray = $sortable.sortable("toArray");

		const itemIdAttr = ui.item.attr("id");
		const indexOfItem = sortArray.indexOf(itemIdAttr);

		const itemId = parseInt(itemIdAttr.substr(8));
		const prevItemId = typeof sortArray[indexOfItem - 1] !== "undefined"
			? parseInt(sortArray[indexOfItem - 1].substr(8))
			: null;
		const nextItemId = typeof sortArray[indexOfItem + 1] !== "undefined"
			? parseInt(sortArray[indexOfItem + 1].substr(8))
			: null;

		$.ajax({
			url: $sortable.data("sort-url"),
			type: "GET",
			dataType: "json",
			data: {
				itemId: itemId,
				prevItemId: prevItemId,
				nextItemId: nextItemId
			}
		});
	}
};


main.confirmLinks = {
	init: function () {
		$("a, button, input[type=button], input[type=submit]").click(function () {
			if (this.hasAttribute("data-confirm")) {
				let message;
				if (this.dataset.confirm) {
					message = this.dataset.confirm;
				} else {
					message = "Do you really want do this action?";
				}

				if (!confirm(message)) {
					return false;
				}
			}
		});
	}
};


$(function () {
	main.liveForm.init();
	main.todoList.init();
	main.sortable.init();
	main.confirmLinks.init();
});
