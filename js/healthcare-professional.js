// Array of healthcare professional roles
const healthcareProfessionalRoles = ["Cardiologist", "Medical Technologist", "Pathologist", "Physician", "Radiologist", "X-Ray Technologist"];

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

function setRoleSelect(listProfessionals, selectProfessionals, selectedProfessional, filterRole) {
    selectProfessionals.innerHTML = "";

    const defaultOption = document.createElement("option");
    defaultOption.value = "";
    defaultOption.textContent = "Select";
    defaultOption.disabled = true;
    
    if (selectedProfessional === '' || selectedProfessional === '0') {
        defaultOption.selected = true;
    }
    selectProfessionals.appendChild(defaultOption);
    console.log(listProfessionals);
    const filteredProfessionals = listProfessionals.filter(prof => prof.prof_role === filterRole);

    filteredProfessionals.forEach(professional => {
        const optionElement = document.createElement("option");
        optionElement.value = professional.prof_id;
        optionElement.textContent = professional.prof_name;

        if (optionElement.value === selectedProfessional) {
            optionElement.selected = true;
        }

        selectProfessionals.appendChild(optionElement);
    });
}