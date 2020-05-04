import { helloWorld } from './../utils'

window.addEventListener('load', function () {
  helloWorld()
  
  var tabs = document.querySelectorAll('ul.nav-tabs > li');

  tabs.forEach(tab => {
    tab.addEventListener('click', switchTab);
  })

  //@event -> clickEvent
  function switchTab (event) {
    event.preventDefault(); //prevent url to change to hashed tab-n

    document.querySelector('ul.nav-tabs li.active').classList.remove('active'); // remove .active class on tab
    document.querySelector('.tab-pane.active').classList.remove('active'); // remove .active class on tab content

    var clickedTab = event.currentTarget
    var anchor = event.target //a href target (the id of the tab content we want to show)
    var activePaneId = anchor.getAttribute('href') //a href target (the  #{id} of the tab content we want to show)
    
    clickedTab.classList.add('active')
    document.querySelector(activePaneId).classList.add('active');

    
  }
})