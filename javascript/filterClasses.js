const weekDay = document.getElementById("week-day");
const time = document.getElementById("time");
const classType = document.getElementById("type");
const trainer = document.getElementById("trainer");
const classes = document.querySelectorAll(".class-list > article");

function getTimeOfDay(timeString) {
    if (!timeString) return "all";
    const startTime = timeString.split(" - ")[0].trim();
    const hour = parseInt(startTime.split(":")[0]);
    if (hour < 12) {
        return "morning";
    } else if (hour < 18) {
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
        const className = content.children[0].textContent.toLowerCase().trim();
        const dayText = content.children[1].textContent.toLowerCase().trim();
        const timeText = content.children[2].textContent;
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
