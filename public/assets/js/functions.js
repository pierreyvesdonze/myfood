
/*
****************************************
RECIPIES ADD & REMOVE STEPS & INGREDIENTS
****************************************
*/
var $stepCollectionHolder;
var $ingredientCollectionHolder;

var $addStepButton = $('<button type="button" class="add-step-link"><i class="fas fa-plus-circle fa-2x"></button>');
var $addIngredientButton = $('<button type="button" class="button-save">Ajouter un ingrédient</button>');
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
    var $newFormLi = $('<li class="li-step"></li>').append(newForm);
    $newStepLinkLi.before($newFormLi);

    // add a delete link to the new form
    addStepFormDeleteLink($newFormLi);
}

// function stepCounterIncrease(counter) {
//     //Increase
//     let parseCount = parseInt(counter.text());
//     parseCount++;
//     counter.text(parseCount);

//     // If plural
//     let text = $('.step-counter-text');
//     if (parseCount > 1) {
//         text.text("étapes")
//     }
// }

// function stepCounterDecrease(counter) {
//     let parseCount = parseInt(counter.text());
//     parseCount--;
//     counter.text(parseCount);

//     // If plural
//     let text = $('.step-counter-text');
//     if (parseCount < 2) {
//         text.text("étape")
//     }
// }

function addStepFormDeleteLink($stepFormLi) {
    var $removeFormButton = $('<button type="button" class="remove-step-link"></button>');
    $stepFormLi.append($removeFormButton);

    $removeFormButton.on('click', function (e) {
        // remove the li for the article form
        $stepFormLi.remove();

        // Decrease counter
        let counter = ($('.step-counter'));
        stepCounterDecrease(counter);
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
    var $newFormLi = $('<li class="li-ingredient"></li>').append(newForm);
    $newIngredientLinkLi.before($newFormLi);

    // add a delete link to the new form
    addIngredientFormDeleteLink($newFormLi);
}

function addIngredientFormDeleteLink($ingredientFormLi) {
    var $removeFormButton = $('<button type="button" class="remove-ingredient-link"></button>');
    $ingredientFormLi.append($removeFormButton);

    $removeFormButton.insertBefore($ingredientFormLi);

    $removeFormButton.on('click', function (e) {
        // remove the li for the article form
        $ingredientFormLi.remove();
        $removeFormButton.remove();
    });
}

////////////////////////////////////////
// Prevent double click/submit function
///////////////////////////////////////
$('form').submit(function (e) {
    // if the form is disabled don't allow submit
    if ($(this).hasClass('disabled')) {
        e.preventDefault();
        return;
    }
    $(this).addClass('disabled');
});

/*
****************************
LOADER ANIMATION
****************************
*/

document.onreadystatechange = function () {
    if (document.readyState !== "complete") {
        document.querySelector(
            "body").style.visibility = "hidden";
        document.querySelector(
            "#loader").style.visibility = "visible";
    } else {
        document.querySelector(
            "#loader").style.visibility = "hidden";
        document.querySelector(
            "body").style.visibility = "visible";
    }
};

/*
***********************************
DYNAMIC FORM
***********************************
*/
$(document).ready(function () {

    var current_fs, next_fs, previous_fs; //fieldsets
    var opacity;
    var current = 1;
    var steps = $("fieldset").length;

    setProgressBar(current);

    $(".next").click(function () {

        current_fs = $(this).parent();
        next_fs = $(this).parent().next();

        //Add Class Active
        $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

        //show the next fieldset
        next_fs.show();
        //hide the current fieldset with style
        current_fs.animate({ opacity: 0 }, {
            step: function (now) {
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                next_fs.css({ 'opacity': opacity });
            },
            duration: 500
        });
        setProgressBar(++current);
    });

    $(".previous").click(function () {

        current_fs = $(this).parent();
        previous_fs = $(this).parent().prev();

        //Remove class active
        $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

        //show the previous fieldset
        previous_fs.show();

        //hide the current fieldset with style
        current_fs.animate({ opacity: 0 }, {
            step: function (now) {
                // for making fielset appear animation
                opacity = 1 - now;

                current_fs.css({
                    'display': 'none',
                    'position': 'relative'
                });
                previous_fs.css({ 'opacity': opacity });
            },
            duration: 500
        });
        setProgressBar(--current);
    });

    function setProgressBar(curStep) {
        var percent = parseFloat(100 / steps) * curStep;
        percent = percent.toFixed();
        $(".progress-bar")
            .css("width", percent + "%")
    }

    $(".submit").click(function () {
        return false;
    })

    /*
    ***********************************
    BOOTSTRAP MANAGE ACTIVE LINKS
    ***********************************
    */
    $(".nav .nav-link").on("click", function () {
        $(".nav").find(".active").removeClass("active");
        $(this).addClass("active");
    });

  
});  
$(document).ready(function() {
    $('input[type="file"]').change(function() {
        var val = ($(this).val() != "") ? $(this).val() : "No file selected";
        $('.filename').attr('placeholder', val);
    });
});