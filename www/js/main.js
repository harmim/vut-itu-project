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
			$tr.find("input[type=checkbox], label").click(function (e) {
				e.preventDefault();
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
			const checkBoxId = $checkBox.attr("id");
			const checkBoxIdPrefix = "todo-list-";
			const checkBoxIdGlobalPrefix = "todo-list-global-";
			const isGlobalItem = checkBoxId.startsWith(checkBoxIdGlobalPrefix);
			const itemId = parseInt(
				isGlobalItem
					? checkBoxId.substr(checkBoxIdGlobalPrefix.length)
					: checkBoxId.substr(checkBoxIdPrefix.length)
			);
			const isChecked = $checkBox.is(":checked");

			$.ajax({
				url: $tr.closest("table").data("todo-list-check-url"),
				type: "POST",
				dataType: "json",
				contentType: "application/json",
				data: JSON.stringify({
					itemId: itemId,
					isGlobalItem: isGlobalItem,
					isChecked: !isChecked
				}),
				complete: function () {
					if (isChecked) {
						$checkBox.prop("checked", false);
						$tr.removeClass("bg-success text-white");
						$label.contents().contents().unwrap();
					} else {
						$checkBox.prop("checked", true);
						$tr.addClass("bg-success text-white");
						$label.wrapInner("<del></del>");
					}
				}
			});
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

		const itemIdPrefixLength = "sort-id-".length;
		const itemId = parseInt(itemIdAttr.substr(itemIdPrefixLength));
		const prevItemId = typeof sortArray[indexOfItem - 1] !== "undefined"
			? parseInt(sortArray[indexOfItem - 1].substr(itemIdPrefixLength))
			: null;
		const nextItemId = typeof sortArray[indexOfItem + 1] !== "undefined"
			? parseInt(sortArray[indexOfItem + 1].substr(itemIdPrefixLength))
			: null;

		$.ajax({
			url: $sortable.data("sort-url"),
			type: "POST",
			dataType: "json",
			contentType: "application/json",
			data: JSON.stringify({
				itemId: itemId,
				prevItemId: prevItemId,
				nextItemId: nextItemId
			})
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
