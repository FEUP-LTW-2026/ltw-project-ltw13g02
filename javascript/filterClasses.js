const weekDay = document.getElementById("week-day");
const time = document.getElementById("time");
const classType = document.getElementById("type");
const trainer = document.getElementById("trainer");
const classes = document.querySelectorAll(".class-list > article");

function getTimeOfDay(timeString) {
    if (!timeString) return "all";

    const times = timeString.split(" - ")

    let startHour = parseInt(times[0].split(':')[0], 10);
    const meridiem = times[0].split(' ')[1].toUpperCase();

    if (meridiem === "PM" && startHour !== 12) {
        startHour += 12;
    }

    if (meridiem === "AM" && startHour === 12) {
        startHour = 0;
    }

    if (startHour < 12) {
        return "morning";
    } else if (startHour < 18) {
        return "afternoon";
    } else {
        return "evening";
    }
}

function filterClasses() {
    const selectedDay = weekDay.value;
    const selectedTime = time.value;
    const selectedType = classType.value;
    const selectedTrainer = trainer.value;

    classes.forEach((card) => {
        const content = card.children[1];
        const className = content.querySelector("h3").textContent.toLowerCase().trim();
        const dayText = content.querySelectorAll("p")[0].lastChild.textContent.toLowerCase().trim();
        const timeText = content.querySelectorAll("p")[1].lastChild.textContent.trim();
        const trainerLink = content.querySelector(".trainer");

        const matchDay = selectedDay === "all" || dayText === selectedDay;

        const matchTime =
            selectedTime === "all" || getTimeOfDay(timeText) === selectedTime;

        const matchType = selectedType === "all" || className.includes(selectedType);

        const matchTrainer =
            selectedTrainer === "all" ||
            trainerLink.textContent.toLowerCase().split(" ").join("-") ===
                selectedTrainer;

        card.style.display =
            matchDay && matchTime && matchType && matchTrainer
                ? "block"
                : "none";
    });
}

weekDay.addEventListener("change", filterClasses);
time.addEventListener("change", filterClasses);
classType.addEventListener("change", filterClasses);
trainer.addEventListener("change", filterClasses);
