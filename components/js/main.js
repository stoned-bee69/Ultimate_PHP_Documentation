// Copy ----------------------------------------------------------------------------------------------------------------------------

  function copy(id_content, id_button) {
    copy_Text = document.getElementById(id_content).innerHTML;

    copy_Text = copy_Text.trim();

    copy_Text = copy_Text.replaceAll('&lt;','<');
    copy_Text = copy_Text.replaceAll('&gt;','>');

    navigator.clipboard.writeText(copy_Text);

    document.getElementById(id_button).innerHTML = 'Copied!';
  }

// ---------------------------------------------------------------------------------------------------------------------------------


// Navbar Burger -------------------------------------------------------------------------------------------------------------------

  document.addEventListener('DOMContentLoaded', () => {

  // Get all "navbar-burger" elements
  const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

  // Add a click event on each of them
  $navbarBurgers.forEach( el => {

          el.addEventListener('click', () => {

              // Get the target from the "data-target" attribute
              const target = el.dataset.target;
              const $target = document.getElementById(target);

              // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
              el.classList.toggle('is-active');
              $target.classList.toggle('is-active');

          });
      });

  });

// ---------------------------------------------------------------------------------------------------------------------------------

// Documentation dropdowns ------------------------------------------------------------------------------------------------------------

  function hide_show_something(ID) {
    if (document.getElementById(ID).style.display == "none") {
        document.getElementById(ID).style.display = "block";
    } else {
        document.getElementById(ID).style.display = "none";
    } 
  }

// ---------------------------------------------------------------------------------------------------------------------------------

