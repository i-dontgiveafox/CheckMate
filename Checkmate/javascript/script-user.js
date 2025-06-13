// DOM Elements - Keep original structure but organize better
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
const viewTasksBtn = document.querySelector('.view-tasks-btn');
const tasksPopup = document.getElementById('tasksPopup');
const closeTasksPopupBtn = document.getElementById('closeTasksPopup');
const tasksListContainer = document.querySelector('.tasks-list-container');

let mobileToggle;

function getCurrentAccountId() {
    // Pwede mo gamitin yung email as unique identifier
    const emailInput = document.getElementById('email');
    if (emailInput && emailInput.value) {
        return emailInput.value;
    }
    // Or kung may ibang way ka na makuha yung user ID
    return 'default_user'; // fallback
}

// Utility Functions
function showToast(message, duration = 3000) {
    let toastContainer = document.querySelector('.toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        toastContainer.style.position = 'fixed';
        toastContainer.style.bottom = '20px';
        toastContainer.style.right = '20px';
        toastContainer.style.zIndex = '9999';
        document.body.appendChild(toastContainer);
    }

    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;
    toast.style.backgroundColor = 'var(--purple-accent)';
    toast.style.color = 'white';
    toast.style.padding = '12px 20px';
    toast.style.borderRadius = '6px';
    toast.style.marginTop = '10px';
    toast.style.boxShadow = '0 3px 10px rgba(0,0,0,0.2)';
    toast.style.opacity = '0';
    toast.style.transition = 'opacity 0.3s ease';

    toastContainer.appendChild(toast);
    setTimeout(() => toast.style.opacity = '1', 10);
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// Validation Functions
function validateEmail(email) {
    if (!email?.trim()) return { valid: false, message: 'Email address is required.' };
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailRegex.test(email)) return { valid: false, message: 'Please enter a valid email format.' };
    const domain = email.split('@')[1].toLowerCase();
    
    const typos = ['gmail.co', 'yahoo.co', 'outlook.co', 'hotmail.co', 'gmail.con', 'yahoo.con'];
    if (typos.includes(domain)) return { valid: false, message: 'Invalid email address. Please check your email.' };

    const validDomains = /^(gmail|yahoo|outlook|hotmail|icloud|protonmail|aol|live)\.com$|^yahoo\.com\.ph$|\.ph$|\.com\.ph$|\.edu\.ph$|\.gov\.ph$/;
    if (!validDomains.test(domain)) return { valid: false, message: 'Please use Gmail, Yahoo, Outlook, or Philippine domains (.ph).' };

    return { valid: true };
}

// Tasks Popup Functionality
if (viewTasksBtn && tasksPopup) {
    viewTasksBtn.addEventListener('click', () => {
        const existingTasks = document.querySelector('.tasks ul');
        if (existingTasks) {
            tasksListContainer.innerHTML = '';
            const tasksList = existingTasks.cloneNode(true);
            tasksListContainer.appendChild(tasksList);
            tasksPopup.style.display = 'flex';
            enableTaskCheckInPopup();
        }
    });
}

if (closeTasksPopupBtn && tasksPopup) {
    closeTasksPopupBtn.addEventListener('click', () => {
        tasksPopup.style.display = 'none';
    });
}

if (tasksPopup) {
    tasksPopup.addEventListener('click', (e) => {
        if (e.target === tasksPopup) {
            tasksPopup.style.display = 'none';
        }
    });

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && tasksPopup.style.display === 'flex') {
            tasksPopup.style.display = 'none';
        }
    });
}

function enableTaskCheckInPopup() {
    const checkIcons = tasksPopup.querySelectorAll('.check-icon');
    checkIcons.forEach(icon => {
        icon.addEventListener('click', function () {
            const taskItem = this.closest('li');
            const taskTitle = taskItem.querySelector('.task-title').textContent;
            taskItem.classList.toggle('task-completed');

            if (taskItem.classList.contains('task-completed')) {
                this.style.backgroundColor = 'var(--purple-accent)';
                this.style.color = 'white';
            } else {
                this.style.backgroundColor = 'transparent';
                this.style.color = 'var(--purple-accent)';
            }

            const taskStatus = JSON.parse(localStorage.getItem('taskStatus') || '{}');
            taskStatus[taskTitle] = taskItem.classList.contains('task-completed');
            localStorage.setItem('taskStatus', JSON.stringify(taskStatus));
            updateMainTaskList(taskTitle, taskItem.classList.contains('task-completed'));
            showToast(`Task ${taskStatus[taskTitle] ? 'completed' : 'reopened'}!`, 1500);
        });
    });
}

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

// Mobile Toggle and Overlay
const createOverlay = () => {
    const overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.remove('active');
        if (mobileToggle) mobileToggle.style.display = 'flex';
    });
    return overlay;
};

const overlay = createOverlay();

const createMobileToggle = () => {
    let mobileToggleBtn = document.querySelector('.mobile-toggle');
    if (mobileToggleBtn) return mobileToggleBtn;

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

// Sidebar Toggle
toggle.addEventListener("click", () => {
    sidebar.classList.toggle("close");
});

// Search Box
if (searchBox) {
    searchBox.addEventListener("click", () => {
        if (window.innerWidth > 576) {
            sidebar.classList.remove("close");
        }
    });
}

// Profile Picture Handling
if (profilePic && fileInput) {
    profilePic.addEventListener('click', () => fileInput.click());
}

if (editProfileBtn && fileInput) {
    editProfileBtn.addEventListener('click', () => fileInput.click());
}

if (fileInput) {
    fileInput.addEventListener('change', (event) => {
        const file = event.target.files[0];
        if (file) {
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file.');
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                alert('Image size should be less than 5MB.');
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                profilePic.src = e.target.result;
                const accountId = getCurrentAccountId(); // need mo idagdag tong function
                localStorage.setItem(`userProfilePic_${accountId}`, e.target.result);
                showToast('Profile picture updated successfully!');
            };
            reader.readAsDataURL(file);
        }
    });
}

// Edit Profile Popup
if (editInfoBtn && popup) {
    editInfoBtn.addEventListener('click', () => {
        popup.style.display = 'flex';
        const confirmationNotice = document.getElementById('emailConfirmationNotice');
        if (confirmationNotice) {
            confirmationNotice.style.display = 'none';
        }
        const firstInput = popup.querySelector('input');
        if (firstInput) {
            setTimeout(() => firstInput.focus(), 100);
        }
    });
}

if (closePopupBtn && popup) {
    closePopupBtn.addEventListener('click', () => {
        popup.style.display = 'none';
    });
}

if (popup) {
    window.addEventListener('click', (e) => {
        if (e.target === popup) {
            popup.style.display = 'none';
        }
    });

    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && popup.style.display === 'flex') {
            popup.style.display = 'none';
        }
    });
}

// Form Submission
if (editForm) {
    editForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const firstName = document.getElementById('firstName').value.trim();
        const lastName = document.getElementById('lastName').value.trim();
        const email = document.getElementById('email').value.trim();
        const phone = document.getElementById('phone').value.trim();

        const nameRegex = /^[A-Za-z\s\-']+$/;
        if (!firstName || !nameRegex.test(firstName)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid First Name',
                text: 'Please enter a valid first name (letters, spaces, hyphens, and apostrophes only).',
                confirmButtonColor: 'var(--purple-accent)'
            });
            return;
        }

        if (!lastName || !nameRegex.test(lastName)) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Last Name',
                text: 'Please enter a valid last name (letters, spaces, hyphens, and apostrophes only).',
                confirmButtonColor: 'var(--purple-accent)'
            });
            return;
        }

        const result = validateEmail(email);
        if (!result.valid) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid Email',
                text: result.message,
                confirmButtonColor: 'var(--purple-accent)'
            });
            return;
        }

        if (phone) {
            const phoneDigits = phone.replace(/\D/g, '');
            if (phoneDigits.length !== 11) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Phone Number',
                    text: 'Phone number must be exactly 11 digits.',
                    confirmButtonColor: 'var(--purple-accent)'
                });
                return;
            }
            if (!phoneDigits.startsWith('09')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid Phone Number',
                    text: 'Phone number must start with 09 (e.g., 09171234567).',
                    confirmButtonColor: 'var(--purple-accent)'
                });
                return;
            }
        }

        const originalEmail = document.getElementById('originalEmail').value;
        const hasEmailChanged = email !== originalEmail;

        if (hasEmailChanged) {
            const result = await Swal.fire({
                icon: 'info',
                title: 'Email Confirmation Required',
                html: `
                    <p>You are changing your email address from:</p>
                    <p><strong>${originalEmail}</strong></p>
                    <p>to:</p>
                    <p><strong>${email}</strong></p>
                    <br>
                    <p>A confirmation email will be sent to your new email address for security purposes.</p>
                `,
                showCancelButton: true,
                confirmButtonText: 'Continue & Send Confirmation',
                cancelButtonText: 'Cancel',
                confirmButtonColor: 'var(--purple-accent)',
                cancelButtonColor: '#6c757d'
            });

            if (!result.isConfirmed) {
                const submitBtn = editForm.querySelector('.save-btn');
                const originalText = submitBtn.textContent;
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                return;
            }
        }

        const submitBtn = editForm.querySelector('.save-btn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Saving...';
        submitBtn.disabled = true;

        try {
            const formData = new FormData();
            formData.append('firstName', firstName);
            formData.append('lastName', lastName);
            formData.append('email', email);
            formData.append('phone', phone);

            const response = await fetch('update_profile.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                updateProfileInfo(firstName, lastName, email, phone);
                popup.style.display = 'none';
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: result.message || 'Profile updated successfully!',
                    confirmButtonColor: 'var(--purple-accent)',
                    timer: 2000,
                    showConfirmButton: false
                });
                const userInfo = { firstName, lastName, email, phone };
                localStorage.setItem('userInfo', JSON.stringify(userInfo));
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed',
                    text: result.message || 'Failed to update profile. Please try again.',
                    confirmButtonColor: 'var(--purple-accent)'
                });
            }
        } catch (error) {
            console.error('Error updating profile:', error);
            Swal.fire({
                icon: 'error',
                title: 'Network Error',
                text: 'Could not connect to server. Please check your internet connection and try again.',
                confirmButtonColor: 'var(--purple-accent)'
            });
        } finally {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    });
}

function updateProfileInfo(firstName, lastName, email, phone) {
    const nameElement = document.querySelector('.header-text .name');
    if (nameElement) {
        nameElement.textContent = `${firstName} ${lastName.charAt(0)}.`;
    }

    const profileName = document.querySelector('.profile-info h2');
    if (profileName) {
        profileName.textContent = `${firstName} ${lastName.charAt(0)}.`;
    }

    const infoItems = document.querySelectorAll('.info-item');
    if (infoItems.length >= 4) {
        infoItems[0].querySelector('span').textContent = firstName;
        infoItems[1].querySelector('span').textContent = lastName;
        infoItems[2].querySelector('span').textContent = email;
        infoItems[3].querySelector('span').textContent = phone;
    }
}

// Task Status Toggle
const enableTaskStatusToggle = () => {
    const checkIcons = document.querySelectorAll('.check-icon');
    checkIcons.forEach(icon => {
        icon.addEventListener('click', function () {
            const taskItem = this.closest('li');
            const taskTitle = taskItem.querySelector('.task-title').textContent;
            taskItem.classList.toggle('task-completed');

            if (taskItem.classList.contains('task-completed')) {
                this.style.backgroundColor = 'var(--purple-accent)';
                this.style.color = 'white';
            } else {
                this.style.backgroundColor = 'transparent';
                this.style.color = 'var(--purple-accent)';
            }

            const taskStatus = JSON.parse(localStorage.getItem('taskStatus') || '{}');
            taskStatus[taskTitle] = taskItem.classList.contains('task-completed');
            localStorage.setItem('taskStatus', JSON.stringify(taskStatus));
            showToast(`Task ${taskStatus[taskTitle] ? 'completed' : 'reopened'}!`, 1500);
        });
    });
};

// Email Resend Function
async function resendEmailConfirmation() {
    try {
        const response = await fetch('resend_confirmation.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });
        const result = await response.json();
        
        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'Email Sent!',
                text: 'Confirmation email has been resent to your email address.',
                confirmButtonColor: 'var(--purple-accent)'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Failed to Send',
                text: result.message || 'Could not send confirmation email.',
                confirmButtonColor: 'var(--purple-accent)'
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Network Error',
            text: 'Could not send confirmation email. Please try again.',
            confirmButtonColor: 'var(--purple-accent)'
        });
    }
}

// Window Resize Handler
window.addEventListener('resize', () => {
    if (window.innerWidth > 576) {
        overlay.classList.remove('active');
        if (mobileToggle) mobileToggle.style.display = 'none';
        if (toggle) toggle.style.display = 'block';
        sidebar.classList.remove('active');
    } else {
        if (!mobileToggle) {
            mobileToggle = createMobileToggle();
        }
        if (toggle) toggle.style.display = 'none';
        if (mobileToggle && !sidebar.classList.contains('active')) {
            mobileToggle.style.display = 'flex';
        } else if (mobileToggle) {
            mobileToggle.style.display = 'none';
        }
        sidebar.classList.remove('active');
    }
});

// Touch Gestures
const enableTouchGestures = () => {
    let touchStartX = 0;
    let touchEndX = 0;
    const minSwipeDistance = 70;

    document.addEventListener('touchstart', e => {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    document.addEventListener('touchend', e => {
        touchEndX = e.changedTouches[0].screenX;
        const swipeDistance = Math.abs(touchEndX - touchStartX);

        if (swipeDistance < minSwipeDistance) return;

        if (touchEndX - touchStartX > minSwipeDistance && touchStartX < 50 && window.innerWidth <= 576) {
            sidebar.classList.add('active');
            overlay.classList.add('active');
            if (mobileToggle) mobileToggle.style.display = 'none';
        }

        if (touchStartX - touchEndX > minSwipeDistance && sidebar.classList.contains('active') && window.innerWidth <= 576) {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            if (mobileToggle) mobileToggle.style.display = 'flex';
        }
    }, { passive: true });
};

// Load Saved Data
function loadSavedData() {
    const accountId = getCurrentAccountId();
    const savedProfilePic = localStorage.getItem(`userProfilePic_${accountId}`);
    if (savedProfilePic && profilePic) {
        profilePic.src = savedProfilePic;
    }

    const savedUserInfo = localStorage.getItem('userInfo');
    if (savedUserInfo) {
        const userInfo = JSON.parse(savedUserInfo);
        if (document.getElementById('firstName') && !document.getElementById('firstName').value) {
            document.getElementById('firstName').value = userInfo.firstName || '';
        }
        if (document.getElementById('lastName') && !document.getElementById('lastName').value) {
            document.getElementById('lastName').value = userInfo.lastName || '';
        }
        if (document.getElementById('email') && !document.getElementById('email').value) {
            document.getElementById('email').value = userInfo.email || '';
        }
        if (document.getElementById('phone') && !document.getElementById('phone').value) {
            document.getElementById('phone').value = userInfo.phone || '';
        }
    }

    const taskStatus = JSON.parse(localStorage.getItem('taskStatus') || '{}');
    document.querySelectorAll('.tasks li').forEach(taskItem => {
        const taskTitle = taskItem.querySelector('.task-title').textContent;
        const checkIcon = taskItem.querySelector('.check-icon');

        if (taskStatus[taskTitle]) {
            taskItem.classList.add('task-completed');
            if (checkIcon) {
                checkIcon.style.backgroundColor = 'var(--purple-accent)';
                checkIcon.style.color = 'white';
            }
        }
    });
}

// Initialize on DOM Load
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('email_confirmed') === 'true') {
        Swal.fire({
            icon: 'success',
            title: 'Email Confirmed!',
            text: 'Your email has been successfully confirmed.',
            confirmButtonColor: 'var(--purple-accent)'
        });
    } else if (urlParams.get('email_confirmed') === 'false') {
        Swal.fire({
            icon: 'error',
            title: 'Confirmation Failed',
            text: 'Email confirmation failed or link has expired.',
            confirmButtonColor: 'var(--purple-accent)'
        });
    }
    
    mobileToggle = createMobileToggle();
    enableTaskStatusToggle();
    enableTouchGestures();
    loadSavedData();
});