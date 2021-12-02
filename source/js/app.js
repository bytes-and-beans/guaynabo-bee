/**
 *  This file should contain the 'global' javascript for the app.
 */

 /** Helper Functions for Alpine */
window.utils = {};
 /** Send user to the search page for a given search term.
  */
window.utils.execute_search = (query='') => {
    if(query != '') window.location.href = `/search?q=${query}`;
}

