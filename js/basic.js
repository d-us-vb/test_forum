

// heaer collapse animation script
// TODO: disable this on mobile
// TODO: mobile users it appears as a simple button in the lower right.
var header = document.getElementById("header");
var headerCollapsed = false;
var scrollFunc = function() {
    if(window.scrollY > 50 && !header.classList.contains("bubble-header")) {
        header.style.animationName = "header-collapse";
        setTimeout(function() {
            header.classList.add("bubble-header");
            header.classList.remove("full-header");
        }, 495);
        headerCollapsed = true;
    } else if(dropdownCollapsed && window.scrollY <= 50 && !header.classList.contains("full-header")) {
        header.style.animationName = "header-expand";
        setTimeout(function() {
            header.classList.add("full-header");
            header.classList.remove("bubble-header");
        }, 495);
        headerCollapsed = false;
    }
}
window.onscroll = scrollFunc;

// dropdown menu
var dropdown = document.getElementById("dropdown");
var linkGroup = document.getElementById("link-group");
var dropdownCollapsed = true;
var toggleDropdownFunc = function() {
    console.log("You clicked the dropdown!");
    
    if(dropdownCollapsed && window.scrollY > 50 && header.classList.contains("bubble-header")) {
        dropdown.style.animationName = "dropdown-expand";
        setTimeout(function() {
            dropdown.classList.add("full-dropdown");
            dropdown.classList.remove("bubble-dropdown");
        }, 485);
        dropdownCollapsed = false;
    } else if(!dropdownCollapsed && window.scrollY > 50 && header.classList.contains("bubble-header")) {
        dropdown.style.animationName = "dropdown-collapse";
        setTimeout(function() {
            dropdown.classList.add("bubble-dropdown")
            dropdown.classList.remove("full-dropdown");
        }, 485);
        dropdownCollapsed = true;
    } else if(dropdownCollapsed && window.scrollY < 50 && header.classList.contains("full-header")) {
        dropdown.style.animationName = "dropdown-expand";
        header.style.animationName = "header-collapse";
        setTimeout(function() {
            dropdown.classList.add("full-dropdown");
            header.classList.add("bubble-header");
            dropdown.classList.remove("bubble-dropdown");
            header.classList.remove("full-header");
        }, 485);
        dropdownCollapsed = false;
    } else {
        dropdown.style.animationName = "dropdown-collapse";
        header.style.animationName = "header-expand";
        setTimeout(function() {
            dropdown.classList.add("bubble-dropdown");
            header.classList.add("full-header");
            dropdown.classList.remove("full-dropdown");
            header.classList.remove("bubble-header");
        }, 485);
        dropdownCollapsed = true;
    }
}

var closeMenu = function() {
    console.log("did you click the window?")
    if(!dropdownCollapsed) {
        toggleDropdownFunc();
    }
}

dropdown.onclick = toggleDropdownFunc;
document.getElementById("menu-icon").onclick = toggleDropdownFunc;
document.getElementById("content").onclick = closeMenu;
