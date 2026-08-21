<?php

$pageTitle = "Book Appointment";
$bodyClass = "dashboard-page";

require_once("../includes/auth_check.php");
require_once("../config/database.php");
require_once("../includes/header.php");
require_once("../includes/navbar.php");

?>

<div class="container py-5">

    <!-- Hero Section -->

    <div class="hero-section text-center mb-5">

        <h1>Book Your Appointment</h1>

        <p>

            Discover our range of premium salon services and book your next appointment with ease.

        </p>

    </div>

    <!-- Search -->

    <div class="search-section mb-5">

        <div class="input-group">

            <span class="input-group-text">

                <i class="bi bi-search"></i>

            </span>

            <input
                type="text"
                class="form-control"
                id="serviceSearch"
                placeholder="Search for a service...">

        </div>

    </div>

    <!-- Services -->

<div class="services-section" >

    <div class="row g-4">
        <?php

$sql = "SELECT *
        FROM services
        WHERE status = 'Active'
        ORDER BY category, service_name";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){

    while($service = mysqli_fetch_assoc($result)){

        include("../includes/service_card.php");

    }

}else{

?>

<div class="col-12">

    <div class="dashboard-card text-center py-5">

        <i class="bi bi-scissors"
           style="font-size:4rem;color:var(--gold);"></i>

        <h3 class="mt-4">

            No Services Available

        </h3>

        <p>

            Please check back later.

        </p>

    </div>

</div>

<?php

}

?>

    


    </div>
</div>
<!-- Booking Summary -->

<div class="booking-summary mt-5">

    <div class="dashboard-card">

      <form action="book_appointment_process.php" method="POST">
        <input
             type="hidden"
            name="service_id"
            id="serviceId">

        <h2 class="mb-4">

            Booking Summary

        </h2>

        <div class="summary-item">

            <strong>Selected Service</strong>

            <p id="selectedService">

                None Selected

            </p>

        </div>

        <hr>

        <div class="summary-item">

            <label class="form-label">

                Preferred Date

            </label>

            <input
                type="date"
                class="form-control"
                id="appointmentDate"
                name="appointment_date">

        </div>

        <hr>

        <div class="summary-item">

            <label class="form-label">

                Preferred Time

            </label>

            <input
              type="time"
              class="form-control"
              id="appointmentTime"
             name="appointment_time"
              min="08:00"
              max="18:00"
              step="1800">

        </div>

        <hr>

        <div class="summary-item">

            <label class="form-label">

                Available Staff

            </label>

            <select
                class="form-select"
                id="staffSelect"
                name="staff_id"
                disabled>

                <option>

                    Select a date and time first

                </option>

            </select>

        </div>

        <hr>

        <div class="summary-item">

            <label class="form-label">

                Additional Notes

            </label>

            <textarea
                class="form-control"
                rows="4"
                id="bookingNotes"
                name="notes"
                placeholder="Anything you'd like your stylist to know?"></textarea>

        </div>

        <div class="mt-4">

            <button
                type="submit"
                class="btn btn-primary w-100 btn-lg"
                id="bookAppointmentBtn"
                disabled>

                Book Appointment

            </button>

        </div>

      </form>

    </div>

</div>

</div>
<script>

const cards = document.querySelectorAll(".service-card");

const selectedService = document.getElementById("selectedService");

const searchInput = document.getElementById("serviceSearch");

const appointmentDate = document.getElementById("appointmentDate");

const appointmentTime = document.getElementById("appointmentTime");

const staffSelect = document.getElementById("staffSelect");

const bookButton = document.getElementById("bookAppointmentBtn");

let selectedServiceId = null;

// =========================
// Minimum Date
// =========================

const today = new Date();

const yyyy = today.getFullYear();

const mm = String(today.getMonth() + 1).padStart(2, "0");

const dd = String(today.getDate()).padStart(2, "0");

appointmentDate.min = `${yyyy}-${mm}-${dd}`;

// =========================
// Prevent Sunday Bookings
// =========================

appointmentDate.addEventListener("change", function () {

    const selectedDate = new Date(this.value);

    if (selectedDate.getDay() === 0) {

        alert("Sorry, Chic Groomers Salon is closed on Sundays.");

        this.value = "";

        checkBookingReady();

    }

});

// =========================
// Service Card Selection
// =========================

cards.forEach(card => {

    card.addEventListener("click", () => {

        cards.forEach(c => c.classList.remove("active"));

        card.classList.add("active");

        selectedService.textContent = card.dataset.service;

        selectedServiceId = card.dataset.id;
        
        document.getElementById("serviceId").value = selectedServiceId;

       checkBookingReady();

        if (appointmentDate.value && appointmentTime.value) {

            loadAvailableStaff();

        }


    });

});


// =========================
// Booking Validation
// =========================

function checkBookingReady(){

    const serviceChosen = selectedServiceId !== null;

    const dateChosen = appointmentDate.value !== "";

    const timeChosen = appointmentTime.value !== "";

    const staffChosen = staffSelect.value !== "";

    // Enable/Disable staff dropdown
    staffSelect.disabled = !(serviceChosen && dateChosen && timeChosen);

    // Enable/Disable booking button
    bookButton.disabled = !(serviceChosen && dateChosen && timeChosen && staffChosen);

}

function loadAvailableStaff() {

    if (!selectedServiceId) {

        return;

    }

    fetch(
    "get_available_staff.php?service_id=" +
    selectedServiceId +
    "&date=" +
    appointmentDate.value +
    "&time=" +
    appointmentTime.value
)

        .then(response => response.json())

        .then(data => {

            staffSelect.innerHTML = "";

            if (data.length === 0) {

                staffSelect.innerHTML =
                    "<option value=''>No staff available</option>";

                return;

            }

            staffSelect.innerHTML =
                "<option value=''>Select Staff</option>";

            data.forEach(staff => {

                staffSelect.innerHTML +=

                    `<option value="${staff.user_id}">

                        ${staff.first_name} ${staff.last_name}

                    </option>`;

            });
            checkBookingReady();
        })

        .catch(error => {

            console.error(error);

        });

}


appointmentDate.addEventListener("change", () => {

    checkBookingReady();

    loadAvailableStaff();

});

appointmentTime.addEventListener("change", () => {

    checkBookingReady();

    loadAvailableStaff();

});

staffSelect.addEventListener("change", checkBookingReady);


// =========================
// Service Search
// =========================

searchInput.addEventListener("keyup", function(){

    const searchText = this.value.toLowerCase().trim();

    cards.forEach(card => {

        const service = card.dataset.service.toLowerCase();

        const category = card.dataset.category.toLowerCase();

        const description = card.querySelector(".service-content p").textContent.toLowerCase();

        const parentColumn = card.parentElement;

        if(

            service.includes(searchText) ||

            category.includes(searchText) ||

            description.includes(searchText)

        ){

            parentColumn.style.display = "";

        }
        else{

            parentColumn.style.display = "none";

        }

    });

});

</script>
<?php

require_once("../includes/footer.php");

?>