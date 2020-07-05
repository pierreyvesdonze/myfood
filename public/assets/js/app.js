var app = {

  init: function () {

    console.log('app init');

    // Listeners
    $('.openbtn').click(app.openNav);
    $('.closebtn').click(app.closeNav);

  },

  /**
   * NAVBAR
   */
  openNav: function () {
    document.getElementById("mySidepanel").style.width = "250px";
  },

  closeNav: function () {
    console.log('close');
    document.getElementById("mySidepanel").style.width = "0";
  }
}

// App Loading
document.addEventListener('DOMContentLoaded', app.init);




