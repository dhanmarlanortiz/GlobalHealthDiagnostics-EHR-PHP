// Array of healthcare professional roles
const healthcareProfessionalRoles = ["Medical Technologist", "Pathologist", "Physician", "Radiologist", "X-Ray Technologist"];

// Function to populate the select element with healthcare professional roles
function setProfessionalsSelect(selectRoles, selectedRole) {
    // Clear any existing options
    selectRoles.innerHTML = "";

    // Add the default option
    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "Select";
    defaultOption.disabled = true;

    if (selectedRole === '') {
        defaultOption.selected = true;
    }

    selectRoles.appendChild(defaultOption);

    // Add options for each healthcare professional role
    healthcareProfessionalRoles.forEach(role => {
        const optionElement = document.createElement("option");
        optionElement.value = role;
        optionElement.textContent = role;

        if (optionElement.value === selectedRole) {
            optionElement.selected = true;
        }

        selectRoles.appendChild(optionElement);
    });
}

