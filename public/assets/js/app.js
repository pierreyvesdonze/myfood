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

		//SEARCH BY INGREDIENTS
		$('.search-by-ingredients').submit(app.searchByIngredients);
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
		document.getElementById("mySidepanel").style.width = "250px";
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

	/**
	 *SEARCH BY INGREDIENTS
	 */
	searchByIngredients: function (e) {
		e.stopPropagation();
		e.preventDefault();
		let userInput = $('.search-by-ingredients-input').val();
		console.log('input : ' + userInput);

		let resultInput = $('.result-input-ingredients');

		$.ajax(
			{
				url: Routing.generate('shopping_list_by_ingredients_ajax'),
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
	}
};

// App Loading
document.addEventListener('DOMContentLoaded', app.init);




