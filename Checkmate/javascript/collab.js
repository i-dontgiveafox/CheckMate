document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("createTeamForm");
    const groupTasks = document.querySelector(".group-tasks");
    const modal = document.getElementById("modal");
    const addMemberBtn = document.getElementById("addMemberBtn");
    const memberContainer = document.getElementById("memberContainer");
    const deleteModal = document.getElementById("deleteModal");
    let selectedTeamId = null;
    let selectedContainer = null;
    const saveBtn = document.getElementById("saveGroupBtn");

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


    // Set initial button color
    if (saveBtn) {
        saveBtn.style.backgroundColor = "#e74c3c";
        saveBtn.textContent = "Save Team";
    }

    form?.addEventListener("submit", async (e) => {
        e.preventDefault();

        const saveBtn = document.getElementById("saveGroupBtn");

        if (!form.checkValidity()) {
            form.reportValidity();
            saveBtn.style.backgroundColor = "#e74c3c";
            saveBtn.textContent = "Please complete all fields!";

            setTimeout(() => {
                saveBtn.style.backgroundColor = "#e74c3c";
                saveBtn.textContent = "Save Team";
            }, 1000);
            return;
        }

        saveBtn.disabled = true;
        saveBtn.style.backgroundColor = "#3498db";
        saveBtn.textContent = "Saving...";

        const formData = new FormData(form);

        try {
            const res = await fetch("save_team.php", {
                method: "POST",
                body: formData
            });
            const data = await res.json();

            if (!data || data.status !== "success") {
                saveBtn.style.backgroundColor = "#e74c3c";
                saveBtn.textContent = data.error || "Save failed!";
                saveBtn.disabled = false;
                return;
            }

            // Success
            saveBtn.style.backgroundColor = "#27ae60";
            saveBtn.textContent = "Team Saved!";

            // Create DOM element
            const wrapper = document.createElement("div");
            wrapper.className = "team-link";

            const container = document.createElement("div");
            container.className = "team-container";
            container.setAttribute("data-id", data.id);
            container.style.background = data.color;

            const membersHTML = data.members.map(member => `
            <div class="member">
                <img src="${member.image}" alt="${member.name}" />
                <span>${member.name}</span>
            </div>
        `).join("");

            container.innerHTML = `
            <div class="team-header" style="display:flex; justify-content:space-between;">
                <h2>${data.name}</h2>
                <div style="display:flex; flex-direction:column; align-items:flex-end;">
                    <button class="delete-team" data-id="${data.id}" title="Delete team" style="background:none; border:none; font-size:18px; color:white; cursor:pointer;">✖</button>
                    <a href="team_page.php?id=${data.id}" class="enter-team" style="text-decoration:none; color:white;">
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            <div class="team-members">
                ${membersHTML}
                <div class="add-member-btn" data-team-id="${data.id}">+</div>
            </div>
        `;

            wrapper.appendChild(container);
            groupTasks.appendChild(wrapper);

            // Reset form
            form.reset();
            memberContainer.innerHTML = ""; // clear old blocks

            // Add 1 default block
            const block = document.createElement("div");
            block.className = "member-block";
            block.style = "display:flex; gap:10px; margin-top:10px; align-items:center;";
            block.innerHTML = `
            <select name="members[]" required style="flex:2; padding:10px; border-radius:8px; border:1px solid #ccc;">
                <option value="">Select Member</option>
                ${allUsers.map(u => `<option value="${u.user_id}">${u.user_firstname} ${u.user_lastname}</option>`).join("")}
            </select>
            <input type="file" name="member_images[]" accept="image/*" required style="flex:2;" title="Upload image">
            <button type="button" class="removeMember" style="background:#e74c3c; color:white; border:none; padding:10px; border-radius:50%;">×</button>
        `;
            memberContainer.appendChild(block);

            modal.style.display = "none";

            // Revert button after delay
            setTimeout(() => {
                saveBtn.style.backgroundColor = "#e74c3c";
                saveBtn.textContent = "Save Team";
                saveBtn.disabled = false;
            }, 1000);

        } catch (err) {
            console.error("Unexpected error:", err);
            saveBtn.style.backgroundColor = "#e74c3c";
            saveBtn.textContent = "Error!";
            saveBtn.disabled = false;
        }
    });


    // Add member block
    document.addEventListener("click", (e) => {
        if (e.target && e.target.id === "addMemberBtn") {
            const div = document.createElement("div");
            div.className = "member-block";
            div.style = "display:flex; gap:10px; margin-top:10px; align-items:center;";

            const select = document.createElement("select");
            select.name = "members[]";
            select.required = true;
            select.style = "flex:2; padding:10px; border-radius:8px; border:1px solid #ccc;";

            const defaultOption = document.createElement("option");
            defaultOption.value = "";
            defaultOption.textContent = "Select member";
            select.appendChild(defaultOption);

            allUsers.forEach(user => {
                const opt = document.createElement("option");
                opt.value = user.user_id;
                opt.textContent = `${user.user_firstname} ${user.user_lastname}`;
                select.appendChild(opt);
            });
            const file = document.createElement("input");
            file.type = "file";
            file.name = "member_images[]";
            file.accept = "image/*";
            file.required = true;
            file.style = "flex:2";

            const remove = document.createElement("button");
            remove.type = "button";
            remove.textContent = "×";
            remove.className = "removeMember";
            remove.style = "background:#e74c3c; color:white; border:none; padding:10px; border-radius:50%;";

            div.appendChild(select);
            div.appendChild(file);
            div.appendChild(remove);

            document.getElementById("memberContainer").appendChild(div);
        }
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
        })
            .then(res => res.json()) // <--- failing here
            .then(data => {
                if (data.success) {
                    // Update team container with new member
                }
            });
        const result = await res.json();

        if (result.success) {
            // Append new member DOM (optional, or reload)
            location.reload();
        } else {
            alert('Failed to add member');
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


saveBtn?.addEventListener("click", function (e) {
    if (!form.checkValidity()) {
        e.preventDefault();
        form.reportValidity();

        if (saveBtn) {
            saveBtn.style.backgroundColor = "#e74c3c";
        }

        saveBtn.textContent = "Please complete all fields!";

    } else {
        // let the submit event handle success coloring
    }
});

function rebuildMemberBlock() {
    memberContainer.innerHTML = "";

    const block = document.createElement("div");
    block.className = "member-block";
    block.style = "display:flex; gap:10px; margin-top:10px; align-items:center;";

    const select = document.createElement("select");
    select.name = "members[]";
    select.required = true;
    select.style = "flex:2; padding:10px; border-radius:8px; border:1px solid #ccc;";
    select.innerHTML = `<option value="">Select member</option>` + allUsers.map(u =>
        `<option value="${u.user_id}">${u.user_firstname} ${u.user_lastname}</option>`
    ).join("");

    const file = document.createElement("input");
    file.type = "file";
    file.name = "member_images[]";
    file.accept = "image/*";
    file.required = true;
    file.style = "flex:2;";
    file.setAttribute("title", "Upload image");

    const remove = document.createElement("button");
    remove.type = "button";
    remove.className = "removeMember";
    remove.textContent = "×";
    remove.style = "background:#e74c3c; color:white; border:none; padding:10px; border-radius:50%;";

    block.append(select, file, remove);
    memberContainer.appendChild(block);
}
