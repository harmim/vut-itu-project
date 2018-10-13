"use strict";


let customMain;
if (typeof customMain === "undefined") {
	customMain = {};
}


customMain.todoList = {
	init: function () {
		const $tr = $("table.todo-list tr");
		if ($tr.length > 0) {
			$tr.each(this.onInit);
			$tr.click(this.onItemClick);

			const $a = $tr.find("a");
			if ($a.length > 0) {
				$a.click(function (e) {
					e.stopPropagation();
				});
			}
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


customMain.sortable = {
	init: function () {
		const $sortable = $(".sortable");
		if ($sortable.length > 0) {
			$sortable.sortable({
				handle: ".handle"
				// update: this.sortChanged
			});
			// this.setSortValues($sortable);
		}
	},

	sortChanged: function () {
		const $sortable = $(".sortable");
		if ($sortable.length > 0) {
			const $form = $sortable.closest("form");
			if ($form.length > 0) {
				$form.trigger("submit");
			}
		}
	},

	setSortValues: function ($sortable) {
		const $form = $sortable.closest("form");
		if ($form.length > 0) {
			const $hidden = $form.find("input[type=hidden][name=sort]");
			if ($hidden.length > 0) {
				$form.submit(function () {
					$hidden.val(JSON.stringify($sortable.sortable("toArray")));
				});
			}
		}
	}
};


$(function () {
	customMain.todoList.init();
	customMain.sortable.init();
});
