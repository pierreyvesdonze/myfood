
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
    let burger = document.querySelector(".openbtn")
    burger.classList.add('activebtn');
  },

  closeNav: function () {
    console.log('close');
    document.getElementById("mySidepanel").style.width = "0";
    let burger = document.querySelector(".open btn .activebtn")
    burger.classList.remove('activebtn');
  }
}

// App Loading
document.addEventListener('DOMContentLoaded', app.init);




