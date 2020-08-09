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
		//$('.search').submit(app.search);
		$('.search-input').focus(app.removeSearchIcon);
		$(document).click(app.activeSearchIcon);

		//CREATE LIST INGREDIENTS
		$('.search-recipe-form').on('keypress', app.searchByIngredients);

		//FILTERS MODAL
		$('.filters-sliders').click(app.openFiltersModal);
		$('.close-modal-btn').click(app.closeFiltersModal);
		$('#submit-form-filters').click(app.closeFiltersModal);
		$('.filters :checkbox').on('change', app.filtersRecipies);
		$('.select-all').click(app.uncheckAll);
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

		const checks  = $('.filters :checkbox'),
			  boxes   = $('.recipe'),
			  matches = {};

		checks.filter(':checked').each(function () {
			let name = $(this).attr('name');
			matches[name] = (matches[name] || []);
			matches[name].push('[data-' + name + '="' + $(this).data(name) + '"]');
			console.log(matches);
		});

		const menus 	 = matches.menu ? matches.menu.join() : '*';
		const categories = matches.category ? matches.category.join() : '*';
		boxes.hide().filter(menus).filter(categories).show();
	},

	uncheckAll: function() {
		var checked = !$(this).data('checked');
		$('input:checkbox').prop('checked', checked);
		$(this).val(checked ? 'Tout désélectionner' : 'Tout sélectionner' )
		$(this).data('checked', checked);
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
				console.log('ok : ' + JSON.stringify(response));
			/* 	let redirectUrl = Routing.generate('recipe_list_api', response, true);

				response = JSON.stringify(response);
				window.location.replace(redirectUrl + response); */
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