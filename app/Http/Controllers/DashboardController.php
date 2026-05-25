{{-- ============================================================ --}}
{{-- STEP 4: LOCATION PANEL — replace the entire wz-panel-4 div --}}
{{-- ============================================================ --}}

<div class="wz-panel-view" id="wz-panel-4">
    <div class="mb-4">
        <h5 class="fw-bold text-dark">Incident Physical Location Mapping</h5>
        <p class="text-muted small">Specify exactly where on campus our support technicians need to deploy to handle the issue.</p>
    </div>
    <div class="p-3 bg-light border rounded">
        <div class="row g-3">

            {{-- ROW 1: Campus (DB dropdown) + College (DB dropdown) --}}
            <div class="col-md-6">
                <label for="campus_select" class="form-label fw-bold text-dark small">1. Campus Branch</label>
                <select id="campus_select" name="campus" class="form-select" required>
                    <option value="" disabled selected>Choose Campus...</option>
                    @foreach($campuses as $camp)
                        <option value="{{ $camp->campus_id }}">{{ $camp->campus_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label for="college_select" class="form-label fw-bold text-dark small">2. Department College</label>
                <select id="college_select" name="college" class="form-select" required disabled>
                    <option value="" disabled selected>Awaiting Campus...</option>
                </select>
            </div>

            {{-- DIVIDER --}}
            <div class="col-12">
                <hr class="my-1">
                <p class="text-muted xsmall mb-0"><i class="fa-solid fa-pen text-primary me-1"></i> Fill in the building and room details manually below.</p>
            </div>

            {{-- ROW 2: Building name (manual) --}}
            <div class="col-md-12">
                <label for="manual_building_name" class="form-label fw-bold text-dark small">3. Building Name</label>
                <input type="text"
                       id="manual_building_name"
                       name="manual_building_name"
                       class="form-control"
                       placeholder="e.g., CSSP Building 1, Main Engineering Hall"
                       required>
            </div>

            {{-- ROW 3: Floor + Room No + Room Name --}}
            <div class="col-md-2">
                <label for="manual_floor_no" class="form-label fw-bold text-dark small">4. Floor No.</label>
                <input type="number"
                       id="manual_floor_no"
                       name="manual_floor_no"
                       class="form-control"
                       placeholder="e.g., 2"
                       min="1"
                       required>
            </div>
            <div class="col-md-3">
                <label for="manual_room_no" class="form-label fw-bold text-dark small">5. Room No.</label>
                <input type="text"
                       id="manual_room_no"
                       name="manual_room_no"
                       class="form-control"
                       placeholder="e.g., 201"
                       required>
            </div>
            <div class="col-md-4">
                <label for="manual_room_name" class="form-label fw-bold text-dark small">6. Room Name <span class="text-muted fw-normal">(optional)</span></label>
                <input type="text"
                       id="manual_room_name"
                       name="manual_room_name"
                       class="form-control"
                       placeholder="e.g., Computer Lab 3, Faculty Office">
            </div>
            <div class="col-md-3">
                <label for="manual_room_type" class="form-label fw-bold text-dark small">7. Room Type</label>
                <select id="manual_room_type" name="manual_room_type" class="form-select" required>
                    <option value="" disabled selected>Select type...</option>
                    <option value="laboratory">Laboratory</option>
                    <option value="lecture_hall">Lecture Hall</option>
                    <option value="office">Office</option>
                    <option value="server_room">Server Room</option>
                </select>
            </div>

        </div>
    </div>
</div>