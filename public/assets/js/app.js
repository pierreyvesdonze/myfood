var app = {

	init: function () {

		console.log('app init');

		/**
		 * *****************************
		 * L I S T E N E R S
		 * *****************************
		 */
		//NAVBAR
		$('.openbtn').click(app.openNav);
		$('.closebtn').click(app.closeNav);

		//SEARCHBAR
		$('.search').submit(app.search);
		$('.search-input').focus(app.removeSearchIcon);
		$(document).click(app.activeSearchIcon);

		//CREATE LIST INGREDIENTS
		$('.search-recipe-form').on('keypress', app.searchByIngredients);

		//FILTERS MODAL
		$('.filters').click(app.openFiltersModal);
		$('.close-modal-btn').click(app.closeFiltersModal);
		$('#form-filters').submit(app.filtersRecipies);
	},

	/**
	 * *****************************
	 * F U N C T I O N S
	 * *****************************
	 */

	/**
	 * NAVBAR
	 */
	openNav: function () {
		document.getElementById("mySidepanel").style.width = "100%";
	},

	closeNav: function () {
		console.log('close');
		document.getElementById("mySidepanel").style.width = "0";
	},

	/**
	 * FILTERS
	 */
	openFiltersModal: function () {
		console.log('open modal filters');
		let modal = $('.filters-container');
		modal.css("height", "100%")
	},

	closeFiltersModal: function () {
		let closeModal = $('.filters-container');
		closeModal.css("height", "0%")
	},

	filtersRecipies: function (e) {
		e.preventDefault();

		const recipiesData = $('.recipe .filter-data-recipe');
		const filters = $('.filters input[type=checkbox]');
		const filtersTags = $('.filters-tags input[type=checkbox]');
		const hideRecipes = $('.box');

		// if(hideRecipes.hasClass('hide')) {
		// 	hideRecipes.removeClass('hide');
		// 	hideRecipes.addClass('recipe');
		// }

		const filtersArray = [];
		for (filter of filters) {
			if (filter.checked) {
				filtersArray.push(filter.value);
			}
		}

		console.log(filtersArray);
		// for (recipeData of recipiesData) {
		//
		// 	if (filtersArray.length > 0) {
		// 		let menu 	 = recipeData.getAttribute('data-menu');
		// 		let category = recipeData.getAttribute('data-category');
		// 		if (!filtersArray.includes(menu) || !filtersArray.includes(category)) {
		// 			recipeDiv = recipeData.parentNode.parentNode.parentNode.parentNode;
		// 			recipeDiv.classList.remove('recipe');
		// 			recipeDiv.classList.add('hide');
		// 			console.log(menu + ' removed');
		// 		}
		//
		//
		// 	}
		// }



		app.closeFiltersModal();
	},

	/**
	 *SEARCH
	 */
	search: function (e) {
		e.preventDefault();
		let userInput = $('.search-input').val();
		console.log('input : ' + userInput);
		$.ajax(
			{
				url: Routing.generate('searchApi'),
				method: "POST",
				dataType: "json",
				data: JSON.stringify(userInput),
			}).done(function (response) {
			if (null !== response) {
				console.log('ok : ' + JSON.stringify(response))
			} else {
				console.log('Problème');
			}
		}).fail(function (jqXHR, textStatus, error) {
			console.log(jqXHR);
			console.log(textStatus);
			console.log(error);
		});
	},

	removeSearchIcon: function () {

		$('.search i').addClass('hide');
		$('.search i').removeClass('active');
	},

	activeSearchIcon: function (e) {

		if (e.target.className !== 'search-input') {
			$('.search i').removeClass('hide');
			$('.search i').addClass('active');
		}
	},

	/**
	 *SEARCH BY INGREDIENTS
	 */
	searchByIngredients: function (e) {

		console.log('Recherche de recettes');

		// Disable submit form on Enter keypress
		const keyCode = e.keyCode || e.which;
		if (keyCode === 13) {
			e.preventDefault();

			// Add new input
			let addInput = $('.add-ingredient-link');
			addInput.trigger("click");

			// Get next input
			let i = $('.search-by-ingredient-input:last').attr('name');
			let getInteger = i.match(/(\d+)/);
			i = parseInt(getInteger);

			// Focus to next input
			setTimeout(function () {
				let pouet = $('#search_recipe_ingredient_' + i + '_name');
				pouet.focus();
			}, 30);

			return false;
		}
	}
};

// App Loading
document.addEventListener('DOMContentLoaded', app.init);

var checks = $('.filters :checkbox')
var boxes = $('.box')
checks.on('change', function() {
	console.log(boxes.attr('data-menu'))
	var matches = {}
	checks.filter(':checked').each(function() {
		var name = $(this).attr('name');
		matches[name] = (matches[name] || [])
		matches[name].push('[data-' + name + '="' + $(this).data(name) + '"]')
		console.log(matches);
	})
	var menus = matches.menu ? matches.menu.join() : '*'
	boxes.hide().filter(menus).show()

	console.log(menus);
});
