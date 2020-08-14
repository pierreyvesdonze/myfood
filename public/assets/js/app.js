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

		//SHOPPING LISTS & ARTICLES & MODAL
		$('.add-article').click(app.openArticlesModal);
		$('.close-articles-modal').click(app.closeArticlesModal);
		$('.increase-amount').click(app.increaseAmount);
		$('.decrease-amount').click(app.decreaseAmount);
		$('.delete-article').click(app.deleteArticleApi);

		//ALERT MODAL
		app.close = $('.close').on('click', app.closeAlertModal);
		app.modal = $('.alert-success');
		app.modalError = $('.alert-error');
		setTimeout(function () {
			app.modal.remove();
			app.modalError.remove();
			app.close.remove();
		}, 3000);
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
		console.log('close navbar');
		document.getElementById("mySidepanel").style.width = "0";
	},

	closeAlertModal: function () {
		console.log('close alert modal');
		app.modal.remove();
		app.modalError.remove();
		app.close.remove();
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

		const checks = $('.filters :checkbox'),
			boxes = $('.recipe'),
			matches = {};

		checks.filter(':checked').each(function () {
			let name = $(this).attr('name');
			matches[name] = (matches[name] || []);
			matches[name].push('[data-' + name + '="' + $(this).data(name) + '"]');
			console.log(matches);
		});

		const menus = matches.menu ? matches.menu.join() : '*';
		const categories = matches.category ? matches.category.join() : '*';
		boxes.hide().filter(menus).filter(categories).show();
	},

	uncheckAll: function () {
		var checked = !$(this).data('checked');
		$('input:checkbox').prop('checked', checked);
		$(this).val(checked ? 'Tout désélectionner' : 'Tout sélectionner')
		$(this).data('checked', checked);
	},


	/**
	 * ADD ARTICLES MODAL
	 */
	openArticlesModal: function (e) {
		let modal = $('.add-articles-section');
		let modalContent = $('.add-articles-section *');
		setTimeout(function () {
			modalContent.css("visibility", "visible")
		}, 80);
		modal.css("height", "278px");

		//Open current shoplist
		let prevMdodal = this.parentNode.querySelector('.fa-eye');
		prevMdodal.click();

		// Send current shoplist Id to another methods
		let currentId = $(e.currentTarget.childNodes[1]).val();
		$('.add-article-to-shoplist-btn').click(function () {
			app.addArticlesToShopListFront(currentId);
		});
	},

	closeArticlesModal: function () {
		let closeModal = $('.add-articles-section');
		let modalContent = $('.add-articles-section *');
		setTimeout(function () {
			modalContent.css("visibility", "hidden")
		}, 80);
		closeModal.css("height", "0px");
	},

	removeArticle: function (e) {
		e.target.parentNode.parentNode.remove();
	},

	addArticlesToShopListFront: function (shopId) {

		//Add articles to shoplist in Frontend
		let currentShoplist = $('.a-content').find("[data-value='" + shopId + "']");
		console.log(currentShoplist.data("value"))
		console.log(shopId);

		const article = $('.add-article-input').val(),
			articleAmount = $('.add-amount-input').val(),
			articleUnit = $('.add-unit-select').val(),
			shopList = $(currentShoplist).next(),
			newLi = $('<li class="newLi">' + article + ': ' + articleAmount + ' ' + articleUnit + '<a href ="#" class = "remove-article"> <i class="fa fa-trash"></i></a></li>');

		if (article == '') {
			alert("Merci de renseigner un nom à l'article");
		} else if (!$.isNumeric(articleAmount)) {
			alert("Merci de renseigner un nombre valide pour la quantité");
		} else if ('' == articleUnit) {
			alert("Merci de renseigner une unité de mesure");
		} else {
			shopList.append(newLi);
		}

		// Clear inputs
		$(':input').val('');
		$('option').attr('selected', false);

		// Delete article listener
		$('.remove-article').click(app.removeArticle);

		// Send article to back		
		$('.submit-add-article').click(function () {

			let $articlesArray = [];
			let newArticles = $('.newLi');

			$.each(newArticles, function (index, element) {

				let name = $(this).text().split(':')[0];
				let amount = $(this).text().split(' ')[1];
				let unit = $(this).text().split(' ')[2];

				$articlesArray[index] = {
					"name": name,
					"amount": amount,
					"unit": unit,
					"id": shopId
				}
				console.log(element)
			});

			//Send to Backend
			app.addArticlesToShopListBack($articlesArray, shopId)
		});
	},

	addArticlesToShopListBack: function (articlesArray, shopId) {
		shopId = parseInt(shopId, 10);
		$.ajax(
			{
				url: Routing.generate('shopping_list_add_articles', { id: shopId }),
				method: "POST",
				dataType: "json",
				data: JSON.stringify(articlesArray),
			}).done(function (response) {
				if (null !== response) {
					console.log('ok : ' + JSON.stringify(response));
					document.querySelector(
						"#loader").style.visibility = "visible";
					setTimeout(function () {
						location.reload();
					}, 2000);
				} else {
					console.log('Problème');
				}
			}).fail(function (jqXHR, textStatus, error) {
				console.log(jqXHR);
				console.log(textStatus);
				console.log(error);
			});
	},

	increaseAmount: function (e) {
		let currentId = e.currentTarget.children[0].dataset["value"];
		let articleText = e.currentTarget.closest(".articles-container").children[0];

		let currentAmount = articleText.querySelector('.amount-value'),
			parsedIncreasedAmount = parseInt(currentAmount.textContent, 10) + 1;

		currentAmount.textContent = parsedIncreasedAmount;

		//Send updated amount to backend
		app.updateAmountBackend(currentId, currentAmount.textContent);
	},

	updateAmountBackend: function (currentId, currentAmount) {
		const data = {
			"id" : currentId,
			"amount" : currentAmount
		}
		
		console.log(data);
		$.ajax(
			{
				url: Routing.generate('article_increase_amount', { id: currentId }),
				method: "POST",
				dataType: "json",
				data: data,
			}).done(function (response) {
				if (null !== response) {
					console.log('ok : ' + JSON.stringify(response));
				} else {
					console.log('Problème');
				}
			}).fail(function (jqXHR, textStatus, error) {
				console.log(jqXHR);
				console.log(textStatus);
				console.log(error);
			});
	},

	decreaseAmount: function (e) {
		let currentId = $(e.currentTarget).parent().find("[data-value]").data('value');
		let articleText = e.currentTarget.closest(".articles-container").children[0];
		const currentAmount = articleText.querySelector('.amount-value'),
			parsedDecreasedAmount = parseInt(currentAmount.textContent, 10) - 1;

		if (parsedDecreasedAmount >= 1) {
			currentAmount.textContent = parsedDecreasedAmount;
		} else {
			var answer = window.confirm("Supprimer l'article de la liste ?")
			if (answer) {
				$(e.currentTarget).closest(".articles-container").remove();
				app.deleteArticleApi(currentId);
			}
		}
	},

	deleteArticleApi: function (id) {
		let jsonId = {};
		if ("number" !== typeof id) {
			var answer = window.confirm("Supprimer l'article de la liste ?")
			if (answer) {
				$(id.currentTarget).closest('.articles-container').remove();
				jsonId['id'] = id.currentTarget.children[1].dataset['value'];
			}
		} else {
			jsonId['id'] = id;
		}

		$.ajax(
			{
				url: Routing.generate('article_delete', { id: id }),
				method: "POST",
				dataType: "json",
				data: jsonId,
			}).done(function (response) {
				if (null !== response) {
					console.log('ok : ' + JSON.stringify(response));
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