

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

		//CREATE LIST INGREDIENTS
		$('.create-by-ingredients').submit(app.addNewElementToList);
		$('.find-ingredients').click(app.searchByIngredients);

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
	 * ADD NEW ELEMENT TO LIST
	 */
	addNewElementToList: function (e) {
		e.preventDefault();

		let userInput = $('.create-by-ingredients-input').val(),
			resultSubmit = $('.result-input-ingredients'),
			button = $('<button>X</button>'),
			span = $('<span></span>')

		span.addClass('span-ingredient');
		resultSubmit.append(span);
		span.append(userInput);
		button.addClass('delete-ingredient');
		resultSubmit.append(button);
		resultSubmit.append('<br/>');
		this.reset();
	},

	/**
	 *SEARCH BY INGREDIENTS
	 */
	searchByIngredients: function () {
		console.log('Recherche de recettes')

		var array = [];
		var ingredients = $('.span-ingredient');
		ingredients.each(function () {
			array.push(this.innerHTML);
		});
		console.log(array);
		console.log(Array.isArray(array));

		$.ajax(
			{
				url: Routing.generate('shopping_list_by_ingredients_ajax'),
				type: "POST",
				contentType: "application/json",
				dataType: "json",
				data: JSON.stringify(array),
			}).done(function (response) {
				if (null !== response) {
					console.log('ok : ' + JSON.stringify(response));
					console.log(typeof (response));
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

// Remove Ingredients
$(document).on('click', '.delete-ingredient', function () {
	$(this).prev().remove();
	$(this).next().remove();
	this.remove();
});