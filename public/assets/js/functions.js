
/*
****************************************
RECIPIES ADD & REMOVE STEPS & INGREDIENS
****************************************
*/
var $stepCollectionHolder;
var $ingredientCollectionHolder;


var $addStepButton = $('<button type="button" class="add-step-link">Ajouter</button>');
var $addIngredientButton = $('<button type="button" class="add-ingredient-link">Ajouter</button>');
var $newStepLinkLi = $('<li></li>').append($addStepButton);
var $newIngredientLinkLi = $('<li></li>').append($addIngredientButton);

jQuery(document).ready(function () {

    $stepCollectionHolder = $('ul.steps');
    $ingredientCollectionHolder = $('ul.ingredients');

    $stepCollectionHolder.find('.li-step').each(function () {
        addStepFormDeleteLink($(this));
    });
    $ingredientCollectionHolder.find('.li-ingredient').each(function () {
        addIngredientFormDeleteLink($(this));
    });

    // add the "add a article" anchor and li to the articles ul
    $stepCollectionHolder.append($newStepLinkLi);
    $ingredientCollectionHolder.append($newIngredientLinkLi);

    $stepCollectionHolder.data('index', $stepCollectionHolder.find(':input').length);
    $ingredientCollectionHolder.data('index', $ingredientCollectionHolder.find(':input').length);

    $addStepButton.on('click', function (e) {
        // add a new article form (see next code block)
        addStepForm($stepCollectionHolder, $newStepLinkLi);
    });
    $addIngredientButton.on('click', function (e) {
        // add a new article form (see next code block)
        addIngredientForm($ingredientCollectionHolder, $newIngredientLinkLi);
    });
});

function addStepForm($stepCollectionHolder, $newStepLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $stepCollectionHolder.data('prototype');

    // get the new index
    var index = $stepCollectionHolder.data('index');

    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $stepCollectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a article" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newStepLinkLi.before($newFormLi);

    // add a delete link to the new form
    addStepFormDeleteLink($newFormLi);
}

function addStepFormDeleteLink($stepFormLi) {
    var $removeFormButton = $('<button type="button" class="remove-step-link">Supprimer</button>');
    $stepFormLi.append($removeFormButton);

    $removeFormButton.on('click', function (e) {
        // remove the li for the article form
        $stepFormLi.remove();
    });
}


function addIngredientForm($ingredientCollectionHolder, $newIngredientLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $ingredientCollectionHolder.data('prototype');

    // get the new index
    var index = $ingredientCollectionHolder.data('index');

    var newForm = prototype;

    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $ingredientCollectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a article" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newIngredientLinkLi.before($newFormLi);

    // add a delete link to the new form
    addIngredientFormDeleteLink($newFormLi);
}

function addIngredientFormDeleteLink($ingredientFormLi) {
    var $removeFormButton = $('<button type="button" class="remove-ingredient-link">Supprimer</button>');
    $ingredientFormLi.append($removeFormButton);

    $removeFormButton.on('click', function (e) {
        // remove the li for the article form
        $ingredientFormLi.remove();
    });
}

/*
****************************************
MEAL PLAN ADD RECIPIES
****************************************
*/

var $addMealButton = $('<button type="button" class="add_meal_link">Add a meal</button>');
var $newLinkLi = $('<li></li>').append($addMealButton);

jQuery(document).ready(function () {

    $collectionHolder = $('ul.meals');
    $collectionHolder.append($newLinkLi);
    $collectionHolder.data('index', $collectionHolder.find('input').length);
    $addMealButton.on('click', function (e) {
        addMealForm($collectionHolder, $newLinkLi);
    });
});

function addMealForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    newForm = newForm.replace(/__name__/g, index);

    $collectionHolder.data('index', index + 1);

    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    addMealFormDeleteLink($newFormLi);
}

function addMealFormDeleteLink($stepFormLi) {
    var $removeFormButton = $('<button type="button" class="remove-meal-link">Supprimer</button>');
    $stepFormLi.append($removeFormButton);

    $removeFormButton.on('click', function (e) {
        // remove the li for the article form
        $stepFormLi.remove();
    });
}

function afficherCalendrier(idInputDate)
{
    $('#' + idInputDate).datepicker({
        dateFormat: 'dd/mm/yy',
        firstDay: 1
    });
}