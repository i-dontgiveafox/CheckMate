// Get DOM elements
const body = document.querySelector("body");
const sidebar = body.querySelector(".sidebar");
const toggle = body.querySelector(".toggle");
const searchBox = body.querySelector(".search-box");
const profilePic = document.getElementById('profilePic');
const fileInput = document.getElementById('fileInput');
const editProfileBtn = document.getElementById('editProfileBtn');
const editInfoBtn = document.querySelector('.edit-info-btn');
const popup = document.getElementById('editPopup');
const closePopupBtn = document.getElementById('closePopup');
const editForm = document.getElementById('editForm');
// Get tasks popup elements
const viewTasksBtn = document.querySelector('.view-tasks-btn');
const tasksPopup = document.getElementById('tasksPopup');
const closeTasksPopupBtn = document.getElementById('closeTasksPopup');
const tasksListContainer = document.querySelector('.tasks-list-container');

// Declare mobileToggle as a global variable
let mobileToggle;

// Show tasks popup when View all tasks button is clicked
if (viewTasksBtn && tasksPopup) {
    viewTasksBtn.addEventListener('click', () => {
        // Clone existing tasks to the popup
        const existingTasks = document.querySelector('.tasks ul');
        if (existingTasks) {
            // Clear previous content
            tasksListContainer.innerHTML = '';

            // Clone the tasks list
            const tasksList = existingTasks.cloneNode(true);

            // Add to the container
            tasksListContainer.appendChild(tasksList);

            // Show the popup
            tasksPopup.style.display = 'flex';

            // Enable the check functionality in the cloned tasks
            enableTaskCheckInPopup();
        }
    });
}

// Close tasks popup when X button is clicked
if (closeTasksPopupBtn && tasksPopup) {
    closeTasksPopupBtn.addEventListener('click', () => {
        tasksPopup.style.display = 'none';
    });
}

// Close tasks popup when clicking outside the popup content
if (tasksPopup) {
    tasksPopup.addEventListener('click', (e) => {
        if (e.target === tasksPopup) {
            tasksPopup.style.display = 'none';
        }
    });

    // Handle Escape key for closing tasks popup
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && tasksPopup.style.display === 'flex') {
            tasksPopup.style.display = 'none';
        }
    });
}

// Enable task check functionality for cloned tasks in popup
function enableTaskCheckInPopup() {
    const checkIcons = tasksPopup.querySelectorAll('.check-icon');

    checkIcons.forEach(icon => {
        icon.addEventListener('click', function () {
            const taskItem = this.closest('li');
            const taskTitle = taskItem.querySelector('.task-title').textContent;

            // Toggle completed class on task item
            taskItem.classList.toggle('task-completed');

            // Style changes for completed tasks
            if (taskItem.classList.contains('task-completed')) {
                this.style.backgroundColor = 'var(--purple-accent)';
                this.style.color = 'white';
            } else {
                this.style.backgroundColor = 'transparent';
                this.style.color = 'var(--purple-accent)';
            }

            // Save task status to localStorage
            const taskStatus = JSON.parse(localStorage.getItem('taskStatus') || '{}');
            taskStatus[taskTitle] = taskItem.classList.contains('task-completed');
            localStorage.setItem('taskStatus', JSON.stringify(taskStatus));

            // Also update the main task list
            updateMainTaskList(taskTitle, taskItem.classList.contains('task-completed'));

            // Show brief confirmation
            showToast(`Task ${taskStatus[taskTitle] ? 'completed' : 'reopened'}!`, 1500);
        });
    });
}

// Update the main task list when a task is checked/unchecked in the popup
function updateMainTaskList(taskTitle, isCompleted) {
    const mainTasksList = document.querySelector('.tasks > .tasks-container > ul');
    if (mainTasksList) {
        const tasks = mainTasksList.querySelectorAll('li');

        tasks.forEach(task => {
            const title = task.querySelector('.task-title').textContent;
            if (title === taskTitle) {
                const checkIcon = task.querySelector('.check-icon');

                if (isCompleted) {
                    task.classList.add('task-completed');
                    if (checkIcon) {
                        checkIcon.style.backgroundColor = 'var(--purple-accent)';
                        checkIcon.style.color = 'white';
                    }
                } else {
                    task.classList.remove('task-completed');
                    if (checkIcon) {
                        checkIcon.style.backgroundColor = 'transparent';
                        checkIcon.style.color = 'var(--purple-accent)';
                    }
                }
            }
        });
    }
}

// Create overlay element for mobile sidebar
const createOverlay = () => {
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);

    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        // Show mobile toggle when overlay is clicked
        if (mobileToggle) mobileToggle.style.display = 'flex';
    });

    return overlay;
};

const overlay = createOverlay();

// Create mobile toggle for smaller screens
const createMobileToggle = () => {
    // Check if mobile toggle already exists
    let mobileToggleBtn = document.querySelector('.mobile-toggle');

    if (mobileToggleBtn) {
        return mobileToggleBtn;
    }

    mobileToggleBtn = document.createElement('button');
    mobileToggleBtn.className = 'mobile-toggle';
    mobileToggleBtn.innerHTML = '<i class="bx bx-menu"></i>';
    mobileToggleBtn.setAttribute('aria-label', 'Toggle Menu');
    document.body.appendChild(mobileToggleBtn);

    mobileToggleBtn.addEventListener('click', () => {
        sidebar.classList.add('active');
        overlay.classList.add('active');
        mobileToggleBtn.style.display = 'none';
    });

    return mobileToggleBtn;
};
toggle.addEventListener("click", () => {
    sidebar.classList.toggle("close");
});
/**
 * Expand sidebar when search is clicked
 */
if (searchBox) {
    searchBox.addEventListener("click", () => {
        if (window.innerWidth > 576) {
            sidebar.classList.remove("close");
        }
    });
}

/**
 * Profile picture handling
 */
// Trigger file input when profile picture is clicked
if (profilePic && fileInput) {
    profilePic.addEventListener('click', () => {
        fileInput.click();
    });
}

// Trigger file input when "Edit profile" button is clicked
if (editProfileBtn && fileInput) {
    editProfileBtn.addEventListener('click', () => {
        fileInput.click();
    });
}

// Update profile picture when a new file is selected
if (fileInput) {
    fileInput.addEventListener('change', (event) => {
        const file = event.target.files[0];

        if (file) {
            // Validate file is an image
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file.');
                return;
            }

            // Check file size (max 5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('Image size should be less than 5MB.');
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                profilePic.src = e.target.result;

                // Store in localStorage for persistence
                localStorage.setItem('userProfilePic', e.target.result);

                // Show success message
                showToast('Profile picture updated successfully!');
            };
            reader.readAsDataURL(file);
        }
    });
}

/**
 * Edit Information popup handling
 */
// Show popup when Edit button is clicked
if (editInfoBtn && popup) {
    editInfoBtn.addEventListener('click', () => {
        popup.style.display = 'flex';

        // Focus on the first input field for better accessibility
        const firstInput = popup.querySelector('input');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
    });
}

// Close popup when X button is clicked
if (closePopupBtn && popup) {
    closePopupBtn.addEventListener('click', () => {
        popup.style.display = 'none';
    });
}

// Close popup when clicking outside the popup content
if (popup) {
    window.addEventListener('click', (e) => {
        if (e.target === popup) {
            popup.style.display = 'none';
        }
    });

    // Handle Escape key for closing popup
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && popup.style.display === 'flex') {
            popup.style.display = 'none';
        }
    });
}

// Handle form submission
if (editForm) {
    editForm.addEventListener('submit', (e) => {
        e.preventDefault();

        // Get updated values
        const firstName = document.getElementById('firstName').value.trim();
        const lastName = document.getElementById('lastName').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();

        // Enhanced name validation (only letters, spaces, hyphens, and apostrophes)
        const nameRegex = /^[A-Za-z\s\-']+$/;
        if (!firstName || !nameRegex.test(firstName)) {
            alert('Please enter a valid first name (letters, spaces, hyphens, and apostrophes only).');
            return;
        }

        if (!lastName || !nameRegex.test(lastName)) {
            alert('Please enter a valid last name (letters, spaces, hyphens, and apostrophes only).');
            return;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !emailRegex.test(email)) {
            alert('Please enter a valid email address.');
            return;
        }

        // Enhanced phone validation
        // Allows formats like: +123456789, (123) 456-7890, 123-456-7890, 123.456.7890
        const phoneRegex = /^(\+\d{1,3})?[\s.-]?\(?\d{3}\)?[\s.-]?\d{3}[\s.-]?\d{4}$/;
        if (!phone || !phoneRegex.test(phone)) {
            alert('Please enter a valid phone number.');
            return;
        }

        // Update the UI with new values
        updateProfileInfo(firstName, lastName, email, phone);

        // Store information for persistence
        const userInfo = { firstName, lastName, email, phone };
        localStorage.setItem('userInfo', JSON.stringify(userInfo));

        // Close the popup after saving
        popup.style.display = 'none';

        // Show success message
        showToast('Profile information updated successfully!');
    });
}

// Update the UI with new profile info
function updateProfileInfo(firstName, lastName, email, phone) {
    // Update the displayed name in sidebar
    const nameElement = document.querySelector('.header-text .name');
    if (nameElement) {
        nameElement.textContent = `${firstName} ${lastName.charAt(0)}.`;
    }

    // Update the displayed name in profile
    const profileName = document.querySelector('.profile-info h2');
    if (profileName) {
        profileName.textContent = `${firstName} ${lastName}`;
    }

    // Update the personal info section
    const infoItems = document.querySelectorAll('.info-item');
    if (infoItems.length >= 4) {
        infoItems[0].querySelector('span').textContent = firstName;
        infoItems[1].querySelector('span').textContent = lastName;
        infoItems[2].querySelector('span').textContent = email;
        infoItems[3].querySelector('span').textContent = phone;
    }
}

// Show toast notification
function showToast(message, duration = 3000) {
    // Check if toast container exists, create if not
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        document.body.appendChild(toastContainer);

        // Add styles for the toast container if not in CSS
        toastContainer.style.position = 'fixed';
        toastContainer.style.bottom = '20px';
        toastContainer.style.right = '20px';
        toastContainer.style.zIndex = '9999';
    }

    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;

    // Add styles for the toast if not in CSS
    toast.style.backgroundColor = 'var(--purple-accent)';
    toast.style.color = 'white';
    toast.style.padding = '12px 20px';
    toast.style.borderRadius = '6px';
    toast.style.marginTop = '10px';
    toast.style.boxShadow = '0 3px 10px rgba(0,0,0,0.2)';
    toast.style.opacity = '0';
    toast.style.transition = 'opacity 0.3s ease';

    // Add to container
    toastContainer.appendChild(toast);

    // Show toast with animation
    setTimeout(() => {
        toast.style.opacity = '1';
    }, 10);

    // Hide and remove after duration
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, duration);
}

// Handle window resize events
window.addEventListener('resize', () => {
    if (window.innerWidth > 576) {
        // Remove overlay on larger screens
        overlay.classList.remove('active');

        // Hide mobile toggle on larger screens
        if (mobileToggle) mobileToggle.style.display = 'none';

        // Show sidebar toggle on desktop
        if (toggle) toggle.style.display = 'block';

        // Reset sidebar state for desktop
        sidebar.classList.remove('active');
    } else {
        // Create mobile toggle for small screens if it doesn't exist
        if (!mobileToggle) {
            mobileToggle = createMobileToggle();
        }

        // Hide sidebar toggle on mobile
        if (toggle) toggle.style.display = 'none';

        // Show mobile toggle on small screens (if sidebar is closed)
        if (mobileToggle && !sidebar.classList.contains('active')) {
            mobileToggle.style.display = 'flex';
        } else if (mobileToggle) {
            mobileToggle.style.display = 'none';
        }

        // Ensure sidebar is in the correct state for mobile
        sidebar.classList.remove('active');
    }
});

// Additional code to handle sidebar close button
// This ensures mobile toggle reappears when sidebar is closed
if (document.querySelector('.sidebar-close') || document.querySelector('.close-sidebar')) {
    const closeBtn = document.querySelector('.sidebar-close') || document.querySelector('.close-sidebar');
    closeBtn.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        // Show mobile toggle when sidebar is manually closed
        if (mobileToggle) mobileToggle.style.display = 'flex';
    });
}

// Initialize active state for current navigation
const initActiveNav = () => {
    const currentPage = window.location.pathname.split('/').pop();
    const navLinks = document.querySelectorAll('.nav-link a');

    navLinks.forEach(link => {
        const linkPage = link.getAttribute('href');
        if (linkPage === currentPage ||
            (currentPage === '' && linkPage === 'dashboard.html')) {
            link.classList.add('active');

            // Update page title based on active link
            const sectionName = link.getAttribute('data-section');
            if (sectionName) {
                document.title = `${sectionName} | User Profile`;
            }
        }
    });
};

// Add smooth scroll for mobile devices
const enableSmoothScroll = () => {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });

                // Close sidebar on mobile after navigation
                if (window.innerWidth <= 576) {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                    // Show mobile toggle after navigation
                    if (mobileToggle) mobileToggle.style.display = 'flex';
                }
            }
        });
    });
};

// Function to handle touch gestures for mobile
const enableTouchGestures = () => {
    let touchStartX = 0;
    let touchEndX = 0;
    const minSwipeDistance = 70; // Reduced sensitivity to prevent accidental swipes

    document.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    document.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        handleSwipe();
    }, { passive: true });

    const handleSwipe = () => {
        const swipeDistance = Math.abs(touchEndX - touchStartX);

        // Only trigger if the swipe is substantial (prevents accidental triggers)
        if (swipeDistance < minSwipeDistance) return;

        // Detect right swipe to open sidebar (only near the left edge of screen)
        if (touchEndX - touchStartX > minSwipeDistance && touchStartX < 50 && window.innerWidth <= 576) {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            if (mobileToggle) mobileToggle.style.display = 'none';
        }

        // Detect left swipe to close sidebar (only when sidebar is open)
        if (touchStartX - touchEndX > minSwipeDistance && sidebar.classList.contains('active') && window.innerWidth <= 576) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            if (mobileToggle) mobileToggle.style.display = 'flex';
        }
    };
};

// Save task status
const enableTaskStatusToggle = () => {
    const checkIcons = document.querySelectorAll('.check-icon');

    checkIcons.forEach(icon => {
        icon.addEventListener('click', function () {
            const taskItem = this.closest('li');
            const taskTitle = taskItem.querySelector('.task-title').textContent;

            // Toggle completed class on task item
            taskItem.classList.toggle('task-completed');

            // Style changes for completed tasks
            if (taskItem.classList.contains('task-completed')) {
                this.style.backgroundColor = 'var(--purple-accent)';
                this.style.color = 'white';
            } else {
                this.style.backgroundColor = 'transparent';
                this.style.color = 'var(--purple-accent)';
            }

            // Save task status to localStorage
            const taskStatus = JSON.parse(localStorage.getItem('taskStatus') || '{}');
            taskStatus[taskTitle] = taskItem.classList.contains('task-completed');
            localStorage.setItem('taskStatus', JSON.stringify(taskStatus));

            // Show brief confirmation
            showToast(`Task ${taskStatus[taskTitle] ? 'completed' : 'reopened'}!`, 1500);
        });
    });
};

// Initialize accessibility features
const initAccessibility = () => {
    // Add proper ARIA attributes to elements
    if (searchBox) {
        searchBox.setAttribute('role', 'search');
    }

    // Make sure interactive elements are keyboard accessible
    const focusableElements = document.querySelectorAll('button, a, input');
    focusableElements.forEach(el => {
        if (!el.hasAttribute('tabindex')) {
            el.setAttribute('tabindex', '0');
        }
    });

    // Add focus styles if not already defined in CSS
    document.head.insertAdjacentHTML('beforeend', `
    <style>
    :focus-visible {
        outline: 3px solid var(--purple-accent) !important;
        outline-offset: 2px !important;
    }
    </style>
    `);
};

// Detect preferred color scheme
const detectColorScheme = () => {
    // Check if user has a saved preference
    const savedTheme = localStorage.getItem('theme');

    if (savedTheme) {
        // Apply saved preference
        if (savedTheme === 'dark') {
            body.classList.add('dark');
        } else {
            body.classList.remove('dark');
        }
    } else {
        // Otherwise use system preference
        const darkModePreferred = window.matchMedia('(prefers-color-scheme: dark)').matches;
        if (darkModePreferred) {
            body.classList.add('dark');
        }
    }
};

// Document ready - initialize all features
document.addEventListener('DOMContentLoaded', () => {
    // Initialize mobile toggle first
    mobileToggle = createMobileToggle();

    // Initialize rest of the features
    initMobileMenu();
    initActiveNav();
    enableSmoothScroll();
    enableTouchGestures();
    initAccessibility();
    enableTaskStatusToggle();
    detectColorScheme();

    // Load saved data if available
    loadSavedData();

    // Ensure mobile toggle is shown on small screens
    if (window.innerWidth <= 576) {
        if (mobileToggle && !sidebar.classList.contains('active')) {
            mobileToggle.style.display = 'flex';
        }
    }
});

// Load any saved data from localStorage
function loadSavedData() {
    // Load profile picture
    const savedProfilePic = localStorage.getItem('userProfilePic');
    if (savedProfilePic && profilePic) {
        profilePic.src = savedProfilePic;
    }

    // Load user information
    const savedUserInfo = localStorage.getItem('userInfo');
    if (savedUserInfo) {
        const userInfo = JSON.parse(savedUserInfo);

        // Update form values
        if (document.getElementById('firstName')) {
            document.getElementById('firstName').value = userInfo.firstName || '';
        }
        if (document.getElementById('lastName')) {
            document.getElementById('lastName').value = userInfo.lastName || '';
        }
        if (document.getElementById('email')) {
            document.getElementById('email').value = userInfo.email || '';
        }
        if (document.getElementById('phone')) {
            document.getElementById('phone').value = userInfo.phone || '';
        }

        // Update displayed information
        if (userInfo.firstName && userInfo.lastName) {
            updateProfileInfo(
                userInfo.firstName,
                userInfo.lastName,
                userInfo.email,
                userInfo.phone
            );
        }
    }

    // Load task statuses
    const taskStatus = JSON.parse(localStorage.getItem('taskStatus') || '{}');
    document.querySelectorAll('.tasks li').forEach(taskItem => {
        const taskTitle = taskItem.querySelector('.task-title').textContent;
        const checkIcon = taskItem.querySelector('.check-icon');

        if (taskStatus[taskTitle]) {
            taskItem.classList.add('task-completed');

            // Also update the visual style
            if (checkIcon) {
                checkIcon.style.backgroundColor = 'var(--purple-accent)';
                checkIcon.style.color = 'white';
            }
        }
    });
}