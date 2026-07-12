// ===== DAY 5 - DYNAMIC WEB PAGES =====

// ---- STEP 1: ARRAY OF STUDENT OBJECTS ----
// Instead of writing 5 separate variables, we store all students in one array
const students = [
  {
    name: "Nema Ram",
    branch: "CSE",
    cgpa: 8.4,
    city: "Jaipur",
    skills: ["HTML", "CSS", "JavaScript"],
    hobbies: ["Coding", "Gaming"]
  },
  {
    name: "Piyush Aswani",
    branch: "CSE",
    cgpa: 9.5,
    city: "Jaipur",
    skills: ["Python", "React", "SQL"],
    hobbies: ["Reading", "Dancing"]
  },
  {
    name: "Piyush",
    branch: "CSE",
    cgpa: 7.9,
    city: "Jaipur",
    skills: ["C++", "MATLAB", "Arduino"],
    hobbies: ["Music", "Cricket"]
  },
  {
    name: "Nikhil Tetarwal",
    branch: "CSE",
    cgpa: 8.8,
    city: "Jaipur",
    skills: ["Java", "Spring Boot", "MySQL"],
    hobbies: ["Painting", "Yoga"]
  },
  {
    name: "Mohit",
    branch: "CSE",
    cgpa: 7.5,
    city: "Jaipur",
    skills: ["AutoCAD", "SolidWorks", "Python"],
    hobbies: ["Football", "Cooking"]
  }
];


// ---- STEP 2: SHOW TOTAL COUNT AT TOP ----
document.getElementById("totalCount").textContent = "Total Students: " + students.length;


// ---- STEP 3: LOOP TO GENERATE CARDS ----
// We use a for loop to go through each student and build HTML cards
let html = "";

for (let i = 0; i < students.length; i++) {

  // Pick card background - alternate colors using i % 2
  let cardClass = (i % 2 === 0) ? "student-card" : "student-card alt";

  // Pick CGPA badge color - green if CGPA > 8, yellow otherwise
  let cgpaBadge = (students[i].cgpa > 8) ? "cgpa-badge cgpa-high" : "cgpa-badge cgpa-normal";

  // Build the card HTML using template literals
  html += `
    <div class="col-lg-4 col-md-6 col-12">
      <div class="${cardClass}">

        <div class="card-top">
          <h5 class="student-name">${students[i].name}</h5>
          <div class="serial-badge">${i + 1}</div>
        </div>

        <p class="branch-text">${students[i].branch} | ${students[i].city}</p>

        <span class="${cgpaBadge}">CGPA: ${students[i].cgpa}</span>

        <!-- Hidden Details Section -->
        <div class="details">
          <p><strong>Skills:</strong> ${students[i].skills.join(", ")}</p>
          <p><strong>Hobbies:</strong> ${students[i].hobbies.join(", ")}</p>
        </div>

        <!-- Show Details Button -->
        <br>
        <button class="btn-details">Show Details</button>

      </div>
    </div>
  `;
}

// ---- STEP 4: INJECT HTML INTO DOM ----
document.getElementById("cardContainer").innerHTML = html;


// ---- STEP 5: JQUERY - SHOW / HIDE DETAILS ----
// When "Show Details" button is clicked, slideToggle the hidden .details div
$(document).on("click", ".btn-details", function () {

  // Find the .details div inside the same card
  $(this).closest(".student-card").find(".details").slideToggle();

  // Change button text based on toggle state
  if ($(this).text() === "Show Details") {
    $(this).text("Hide Details");
    $(this).css("background-color", "#00d4ff");
    $(this).css("color", "#1a1a2e");
  } else {
    $(this).text("Show Details");
    $(this).css("background-color", "#1a1a2e");
    $(this).css("color", "white");
  }
});


// ---- STEP 6: JQUERY - SEARCH / FILTER ----
// On every keystroke in the search box, filter cards by student name
$("#search").keyup(function () {

  let searchValue = $(this).val().toLowerCase();

  let visibleCount = 0;

  $(".student-card").each(function () {
    let studentName = $(this).find(".student-name").text().toLowerCase();

    if (studentName.includes(searchValue)) {
      $(this).closest(".col-lg-4").show();
      visibleCount++;
    } else {
      $(this).closest(".col-lg-4").hide();
    }
  });

  // Show "no result" message if nothing found
  if (visibleCount === 0) {
    $("#noResult").show();
  } else {
    $("#noResult").hide();
  }
});


// ---- BONUS: FADE IN ANIMATION ON PAGE LOAD ----
$(".student-card").hide().fadeIn(800);
