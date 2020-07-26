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
		console.log('coucou');
		$('.search i').addClass('hide');
		$('.search i').removeClass('active');
	},

	activeSearchIcon: function (e) {
		e.preventDefault();
		console.log('click');
		console.log(e.target.className);
		if (e.target.className !== 'search-input') {
			$('.search i').removeClass('hide');
			$('.search i').addClass('active');
			console.log('Hello');
		}
	},

	/**
	 *SEARCH BY INGREDIENTS
	 */
	searchByIngredients: function (e) {

		console.log('Recherche de recettes')

		// Disable submit form on Enter keypress
		var keyCode = e.keyCode || e.which;
		if (keyCode === 13) {
			e.preventDefault()

			// Add new input
			var addInput = $('.add-ingredient-link');
			addInput.trigger("click");

			// Get next input
			var i = $('.search-by-ingredient-input:last').attr('name');
			var getInteger = i.match(/(\d+)/);
			i = parseInt(getInteger);

			// Focus to next input
			setTimeout(function () {
				var pouet = $('#search_recipe_ingredient_' + i + '_name');
				pouet.focus();
			}, 30);

			return false;
		}
	}
};

// App Loading
document.addEventListener('DOMContentLoaded', app.init);

