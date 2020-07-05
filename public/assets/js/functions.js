
/*
****************************
GROCERIES ADD & REMOVE
****************************
*/
var $collectionHolder;

// setup an "add a article" link
var $addArticleButton = $('<button type="button" class="add-article-link">+</button>');
var $newLinkLi = $('<li></li>').append($addArticleButton);

jQuery(document).ready(function () {
    // Get the ul that holds the collection of articles
    $collectionHolder = $('ul.steps');

    // add a delete link to all of the existing article form li elements
    $collectionHolder.find('li').each(function () {
        addArticleFormDeleteLink($(this));
    });

    // add the "add a article" anchor and li to the articles ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addArticleButton.on('click', function (e) {
        // add a new article form (see next code block)
        addArticleForm($collectionHolder, $newLinkLi);
    });
});

function addArticleForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your articles field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a article" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    // add a delete link to the new form
    addArticleFormDeleteLink($newFormLi);
}

function addArticleFormDeleteLink($articleFormLi) {
    var $removeFormButton = $('<button type="button" class="remove-article-link">-</button>');
    $articleFormLi.append($removeFormButton);

    $removeFormButton.on('click', function (e) {
        // remove the li for the article form
        $articleFormLi.remove();
    });
}