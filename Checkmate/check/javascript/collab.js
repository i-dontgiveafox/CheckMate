document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("createTeamForm");
    const groupTasks = document.querySelector(".group-tasks");
    const modal = document.getElementById("modal");
    const addMemberBtn = document.getElementById("addMemberBtn");
    const memberContainer = document.getElementById("memberContainer");
    const deleteModal = document.getElementById("deleteModal");
    let selectedTeamId = null;
    let selectedContainer = null;

    // Open modal
    const createGroupButton = document.getElementById("openModalBtn");
    createGroupButton?.addEventListener("click", () => {
        modal.style.display = "flex";
    });

    // Close modal
    const closeBtn = document.getElementById("closeModalBtn");
    closeBtn?.addEventListener("click", () => {
        modal.style.display = "none";
    });
    document.querySelector(".group-tasks").addEventListener("click", async (e) => {
        if (e.target.classList.contains("remove-member-btn")) {
            const memberEl = e.target.closest(".member");
            const memberId = memberEl.getAttribute("data-member-id");
            if (!memberId) return;

            const confirmed = confirm("Are you sure you want to remove this member?");
            if (!confirmed) return;

            try {
                const res = await fetch(`remove_member.php?id=${memberId}`, {
                    method: "DELETE",
                });

                const text = await res.text();
                console.log("Server:", text);

                if (res.ok) {
                    memberEl.remove();
                } else {
                    alert("Failed to remove member.");
                }
            } catch (err) {
                console.error("Error:", err);
                alert("An error occurred while removing the member.");
            }
        }
    });

    window.addEventListener("click", (e) => {
        if (e.target === modal) modal.style.display = "none";
    });

    // Submit form
    form?.addEventListener("submit", async (e) => {
        e.preventDefault();
        const formData = new FormData(form);

        try {
            const res = await fetch("save_team.php", {
                method: "POST",
                body: formData,
            });

            const text = await res.text();
            let data;
            try {
                const data = JSON.parse(text);
            } catch (error) {
                console.error("Failed to parse JSON:", text);
                alert("Server Error: " + text); // Shows error instead of breaking the script
                return;
            }


            if (!res.ok || data.error || !data.id) {
                alert("Failed to create team: " + data.error);
                return;
            }

            modal.style.display = "none";

            // Create team container
            const wrapper = document.createElement("div");
            wrapper.className = "team-link";

            const container = document.createElement("div");
            container.className = "team-container";
            container.setAttribute("data-id", data.id);
            container.style.background = data.color;

            container.innerHTML = `
                <div class="team-header" style="display:flex; justify-content:space-between;">
                    <h2>${data.name}</h2>
                    <div style="display:flex; flex-direction:column; gap:10px; align-items:flex-end;">
                        <button class="delete-team" data-id="${data.id}" style="background:none; border:none; font-size:18px; color:white; cursor:pointer;">✖</button>
                        <a href="team_page.php?id=${data.id}" class="enter-team" style="color:white; font-size:16px;"><i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="team-members">
                    ${data.members.length > 0
                    ? data.members.map(member => `
                            <div class="member">
                                <img src="${member.image}" alt="${member.name}">
                                <span>${member.name}</span>
                            </div>
                        `).join("")
                    : "<p>No members added yet.</p>"
                }
                </div>
            `;

            wrapper.appendChild(container);
            groupTasks.appendChild(wrapper);

            form.reset();
            memberContainer.innerHTML = `
                <div class="member-block" style="display:flex; gap:10px; margin-top:10px; align-items:center;">
                    <input type="text" name="members[]" placeholder="Member Name" required style="flex:2; padding:10px; border-radius:8px; border:1px solid #ccc;">
                    <input type="file" name="member_images[]" accept="image/*" required title="Upload profile image" style="flex:2;">
                    <button type="button" class="removeMember" style="background:#e74c3c; color:white; border:none; padding:10px; border-radius:50%;">×</button>
                </div>
            `;
        } catch (err) {
            console.error("Unexpected error:", err);
            alert("Unexpected error. Please check console.");
        }
    });

    // Add member block
    addMemberBtn?.addEventListener("click", () => {
        const div = document.createElement("div");
        div.className = "member-block";
        div.style = "display:flex; gap:10px; margin-top:10px; align-items:center;";
        div.innerHTML = `
            <input type="text" name="members[]" placeholder="Member Name" required style="flex:2; padding:10px; border-radius:8px; border:1px solid #ccc;">
            <input type="file" name="member_images[]" accept="image/*" required title="Upload profile image" style="flex:2;">
            <button type="button" class="removeMember" style="background:#e74c3c; color:white; border:none; padding:10px; border-radius:50%;">×</button>
        `;
        memberContainer.appendChild(div);
    });

    // Remove member block
    memberContainer?.addEventListener("click", (e) => {
        if (e.target.classList.contains("removeMember")) {
            e.target.parentElement.remove();
        }
    });

    // Open delete modal and track selected container
    document.body.addEventListener("click", (e) => {
        const deleteBtn = e.target.closest(".delete-team");
        if (deleteBtn) {
            e.stopPropagation();
            selectedTeamId = deleteBtn.getAttribute("data-id");
            selectedContainer = deleteBtn.closest(".team-link") || deleteBtn.closest(".team-container");
            deleteModal.style.display = "flex";
        }
    });

    // Confirm deletion
    const confirmDelete = document.getElementById("confirmDelete");
    confirmDelete?.addEventListener("click", async () => {
        if (!selectedTeamId || !selectedContainer) return;

        try {
            const res = await fetch(`delete_team.php?id=${selectedTeamId}`, { method: "DELETE" });
            const text = await res.text();
            const result = JSON.parse(text);

            if (result.status === "success") {
                selectedContainer.remove();
                deleteModal.style.display = "none";
            } else {
                alert("Failed to delete team");
            }
        } catch (err) {
            console.error("Error deleting team:", err);
            alert("Something went wrong while deleting the team.");
        }
    });

    // Cancel delete
    const cancelDelete = document.getElementById("cancelDelete");
    cancelDelete?.addEventListener("click", () => {
        deleteModal.style.display = "none";
        selectedTeamId = null;
        selectedContainer = null;
    });
});

document.body.addEventListener('click', function (e) {
    if (e.target.closest('.add-member-btn')) {
        const teamId = e.target.closest('.add-member-btn').dataset.teamId;
        document.getElementById('addTeamId').value = teamId;
        document.getElementById('addMemberModal').style.display = 'flex';
    }
});

document.getElementById('cancelAddMember').addEventListener('click', function () {
    document.getElementById('addMemberModal').style.display = 'none';
});

document.getElementById('addMemberForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    const formData = new FormData(this);

    try {
        const res = await fetch('add_member.php', {
            method: 'POST',
            body: formData
        });

        const data = await res.json(); // ONLY call .json() once

        if (data.success) {
            const container = document.querySelector(`.team-container[data-id="${formData.get('team_id')}"] .team-members`);
            if (container) {
                const member = document.createElement('div');
                member.classList.add('member');
                member.innerHTML = `
                    <img src="${data.member.image}" alt="${data.member.name}" />
                    <span>${data.member.name}</span>
                    <button class="remove-member-btn" data-member-id="${data.member.id}">×</button>
                `;
                container.appendChild(member);
            }
            document.getElementById('addMemberModal').style.display = 'none';
            this.reset();
        } else {
            alert(data.error || 'Failed to add member');
        }
    } catch (err) {
        console.error('Error:', err);
    }
});


document.addEventListener("DOMContentLoaded", function () {
    const body = document.querySelector("body"),
        sidebar = body.querySelector("nav.sidebar"),
        toggle = body.querySelector(".toggle");

    if (toggle) {
        toggle.addEventListener("click", () => {
            sidebar.classList.toggle("close");
        });
    }
});

document.getElementById('addMemberForm').addEventListener('submit', async (e) => {
    e.preventDefault(); // Don't forget this if using fetch!

    const formData = new FormData(e.target);
    const res = await fetch('add_member.php', {
        method: 'POST',
        body: formData
    });

    // Do something with the response...
});

document.getElementById("addMemberForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const teamId = formData.get("team_id");

    fetch("add_member.php", {
        method: "POST",
        body: formData,
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) {
                const teamContainer = document.querySelector(`.team-container[data-id="${teamId}"] .team-members`);

                // Create new member element
                const memberDiv = document.createElement("div");
                memberDiv.classList.add("member");
                memberDiv.innerHTML = `
                <img src="${data.member.image}" alt="${data.member.name}" />
                <span>${data.member.name}</span>
                <button class="remove-member-btn" data-member-id="${data.member.id}" title="Remove">×</button>
            `;
                teamContainer.insertBefore(memberDiv, teamContainer.querySelector(".add-member-btn"));

                // Reset and close modal
                form.reset();
                document.getElementById("addMemberModal").style.display = "none";
            }
        });
});

document.querySelectorAll('.add-member-btn').forEach(button => {
    button.addEventListener('click', () => {
        const teamId = button.getAttribute('data-team-id');
        document.getElementById('addTeamId').value = teamId;
        document.getElementById('addMemberModal').style.display = 'flex';
    });
});

