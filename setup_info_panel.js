// clear error
parent.document.getElementById('main_error').innerHTML = ' &nbsp; ';

// load & save history
function load_main_hist(url) {
    $('#main_space').load(url);
    historyAdd(url);
}

