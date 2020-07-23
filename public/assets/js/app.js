

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
		/* 		$('.search-by-ingredient-input').keydown(app.searchByIngredients); */
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
			setTimeout(function(){ 
				var pouet = $('#search_recipe_ingredient_'+i+'_name');
				pouet.focus();
				 }, 30);
	
			return false;
		}
	}
};

	/**
	 * ADD NEW ELEMENT TO LIST
	 */
/* 	addNewElementToList: function (e) {
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
	}, */

// App Loading
document.addEventListener('DOMContentLoaded', app.init);

// Remove Ingredients
/* $(document).on('click', '.delete-ingredient', function () {
	$(this).prev().remove();
	$(this).next().remove();
	this.remove();
}); */