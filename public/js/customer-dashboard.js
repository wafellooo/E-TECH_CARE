/**
 * Bicol University CARE System — Sandboxed Form Pipeline Controls
 */
document.addEventListener('DOMContentLoaded', function () {
    
    let activeStep = 1;
    const totalSteps = 5;

    const btnNext     = document.getElementById('wz-btn-next');
    const btnBack     = document.getElementById('wz-btn-back');
    const stepHintText = document.getElementById('wz-step-hint');
    const fillLine    = document.getElementById('wz-fill-indicator');

    // --- STEP 1 OPERATION: GRID CARD ACTIVE COMPILING ---
    const visualCards    = document.querySelectorAll('.care-wizard-context .wz-cat-card');
    const hiddenCatInput = document.getElementById('hidden_p_category');

    visualCards.forEach(card => {
        card.addEventListener('click', function () {
            visualCards.forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            hiddenCatInput.value = this.getAttribute('data-cat');
        });
    });

    // --- STEP 2 OPERATION: DATA CHARMETRIC RECORDER ---
    const txtAreaDesc = document.getElementById('p_desc');
    const countBadge  = document.getElementById('wz-counter-badge');

    if (txtAreaDesc && countBadge) {
        txtAreaDesc.addEventListener('input', function () {
            const currentLen = this.value.length;
            countBadge.textContent = `${currentLen} / 500`;
            countBadge.className = (currentLen >= 10 && currentLen <= 500)
                ? "badge bg-success font-monospace text-white"
                : "badge bg-secondary font-monospace text-white";
        });
    }

    // --- STEP 3 OPERATION: SWITCH REGISTERED DEVICE CHANNELS ---
    const rReg   = document.getElementById('dev_reg');
    const rUnreg = document.getElementById('dev_unreg');
    const bReg   = document.getElementById('wz-box-registered');
    const bUnreg = document.getElementById('wz-box-unregistered');

    if (rReg && rUnreg) {
        const resetDeviceInputs = () => {
            if (rReg.checked) {
                bReg.classList.remove('d-none');
                bUnreg.classList.add('d-none');
            } else {
                bReg.classList.add('d-none');
                bUnreg.classList.remove('d-none');
            }
        };
        rReg.addEventListener('change', resetDeviceInputs);
        rUnreg.addEventListener('change', resetDeviceInputs);
    }

    // --- STEP 4 OPERATION: CAMPUS & COLLEGE DROPDOWNS + MANUAL LOCATION FIELDS ---
    const campusSelect  = document.getElementById('campus_select');
    const collegeSelect = document.getElementById('college_select');

    const resetSelect = (selectEl, placeholder) => {
        selectEl.disabled = true;
        selectEl.innerHTML = `<option value="" disabled selected>${placeholder}</option>`;
    };

    if (campusSelect && collegeSelect) {

        // PATHWAY 1: Campus → Colleges (DB lookup)
        campusSelect.addEventListener('change', function () {
            resetSelect(collegeSelect, 'Loading Colleges...');

            fetch(`/customer/api/campus/${this.value}/colleges`)
                .then(res => {
                    if (!res.ok) throw new Error(`Server responded ${res.status}`);
                    return res.json();
                })
                .then(data => {
                    if (data.length === 0) {
                        collegeSelect.innerHTML = '<option value="" disabled selected>No colleges found for this campus.</option>';
                        return;
                    }
                    collegeSelect.innerHTML = '<option value="" disabled selected>Select Department College...</option>';
                    data.forEach(c => {
                        const opt = document.createElement('option');
                        opt.value       = c.college_id;
                        opt.textContent = c.college_name;
                        collegeSelect.appendChild(opt);
                    });
                    collegeSelect.disabled = false;
                })
                .catch(err => {
                    console.error('[CARE] College fetch failed:', err);
                    collegeSelect.innerHTML = '<option value="" disabled selected>Failed to load colleges. Try again.</option>';
                });
        });
    }

    // --- STEP 5 OPERATION: ASSEMBLE VERIFICATION DISPLAY ---
    const compileSummaryLedger = () => {
        document.getElementById('rev-cat').textContent  = hiddenCatInput.value;
        document.getElementById('rev-desc').textContent = txtAreaDesc.value.trim();

        if (rReg.checked) {
            const modelSel  = document.getElementById('model_id');
            const activeOpt = modelSel.options[modelSel.selectedIndex];
            document.getElementById('rev-dev').textContent = `Account Registered Asset: ${activeOpt ? activeOpt.text : 'None selected'}`;
        } else {
            const dCls = document.getElementById('manual_device_type').value;
            const mNme = document.getElementById('manual_model_name').value;
            document.getElementById('rev-dev').textContent = `Infrastructure Node: ${dCls} (${mNme})`;
        }

        const cTxt   = campusSelect.options[campusSelect.selectedIndex]?.text  || '—';
        const clgTxt = collegeSelect.options[collegeSelect.selectedIndex]?.text || '—';
        const bldTxt = document.getElementById('manual_building_name').value   || '—';
        const flrTxt = document.getElementById('manual_floor_no').value        || '—';
        const rmNo   = document.getElementById('manual_room_no').value         || '—';
        const rmName = document.getElementById('manual_room_name').value       || '';
        const rmType = document.getElementById('manual_room_type').value       || '—';

        document.getElementById('rev-loc').innerHTML = `
            <i class="fa-solid fa-chevron-right text-muted xsmall me-1"></i> ${cTxt}<br>
            <i class="fa-solid fa-chevron-right text-muted xsmall me-1"></i> ${clgTxt}<br>
            <i class="fa-solid fa-chevron-right text-muted xsmall me-1"></i> ${bldTxt}<br>
            <i class="fa-solid fa-location-dot text-danger xsmall me-1"></i> Floor ${flrTxt}, Room ${rmNo} ${rmName ? '— ' + rmName : ''} (${rmType})
        `;
    };

    // --- FORM PIPELINE WORKFLOW AND INPUT VALIDATORS ENGINE ---
    const validateActiveInputs = (step) => {
        if (step === 1 && !hiddenCatInput.value) {
            alert("Please choose a category before moving to the next step.");
            return false;
        }
        if (step === 2 && txtAreaDesc.value.trim().length < 10) {
            alert("Please provide an explicit problem summary (minimum 10 characters).");
            return false;
        }
        if (step === 3) {
            if (rReg.checked && !document.getElementById('model_id').value) {
                alert("Please select your registered hardware asset.");
                return false;
            }
            if (rUnreg.checked && (!document.getElementById('manual_device_type').value || !document.getElementById('manual_model_name').value)) {
                alert("Please provide description and model information for the unregistered asset.");
                return false;
            }
        }
        if (step === 4) {
            if (!campusSelect.value) {
                alert("Please select a campus.");
                return false;
            }
            if (!collegeSelect.value) {
                alert("Please select a college.");
                return false;
            }
            if (!document.getElementById('manual_building_name').value.trim()) {
                alert("Please enter the building name.");
                return false;
            }
            if (!document.getElementById('manual_floor_no').value) {
                alert("Please enter the floor number.");
                return false;
            }
            if (!document.getElementById('manual_room_no').value.trim()) {
                alert("Please enter the room number.");
                return false;
            }
            if (!document.getElementById('manual_room_type').value) {
                alert("Please select a room type.");
                return false;
            }
        }
        return true;
    };

    const updateWizardVisualState = () => {
        document.querySelectorAll('.care-wizard-context .wz-panel-view').forEach((p, index) => {
            p.classList.toggle('active', (index + 1) === activeStep);
        });

        for (let i = 1; i <= totalSteps; i++) {
            const bNode = document.getElementById(`wz-bubble-${i}`);
            if (bNode) {
                bNode.classList.toggle('active',    i === activeStep);
                bNode.classList.toggle('completed', i < activeStep);
            }
        }

        const fillPercent = ((activeStep - 1) / (totalSteps - 1)) * 100;
        if (fillLine) fillLine.style.width = `${fillPercent}%`;

        btnBack.classList.toggle('invisible', activeStep === 1);
        stepHintText.textContent = `Step ${activeStep} of ${totalSteps}`;

        if (activeStep === totalSteps) {
            compileSummaryLedger();
            btnNext.innerHTML = '<i class="fa-solid fa-circle-check me-2"></i> Submit Ticket';
            btnNext.className = "btn btn-success px-4 fw-bold shadow-sm";
        } else {
            btnNext.innerHTML = 'Next <i class="fa-solid fa-arrow-right ms-2"></i>';
            btnNext.className = "btn btn-primary px-4 fw-bold";
        }
    };

    btnNext.addEventListener('click', function () {
        if (!validateActiveInputs(activeStep)) return;
        if (activeStep < totalSteps) {
            activeStep++;
            updateWizardVisualState();
        } else {
            window.showToast('Submitting your ticket...', 'info');
            setTimeout(() => {
                document.getElementById('wizard-issue-form').submit();
            }, 400);
        }
    });

    btnBack.addEventListener('click', function () {
        if (activeStep > 1) {
            activeStep--;
            updateWizardVisualState();
        }
    });

    document.getElementById('btn-modal-x-close').addEventListener('click', function () {
        activeStep = 1;
        document.getElementById('wizard-issue-form').reset();
        visualCards.forEach(c => c.classList.remove('selected'));
        hiddenCatInput.value = "";
        updateWizardVisualState();
    });

    // --- TOAST NOTIFICATION SYSTEM ---
    window.showToast = function(message, type = 'success') {
        const toastContainer = document.getElementById('care-toast-container') || createToastContainer();
        
        const toastHTML = `
            <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true" style="
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                min-width: 320px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.2);
                border-radius: 8px;
                animation: slideInRight 0.3s ease-out;
            ">
                <div class="toast-header" style="
                    background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
                    color: white;
                    border: none;
                    border-radius: 8px 8px 0 0;
                ">
                    <i class="fa-solid ${
                        type === 'success' ? 'fa-circle-check' :
                        type === 'error' ? 'fa-circle-exclamation' :
                        'fa-circle-info'
                    } me-2" style="font-size: 1.1rem;"></i>
                    <strong class="me-auto">${
                        type === 'success' ? 'Success!' :
                        type === 'error' ? 'Error' :
                        'Information'
                    }</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" style="transform: scale(0.8);"></button>
                </div>
                <div class="toast-body" style="
                    background: ${
                        type === 'success' ? '#ecfdf5' :
                        type === 'error' ? '#fef2f2' :
                        '#eff6ff'
                    };
                    color: ${
                        type === 'success' ? '#065f46' :
                        type === 'error' ? '#7f1d1d' :
                        '#1e40af'
                    };
                    border-radius: 0 0 8px 8px;
                    font-weight: 500;
                ">
                    ${message}
                </div>
            </div>
        `;
        
        toastContainer.innerHTML = toastHTML;
        
        // Auto-hide after 4 seconds
        setTimeout(() => {
            const toast = toastContainer.querySelector('.toast');
            if (toast) {
                toast.style.animation = 'slideOutRight 0.3s ease-in';
                setTimeout(() => toast.remove(), 300);
            }
        }, 4000);
    };

    function createToastContainer() {
        const container = document.createElement('div');
        container.id = 'care-toast-container';
        container.style.cssText = 'position: fixed; top: 0; right: 0; z-index: 9999;';
        document.body.appendChild(container);
        
        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(400px); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(400px); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
        
        return container;
    }

    // --- CHECK FOR SUCCESS MESSAGE FROM SERVER ---
    document.addEventListener('DOMContentLoaded', function() {
        const successAlert = document.getElementById('system-success-alert');
        if (successAlert) {
            const message = successAlert.textContent.trim().replace(/^\s*/, '');
            window.showToast(message, 'success');
            
            // Close the ticket modal if it was open
            const modal = bootstrap.Modal.getInstance(document.getElementById('stepperIssueModal'));
            if (modal) {
                modal.hide();
            }
            
            // Remove the inline alert after showing toast
            setTimeout(() => {
                successAlert.style.display = 'none';
            }, 500);
        }
    });

    // --- LOGOUT BUTTON HANDLER ---
    const logoutLink = document.getElementById('account-signout-link');
    if (logoutLink) {
        logoutLink.addEventListener('click', function(e) {
            e.preventDefault();
            const logoutForm = document.getElementById('logout-form-processor');
            if (logoutForm) {
                logoutForm.submit();
            }
        });
    }
});