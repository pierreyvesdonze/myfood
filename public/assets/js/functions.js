
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