var app = {

	init: function () {

		console.log('app init');

		// Aberration incompréhensible pour le moment
		$(".col-form-label").remove();


		/**
		 * *****************************
		 * L I S T E N E R S
		 * *****************************
		 */

		//COLLAPSE
		$('.button-icon').on('click', app.activeButton);
		$('.filters-menu').on('click', app.setupFiltersCollapse);

		//CREATE LIST INGREDIENTS
		$('.search-recipe-form').on('keypress', app.searchByIngredients);

		//RECIPIES
		$('.add-to-shoplist').click(app.openAddToShoplistModal);
		$('.close-addshop-modal').click(app.closeAddToShoplistModal);
		$('.button-add').click(app.addRecipeToFavs);
		$('.removeFav').click(app.removeRecipeFromFavs);

		//SHOPPING LISTS & ARTICLES & MODAL
		$('.shoplist-create').click(app.shoplistCreate);
		$('.add-article').click(app.openArticlesModal);
		$('.cancel-add-article').click(app.closeArticlesModal);
		$('.increase-amount').click(app.increaseAmount);
		$('.decrease-amount').click(app.decreaseAmount);
		$('.delete-article').click(app.deleteArticleApi);
		$('.open-shoplist').click(app.openShopList);
		$('.validate-new-shoplist').click(app.newShopList);

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

	// Prevent filters nav to uncollapse 
	setupFiltersCollapse: function (e) {
		e.preventDefault();

		$('#filters-nav *').addClass('show-protected');
	},

	activeButton: function (e) {
		let activeButton = e.currentTarget.dataset.target;
		e.preventDefault()
		setTimeout(function () {
			if (!$(activeButton).hasClass('show')) {
				$('.button-icon').blur();
			}
		}, 400);
	},

	closeAlertModal: function () {
		app.modal.remove();
		app.modalError.remove();
		app.close.remove();
	},


	/**
	 * RECIPE
	 */
	openAddToShoplistModal: function (e) {

		let currentId = e.currentTarget.children[0].dataset['value'];
		let currentUser = e.currentTarget.children[0].dataset['user'];
		
		console.log(currentUser);
		$('.add-articles-section *').addClass('show-protected');

		// Send to appropriate method
		$('.submit-add-toshoplist').click(function () {
			app.sendToBackShopListToAdd(currentId, currentUser)
		});

	},

	sendToBackShopListToAdd: function (currentId, currentUser) {

		let className = '.add-shop-select' + currentId.toString();
		let shopListToAddId = $(className).find(':selected').attr("data-value");

		const jsonShopList = {
			'currentId': currentId,
			'currentUser' : currentUser,
			'shopListToAddId': shopListToAddId
		};

		$.ajax(
			{
				url: Routing.generate('shopping_list_add', { id: currentId }),
				method: "POST",
				dataType: "json",
				data: jsonShopList
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


	/**
	 * ADD RECIPE TO FAVS
	 */
	addRecipeToFavs: function (e) {
		let recipeId = e.currentTarget.dataset.id;
		e.preventDefault();
		let isFav = $(e.currentTarget);
		console.log(recipeId);
		isFav.hasClass('isFav') ? isFav.removeClass('isFav') : isFav.addClass('isFav');

		$.ajax(
			{
				url: Routing.generate('set_to_favs', { id: recipeId }),
				method: "POST",
				dataType: "text",
				data: recipeId
			}).done(function (response) {
				console.log('ok' + response);
			}).fail(function (response, jqXHR, textStatus, error) {
				console.log(response);
				console.log(jqXHR);
				console.log(textStatus);
				console.log(error);
			});
	},

	removeRecipeFromFavs: function (e) {
		let recipeId = e.currentTarget.dataset.id;
		e.preventDefault();
		let favToRemove = $(e.currentTarget).closest('.fav-recipe-list');

		$.ajax(
			{
				url: Routing.generate('remove_favs', { id: recipeId }),
				method: "POST",
				dataType: "text",
				data: recipeId
			}).done(function (response) {
				console.log('ok' + response);
				favToRemove.fadeOut();
			}).fail(function (response, jqXHR, textStatus, error) {
				console.log(response);
				console.log(jqXHR);
				console.log(textStatus);
				console.log(error);
			});
	},

	/**
	 * SHOPLIST
	 */
	shoplistCreate: function () {
		let recipeId = this.children[0].dataset['value'];
		$.ajax(
			{
				url: Routing.generate('shopping_list_create', { id: recipeId }),
				method: "POST",
				dataType: "json",

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

	openArticlesModal: function (e) {
		let modal = $('.add-articles-to-shoplist-section');
		let modalContent = $('.add-articles-to-shoplist-section *');
		let jumbocontainer = $('.jumbo-container');
		let jumbotron = $('.jumbotron-shoplist');

		setTimeout(function () {
			modalContent.css("visibility", "visible")
			jumbotron.css("visibility", "hidden")
		}, 80);
		modal.css("height", "300px");
		jumbocontainer.css("height", "0px");

		//Open current shoplist
		let prevModal = this.parentNode.querySelector('.fa-eye');
		let openedModal = $('.a-content');

		if (!openedModal.hasClass('a-content-visible')) {
			prevModal.click();
		}

		// Send current shoplist Id to another methods
		let currentId = $(e.currentTarget.childNodes[1]).val();
		$('.add-article-to-shoplist-btn').click(function () {
			app.addArticlesToShopListFront(currentId);
		});
	},

	closeArticlesModal: function () {
		let closeModal = $('.add-articles-to-shoplist-section');
		let modalContent = $('.add-articles-to-shoplist-section *');
		let jumbocontainer = $('.jumbo-container');
		let jumbotron = $('.jumbotron-shoplist');


		setTimeout(function () {
			modalContent.css("visibility", "hidden")
			jumbotron.css("visibility", "visible")
		}, 80);
		closeModal.css("height", "0px");
		jumbocontainer.css("height", "auto");
	},

	removeArticle: function (e) {
		e.target.parentNode.parentNode.remove();
	},

	addArticlesToShopListFront: function (shopId) {

		// Add articles to shoplist in Frontend
		let currentShoplist = $('.a-content').find("[data-value='" + shopId + "']");

		const article = $('.add-article-input').val(),
			articleAmount = $('.add-amount-input').val(),
			articleUnit = $('.add-unit-select').val(),
			shopList = $(currentShoplist).next(),
			newLi = $('<li class="newLi">' + article + ': ' + articleAmount + ' ' + articleUnit + '<a href ="#" class = "remove-article"> <i class="fa fa-trash"></i></a></li>');

		if (article === '') {
			alert("Merci de renseigner un nom à l'article");
		} else if (!$.isNumeric(articleAmount)) {
			alert("Merci de renseigner un nombre valide pour la quantité");
		} else if ('' === articleUnit) {
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
		// $('.submit-add-article').click(function () {

		let newArticles = $('.newLi');
		let articlesArray = [];

		$.each(newArticles, function (index, element) {

			let name = $(this).text().split(':')[0];
			let amount = $(this).text().split(' ')[1];
			let unit = $(this).text().split(' ')[2];

			articlesArray[index] = {
				"name": name,
				"amount": amount,
				"unit": unit,
				"id": shopId
			};
		});

		console.log(articlesArray)

		//Send to Backend
		app.addArticlesToShopListBack(articlesArray, shopId)
		// });
	},

	addArticlesToShopListBack: function (articlesArray, shopId) {
		shopId = parseInt(shopId, 10);
		console.log(articlesArray)
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
					console.log('Fuck : ' + JSON.stringify(response));
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
			"id": currentId,
			"amount": currentAmount
		};
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
			var answer = window.confirm("Supprimer l'article de la liste ?");
			if (answer) {
				$(e.currentTarget).closest(".articles-container").remove();
				app.deleteArticleApi(currentId);
			}
		}
	},

	deleteArticleApi: function (id) {
		let jsonId = {};
		if ("number" !== typeof id) {
			var answer = window.confirm("Supprimer l'article de la liste ?");
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

	openShopList: function () {
		$(this).toggleClass('fa-eye-slash');
		let aContent = this.parentNode.parentNode.nextElementSibling;
		let aContainer = aContent.closest('.a-container');

		$('.main-main-title').toggleClass('hide-height');
		$(aContainer).prevUntil('.shopping-list-section', '.a-container').toggleClass('hide-height');
		$(aContent).toggleClass('a-content-visible');
	},

	newShopList: function (e) {
		let input = $('.input-new-shoplist');

		$.ajax(
			{
				url: Routing.generate('new-shopping-list'),
				method: 'POST',
				data: JSON.stringify(input.val())
			}).done(function () {
				app.prepareNewList(input)
			})
	},

	prepareNewList: function(newList) {
		window.document.location = Routing.generate('shopping_list_list');

		$list = $('.main-title-shop-list');
		let = $('.fas.fa-shopping-cart').trigger();

		setTimeout(function () {
			console.log('pouet');
		}, 3000);


		
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

// Close opened collapsed nav
$(document).click(function (e) {

	if (
		!$(e.target).hasClass('show-protected')) {
		$('.collapse').removeClass('show')
	}
})