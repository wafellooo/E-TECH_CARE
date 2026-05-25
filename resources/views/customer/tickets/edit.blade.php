<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CARE System – Edit Ticket #{{ $ticket->ticket_id }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('css/customer-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-ticket-edit.css') }}">
</head>
<body>

    <div class="sidebar-panel d-flex flex-column py-4" role="navigation">
        <div class="px-4 mb-5">
            <a href="#" class="app-logo text-decoration-none">
                <i class="fa-solid fa-graduation-cap"></i> BU CARE <span>System</span>
            </a>
        </div>
        <ul class="nav sidebar-menu flex-column gap-2">
            <li class="nav-item">
                <a href="{{ route('customer.tickets.index') }}" class="nav-link active">
                    <i class="fa-solid fa-list-check me-2"></i> Support Requests
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link" onclick="alert('Maintenance notice.'); return false;">
                    <i class="fa-solid fa-book-open me-2"></i> Knowledge Base
                </a>
            </li>
        </ul>
        <form id="logout-form-processor" action="{{ route('customer.logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a href="#" id="account-signout-link" class="nav-link mt-auto mx-3 py-2 text-white-50 text-decoration-none"
           onclick="event.preventDefault(); document.getElementById('logout-form-processor').submit();">
            <i class="fa-solid fa-right-from-bracket me-2"></i> Log Out
        </a>
    </div>

    <div class="main-wrapper">

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i> {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <header class="d-flex justify-content-between align-items-start mb-5 pb-3 border-bottom">
            <div>
                <a href="{{ route('customer.tickets.index') }}" class="text-muted small text-decoration-none mb-2 d-inline-block">
                    <i class="fa-solid fa-arrow-left me-1"></i> Back to Dashboard
                </a>
                <h1 class="h3 mb-1 text-dark fw-bold">
                    Edit Ticket <span class="font-monospace text-primary">#{{ $ticket->ticket_id }}</span>
                </h1>
                <p class="text-muted small mb-0">Update your ticket details below. Status and location are managed by support staff.</p>
            </div>
            <div>
                @php
                    $statusClass = 'badge-status-' . $ticket->status;
                @endphp
                <span class="badge px-3 py-2 rounded-pill {{ $statusClass }} text-capitalize fw-semibold">
                    <i class="fa-solid fa-circle-dot me-1"></i> {{ $ticket->status }}
                </span>
            </div>
        </header>

        <div class="row g-4">

            {{-- LEFT: EDITABLE FORM --}}
            <div class="col-lg-7">
                <div class="bg-white rounded shadow-sm p-4">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-4">
                        <i class="fa-solid fa-pen-to-square text-primary me-2"></i> Editable Fields
                    </h6>

                    <form action="{{ route('customer.tickets.update', $ticket->ticket_id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Category --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small text-dark">Problem Category</label>
                            <select name="p_category" class="form-select" required>
                                <option value="hardware"        {{ $ticket->p_category === 'hardware'        ? 'selected' : '' }}>Hardware</option>
                                <option value="software"        {{ $ticket->p_category === 'software'        ? 'selected' : '' }}>Software</option>
                                <option value="network"         {{ $ticket->p_category === 'network'         ? 'selected' : '' }}>Network</option>
                                <option value="infrastructure"  {{ $ticket->p_category === 'infrastructure'  ? 'selected' : '' }}>Infrastructure</option>
                                <option value="account"         {{ $ticket->p_category === 'account'         ? 'selected' : '' }}>Account</option>
                            </select>
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold small text-dark mb-0">Problem Description</label>
                                <span class="badge bg-secondary font-monospace" id="edit-counter-badge">{{ strlen($ticket->p_desc) }} / 500</span>
                            </div>
                            <textarea name="p_desc"
                                      id="edit_p_desc"
                                      rows="6"
                                      class="form-control"
                                      required
                                      minlength="10"
                                      maxlength="500"
                                      placeholder="Describe your issue clearly...">{{ old('p_desc', $ticket->p_desc) }}</textarea>
                            <div class="text-muted xsmall mt-1">
                                <i class="fa-solid fa-circle-info text-primary me-1"></i> Minimum 10 characters required.
                            </div>
                        </div>

                        {{-- Location Fields --}}
                        <div class="mb-4">
                            <h6 class="fw-bold text-dark mb-3">
                                <i class="fa-solid fa-location-dot text-primary me-2"></i> Incident Location
                            </h6>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-dark">Building Name</label>
                                    <input type="text"
                                           name="manual_building_name"
                                           class="form-control"
                                           placeholder="e.g., CSSP Building 1"
                                           value="{{ old('manual_building_name', $location->building_name ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-dark">Floor Number</label>
                                    <input type="number"
                                           name="manual_floor_no"
                                           class="form-control"
                                           placeholder="e.g., 2"
                                           min="1"
                                           value="{{ old('manual_floor_no', $location->floor_no ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-dark">Room Number</label>
                                    <input type="text"
                                           name="manual_room_no"
                                           class="form-control"
                                           placeholder="e.g., 201"
                                           value="{{ old('manual_room_no', $location->room_no ?? '') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small text-dark">Room Name <span class="text-muted">(optional)</span></label>
                                    <input type="text"
                                           name="manual_room_name"
                                           class="form-control"
                                           placeholder="e.g., Computer Lab 3"
                                           value="{{ old('manual_room_name', $location->room_name ?? '') }}">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small text-dark">Room Type</label>
                                    <select name="manual_room_type" class="form-select">
                                        <option value="">Select room type...</option>
                                        <option value="laboratory" {{ (old('manual_room_type', $location->room_type ?? '') === 'laboratory') ? 'selected' : '' }}>Laboratory</option>
                                        <option value="lecture_hall" {{ (old('manual_room_type', $location->room_type ?? '') === 'lecture_hall') ? 'selected' : '' }}>Lecture Hall</option>
                                        <option value="office" {{ (old('manual_room_type', $location->room_type ?? '') === 'office') ? 'selected' : '' }}>Office</option>
                                        <option value="server_room" {{ (old('manual_room_type', $location->room_type ?? '') === 'server_room') ? 'selected' : '' }}>Server Room</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4 fw-bold">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Save Changes
                            </button>
                            <a href="{{ route('customer.tickets.index') }}" class="btn btn-secondary px-4 fw-bold">
                                <i class="fa-solid fa-xmark me-2"></i> Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>

            {{-- RIGHT: READ-ONLY INFO --}}
            <div class="col-lg-5">
                <div class="bg-white rounded shadow-sm p-4 mb-4">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                        <i class="fa-solid fa-circle-info text-muted me-2"></i> Ticket Info
                    </h6>
                    <div class="mb-3">
                        <small class="text-muted d-block xsmall fw-bold">TRACKING ID</small>
                        <div class="field-readonly font-monospace">#{{ $ticket->ticket_id }}</div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block xsmall fw-bold">DATE SUBMITTED</small>
                        <div class="field-readonly">
                            {{ \Carbon\Carbon::parse($ticket->date_reported)->format('F d, Y • h:i A') }}
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block xsmall fw-bold">PRIORITY LEVEL</small>
                        <div class="field-readonly text-capitalize">{{ $ticket->p_priority ?? 'Not yet assigned' }}</div>
                    </div>
                    <div>
                        <small class="text-muted d-block xsmall fw-bold">DATE RESOLVED</small>
                        <div class="field-readonly">
                            {{ $ticket->date_resolved ? \Carbon\Carbon::parse($ticket->date_resolved)->format('F d, Y • h:i A') : 'Not yet resolved' }}
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded shadow-sm p-4">
                    <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                        <i class="fa-solid fa-location-dot text-muted me-2"></i> Reported Location
                    </h6>
                    @if($location)
                        <div class="mb-2">
                            <small class="text-muted d-block xsmall fw-bold">BUILDING</small>
                            <div class="field-readonly">{{ $location->building_name ?? '—' }}</div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block xsmall fw-bold">FLOOR</small>
                            <div class="field-readonly">Floor {{ $location->floor_no }}</div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block xsmall fw-bold">ROOM</small>
                            <div class="field-readonly">
                                Room {{ $location->room_no }}{{ $location->room_name ? ' — ' . $location->room_name : '' }}
                            </div>
                        </div>
                        <div>
                            <small class="text-muted d-block xsmall fw-bold">ROOM TYPE</small>
                            <div class="field-readonly text-capitalize">{{ str_replace('_', ' ', $location->room_type) }}</div>
                        </div>
                    @else
                        <p class="text-muted small mb-0">No location data recorded.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/customer-ticket-edit.js') }}"></script>
    <script>
        // Character counter for description textarea
        const editDesc    = document.getElementById('edit_p_desc');
        const editCounter = document.getElementById('edit-counter-badge');
        if (editDesc && editCounter) {
            editDesc.addEventListener('input', function () {
                const len = this.value.length;
                editCounter.textContent = `${len} / 500`;
                editCounter.className = (len >= 10 && len <= 500)
                    ? 'badge bg-success font-monospace'
                    : 'badge bg-secondary font-monospace';
            });
        }
    </script>
</body>
</html>