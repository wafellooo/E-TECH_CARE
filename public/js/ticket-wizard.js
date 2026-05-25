/**
 * ══════════════════════════════════════════════════════════════════════════
 * BU CARE SYSTEM — ticket-wizard.js
 * Client-Side Structural Progressing and Multi-Tier Relational Cascades
 * ══════════════════════════════════════════════════════════════════════════
 */

// Mock Dynamic Location Database Matrix Mapping for Cascade Mechanics
const LOCATION_HIERARCHY_DATA = {
    "MAIN": {
        name: "Main Campus",
        colleges: {
            "CS": {
                name: "College of Science",
                buildings: {
                    "CSB1": {
                        name: "CS Building 1 (A)",
                        rooms: [
                            { id: "RM101", name: "Computer Lab 1", floor: "1", type: "Laboratory" },
                            { id: "RM102", name: "Lecture Room 102", floor: "1", type: "Classroom" },
                            { id: "RMFAC", name: "Faculty Lounge", floor: "2", type: "Office" }
                        ]
                    }
                }
            },
            "CENG": {
                name: "College of Engineering",
                buildings: {
                    "ENG_LAB": {
                        name: "Engineering Mechanics Lab Bldg",
                        rooms: [
                            { id: "RM_MEC", name: "Simulation Center", floor: "1", type: "Laboratory" }
                        ]
                    }
                }
            }
        }
    },
    "EAST": {
        name: "East Campus",
        colleges: {
            "CIT": {
                name: "College of Industrial Technology",
                buildings: {
                    "CIT_MAIN": {
                        name: "CIT Main Training Complex",
                        rooms: [
                            { id: "WS_AUTO", name: "Automotive Tech Bay", floor: "1", type: "Workshop" }
                        ]
                    }
                }
            }
        }
    }
};

document.addEventListener('DOMContentLoaded', function() {
    let currentStepIndex = 1;
    const totalWizardSteps = 5;

    // Collect DOM Selectors
    const panels = document.querySelectorAll('.wizard-panel');
    const crumbs = document.querySelectorAll('.step-crumb');
    const btnPrev = document.getElementById('btnPrevStep');
    const btnNext = document.getElementById('btnNextStep');
    const stepLabel = document.getElementById('wizardStepIndicatorString');

    // Category Select Elements
    const categoryCards = document.querySelectorAll('.category-card');
    const hiddenCategoryInput = document.getElementById('p_category');

    // Device Entity Select Elements
    const toggleRegistered = document.getElementById('toggleRegistered');
    const toggleUnregistered = document.getElementById('toggleUnregistered');
    const hiddenDeviceEntityInput = document.getElementById('device_entity_type');
    const boxRegisteredSelect = document.getElementById('boxRegisteredSelect');
    const boxUnregisteredInput = document.getElementById('boxUnregisteredInput');

    // Location Cascade Dropdown Elements
    const selectCampus = document.getElementById('campus_id');
    const selectCollege = document.getElementById('college_id');
    const selectBuilding = document.getElementById('building_id');
    const selectSpace = document.getElementById('space_config_id');

    // Final Validation Handles
    const pDescText = document.getElementById('p_desc');

    /**
     * Stepper Navigation Sync Controller Engine
     */
    function updateWizardUI() {
        // Manage Panel visibilities
        panels.forEach(p => p.classList.remove('active'));
        document.getElementById(`stepPanel_${currentStepIndex}`).classList.add('active');

        // Manage Step Breadcrumbs Active states
        crumbs.forEach((c, idx) => {
            const currentCrumbIndex = idx + 1;
            c.classList.remove('active', 'completed');
            if (currentCrumbIndex === currentStepIndex) {
                c.classList.add('active');
            } else if (currentCrumbIndex < currentStepIndex) {
                c.classList.add('completed');
            }
        });

        // Toggle button labels and navigation restrictions
        btnPrev.style.visibility = (currentStepIndex === 1) ? 'hidden' : 'visible';
        stepLabel.textContent = `Step ${currentStepIndex} of ${totalWizardSteps}`;

        if (currentStepIndex === totalWizardSteps) {
            btnNext.innerHTML = '<i class="fa-solid fa-cloud-arrow-up me-2"></i>Execute Submission';
            compilePreFlightReviewSummary();
        } else {
            btnNext.innerHTML = 'Advance Next <i class="fa-solid fa-arrow-right ms-2"></i>';
        }
    }

    /**
     * Step Validation Engine Core
     */
    function validateCurrentStep() {
        if (currentStepIndex === 1) {
            if (!hiddenCategoryInput.value) {
                alert("QA Alert: Select an incident problem category profile before advancing.");
                return false;
            }
        }
        if (currentStepIndex === 2) {
            if (pDescText.value.trim().length < 10) {
                alert("QA Alert: Description entry must contain a minimum of 10 structured characters.");
                return false;
            }
        }
        if (currentStepIndex === 3) {
            if (hiddenDeviceEntityInput.value === 'registered' && !document.getElementById('model_id').value) {
                alert("QA Alert: Please select an active registered terminal device profile from your database ledger entries.");
                return false;
            }
            if (hiddenDeviceEntityInput.value === 'unregistered' && !document.getElementById('manual_model_name').value.trim()) {
                alert("QA Alert: Input the model nomenclature parameters manually for this unregistered asset item.");
                return false;
            }
        }
        if (currentStepIndex === 4) {
            if (!selectCampus.value || !selectCollege.value || !selectBuilding.value || !selectSpace.value) {
                alert("QA Alert: Complete full relocation cascade attributes path parameters to isolate hardware position coordinates.");
                return false;
            }
        }
        return true;
    }

    // Attach Stepper Navigation Click Bindings
    btnNext.addEventListener('click', function() {
        if (!validateCurrentStep()) return;

        if (currentStepIndex < totalWizardSteps) {
            currentStepIndex++;
            updateWizardUI();
        } else {
            // Process transactional save operation
            document.getElementById('institutionalWizardMasterForm').submit();
        }
    });

    btnPrev.addEventListener('click', function() {
        if (currentStepIndex > 1) {
            currentStepIndex--;
            updateWizardUI();
        }
    });

    /**
     * Category Grid Context Card Selection Logic
     */
    categoryCards.forEach(card => {
        card.addEventListener('click', function() {
            categoryCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            hiddenCategoryInput.value = this.getAttribute('data-value');
        });
    });

    /**
     * Dynamic Entity Select Option Switch Engine
     */
    function setDeviceEntityTypeMode(mode) {
        hiddenDeviceEntityInput.value = mode;
        if (mode === 'registered') {
            toggleRegistered.classList.add('active');
            toggleUnregistered.classList.remove('active');
            boxRegisteredSelect.classList.remove('d-none');
            boxUnregisteredInput.classList.add('d-none');
        } else {
            toggleRegistered.classList.remove('active');
            toggleUnregistered.classList.add('active');
            boxRegisteredSelect.classList.add('d-none');
            boxUnregisteredInput.classList.remove('d-none');
        }
    }
    toggleRegistered.addEventListener('click', () => setDeviceEntityTypeMode('registered'));
    toggleUnregistered.addEventListener('click', () => setDeviceEntityTypeMode('unregistered'));

    /**
     * Location Hierarchy Cascading Dropdown Engines
     */
    selectCampus.addEventListener('change', function() {
        selectCollege.innerHTML = '<option value="" selected disabled>Select college division...</option>';
        selectBuilding.innerHTML = '<option value="" selected disabled>Awaiting building link...</option>';
        selectSpace.innerHTML = '<option value="" selected disabled>Awaiting spatial coordinates...</option>';
        
        const campusKey = this.value;
        if (!campusKey || !LOCATION_HIERARCHY_DATA[campusKey]) return;

        const colleges = LOCATION_HIERARCHY_DATA[campusKey].colleges;
        for (let key in colleges) {
            let opt = document.createElement('option');
            opt.value = key;
            opt.textContent = colleges[key].name;
            selectCollege.appendChild(opt);
        }
    });

    selectCollege.addEventListener('change', function() {
        selectBuilding.innerHTML = '<option value="" selected disabled>Select structural block facility...</option>';
        selectSpace.innerHTML = '<option value="" selected disabled>Awaiting spatial coordinates...</option>';
        
        const campusKey = selectCampus.value;
        const collegeKey = this.value;
        if (!collegeKey || !LOCATION_HIERARCHY_DATA[campusKey].colleges[collegeKey]) return;

        const buildings = LOCATION_HIERARCHY_DATA[campusKey].colleges[collegeKey].buildings;
        for (let key in buildings) {
            let opt = document.createElement('option');
            opt.value = key;
            opt.textContent = buildings[key].name;
            selectBuilding.appendChild(opt);
        }
    });

    selectBuilding.addEventListener('change', function() {
        selectSpace.innerHTML = '<option value="" selected disabled>Select precise destination coordinate location...</option>';
        
        const campusKey = selectCampus.value;
        const collegeKey = selectCollege.value;
        const bldgKey = this.value;
        if (!bldgKey || !LOCATION_HIERARCHY_DATA[campusKey].colleges[collegeKey].buildings[bldgKey]) return;

        const spaces = LOCATION_HIERARCHY_DATA[campusKey].colleges[collegeKey].buildings[bldgKey].rooms;
        spaces.forEach(sp => {
            let opt = document.createElement('option');
            opt.value = sp.id;
            opt.textContent = `${sp.name} — Floor ${sp.floor} (${sp.type})`;
            selectSpace.appendChild(opt);
        });
    });

    /**
     * Pre-Flight Review Matrix Compilation Data Sync
     */
    function compilePreFlightReviewSummary() {
        document.getElementById('rv_category').textContent = hiddenCategoryInput.value.toUpperCase();
        document.getElementById('rv_desc').textContent = pDescText.value;
        
        if (hiddenDeviceEntityInput.value === 'registered') {
            const selDev = document.getElementById('model_id');
            document.getElementById('rv_device').textContent = `[Registered Profile Pool] — ${selDev.options[selDev.selectedIndex].text}`;
        } else {
            document.getElementById('rv_device').textContent = `[Guest Asset Unlisted Row] — Type: ${document.getElementById('manual_device_type').value.toUpperCase()} / Model: ${document.getElementById('manual_model_name').value}`;
        }

        const cmp = selectCampus.options[selectCampus.selectedIndex].text;
        const col = selectCollege.options[selectCollege.selectedIndex].text;
        const bld = selectBuilding.options[selectBuilding.selectedIndex].text;
        const spc = selectSpace.options[selectSpace.selectedIndex].text;
        document.getElementById('rv_location').textContent = `${cmp} ➔ ${col} ➔ ${bld} ➔ ${spc}`;
    }

    // Run initialization mapping routine checks
    updateWizardUI();
});