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

  search: function(e) {
    e.preventDefault();
    let userInput = $('.search-input').val();
    console.log('input : ' + userInput);
    $.ajax(
      {
        url: Routing.generate('searchApi'),
        method: "POST",
        dataType : "json",
        data: userInput        
      }).done(function(response) {
        console.log('ok' + response)
      }).fail(function (jqXHR, textStatus, error) {
        console.log(jqXHR);
        console.log(textStatus);
        console.log(error);
    }); 
  }
}

// App Loading
document.addEventListener('DOMContentLoaded', app.init);




