// ===== DAY 6 - JSON & APIs =====

// Fetching from our local JSON file instead of a live API
const API_URL = "data.json";


// ---- MAIN FUNCTION: loadUsers() ----
// Wrapping everything in a function so the Retry button can call it again

function loadUsers() {

  // STEP 1: LOADING STATE
  // Show a loading message before fetch starts
  $("#userContainer").html('<p class="status-msg">⏳ Loading users...</p>');
  $("#userCount").text("Fetching users...");


  // STEP 2: FETCH DATA FROM API
  fetch(API_URL)

    // Convert the raw response to a JavaScript object
    .then(function (res) {
      return res.json();
    })

    // Now we have the data — build the cards
    .then(function (data) {

      // Show total user count at top
      $("#userCount").text("Showing " + data.length + " users");

      // Build HTML cards using a loop
      let cards = "";

      for (let i = 0; i < data.length; i++) {

        // Alternate card color using i % 2
        let cardClass = (i % 2 === 0) ? "user-card" : "user-card alt";

        // Photo URL using pravatar (uses user id for unique photo)
        let photoUrl = "photo/" + data[i].id;

        // Build each card using template literals
        cards += `
          <div class="col-lg-3 col-md-4 col-sm-6 col-12 user-col">
            <div class="${cardClass}">
              <img src="${photoUrl}" class="user-photo" alt="photo">
              <p class="user-name">${data[i].name}</p>
              <p class="user-email">📧 ${data[i].email}</p>
              <p class="user-company">🏢 ${data[i].company.name}</p>
            </div>
          </div>
        `;
      }

      // Inject all cards into the container
      $("#userContainer").html(cards + '<p id="noResult" style="display:none;">No users found.</p>');

    })


    // STEP 3: ERROR STATE
    // If fetch fails, show an error message with a Retry button
    .catch(function (err) {
      console.log("Error:", err);
      $("#userContainer").html(`
        <div class="col-12">
          <p class="error-msg">❌ Unable to load users. Please check your connection.</p>
          <button class="btn-retry" onclick="loadUsers()">🔄 Retry</button>
        </div>
      `);
      $("#userCount").text("Failed to load");
    });

}


// ---- CALL loadUsers() WHEN PAGE LOADS ----
loadUsers();


// ---- LIVE SEARCH FILTER ----
// jQuery keyup — fires on every keystroke in the search box
$("#search").keyup(function () {

  let searchValue = $(this).val().toLowerCase();
  let visible = 0;

  $(".user-card").each(function () {
    let name = $(this).find(".user-name").text().toLowerCase();

    if (name.includes(searchValue)) {
      $(this).closest(".user-col").show();
      visible++;
    } else {
      $(this).closest(".user-col").hide();
    }
  });

  // Show no result message if nothing matches
  if (visible === 0) {
    $("#noResult").show();
  } else {
    $("#noResult").hide();
  }
});