const date = new Date();
let currentMonth = date.getMonth();
let currentYear = date.getFullYear();
const months = [
    "January", "February", "March", "April", "May", "June",
    "July", "August", "September", "October", "November", "December"
];

const monthYear = document.getElementById("monthYear");
const calendarGrid = document.getElementById("calendarGrid");
const prevMonthBtn = document.getElementById("prevMonth");
const nextMonthBtn = document.getElementById("nextMonth");

const events = {};

// Save events to localStorage
function saveEvents() {
    localStorage.setItem('events', JSON.stringify(events));
}

// Load events from localStorage
function loadEvents() {
    const storedEvents = localStorage.getItem('events');
    if (storedEvents) {
        Object.assign(events, JSON.parse(storedEvents));
    }
}

function renderCalendar() {
    calendarGrid.innerHTML = "";
    monthYear.textContent = `${months[currentMonth]} ${currentYear}`;

    const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay();
    const lastDateOfMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    const lastDateOfPrevMonth = new Date(currentYear, currentMonth, 0).getDate();

    for (let i = firstDayOfMonth; i > 0; i--) {
        const day = document.createElement('div');
        day.classList.add('day', 'other-month');
        day.textContent = lastDateOfPrevMonth - i + 1;
        day.addEventListener('click', () => {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            renderCalendar();
        });
        calendarGrid.appendChild(day);
    }

    for (let i = 1; i <= lastDateOfMonth; i++) {
        const day = document.createElement('div');
        day.classList.add('day');
        day.textContent = i;

        const dateString = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;

        if (events[dateString]) {
            day.classList.add('has-event');
            day.setAttribute('data-count', events[dateString].length);
        }

        if (
            i === date.getDate() &&
            currentMonth === date.getMonth() &&
            currentYear === date.getFullYear()
        ) {
            day.classList.add('today');
        }

        day.addEventListener('click', () => {
            selectDate(dateString);
        });

        calendarGrid.appendChild(day);
    }

    const totalCells = firstDayOfMonth + lastDateOfMonth;
    const nextDays = (7 - (totalCells % 7)) % 7;

    for (let i = 1; i <= nextDays; i++) {
        const day = document.createElement('div');
        day.classList.add('day', 'other-month');
        day.textContent = i;
        day.addEventListener('click', () => {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            renderCalendar();
        });
        calendarGrid.appendChild(day);
    }
}

function selectDate(dateString) {
    const selectedDate = document.getElementById('selectedDate');
    selectedDate.textContent = `Tasks for ${dateString}`;

    const eventList = document.getElementById('eventList');
    eventList.innerHTML = "";

    if (events[dateString]) {
        events[dateString].forEach((event, index) => {
            const li = document.createElement('li');
            li.textContent = event;

            const removeBtn = document.createElement('button');
            removeBtn.innerHTML = "&times;";
            removeBtn.addEventListener('click', () => {
                events[dateString].splice(index, 1);
                if (events[dateString].length === 0) delete events[dateString];
                saveEvents(); // <-- Save after deleting
                renderCalendar();
                selectDate(dateString);
            });

            li.appendChild(removeBtn);
            eventList.appendChild(li);
        });
    }
}

document.getElementById('addEventBtn').addEventListener('click', () => {
    const input = document.getElementById('newEventInput');
    const value = input.value.trim();
    const selectedDateText = document.getElementById('selectedDate').textContent;
    const selectedDate = selectedDateText.replace('Tasks for ', '').trim();

    if (value && selectedDate) {
        if (!events[selectedDate]) {
            events[selectedDate] = [];
        }
        events[selectedDate].push(value);
        saveEvents(); // <-- Save after adding
        input.value = "";
        renderCalendar();
        selectDate(selectedDate);
    }
});

prevMonthBtn.addEventListener('click', () => {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    renderCalendar();
});

nextMonthBtn.addEventListener('click', () => {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    renderCalendar();
});

// Format today's date
const today = new Date();
const options = { year: 'numeric', month: 'long', day: 'numeric' };
const formattedDate = today.toLocaleDateString('en-US', options);
document.querySelector('.current-date').textContent = formattedDate;

// Load events and render
loadEvents();
renderCalendar();
