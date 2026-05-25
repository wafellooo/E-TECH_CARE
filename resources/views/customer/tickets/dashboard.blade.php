<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CARE System - Student & Faculty Helpdesk Portal</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="{{ asset('css/customer-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/ticket-wizard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-ticket-tabs.css') }}">
</head>
<body>

    <svg xmlns="http://www.w3.org/2000/svg" style="display:none">
      <symbol id="i-cpu" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <rect x="4" y="4" width="16" height="16" rx="2"/>
        <rect x="9" y="9" width="6" height="6"/>
        <line x1="9" y1="1" x2="9" y2="4"/><line x1="15" y1="1" x2="15" y2="4"/>
        <line x1="9" y1="20" x2="9" y2="23"/><line x1="15" y1="20" x2="15" y2="23"/>
        <line x1="20" y1="9" x2="23" y2="9"/><line x1="20" y1="15" x2="23" y2="15"/>
        <line x1="1" y1="9" x2="4" y2="9"/><line x1="1" y1="1" x2="4" y2="15"/>
      </symbol>
      <symbol id="i-apps" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="7" height="7"/>
        <rect x="14" y="3" width="7" height="7"/>
        <rect x="14" y="14" width="7" height="7"/>
        <rect x="3" y="14" width="7" height="7"/>
      </symbol>
      <symbol id="i-wifi" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M5 12.55a11 11 0 0 1 14.08 0"/>
        <path d="M1.42 9a16 16 0 0 1 21.16 0"/>
        <path d="M8.53 16.11a6 6 0 0 1 6.95 0"/>
        <line x1="12" y1="20" x2="12.01" y2="20"/>
      </symbol>
      <symbol id="i-key" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>
      </symbol>
    </svg>

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
        <a href="#" id="account-signout-link" class="nav-link mt-auto mx-3 py-2 text-white-50 text-decoration-none">
            <i class="fa-solid fa-right-from-bracket me-2"></i> Log Out
        </a>
    </div>

    <div class="main-wrapper">
        @if(session('success'))
            <div id="system-success-alert" class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <header class="d-flex justify-content-between align-items-start mb-5 pb-3 border-bottom">
            <div>
                <h1 class="h3 mb-1 text-dark fw-bold">Welcome back, <span class="text-primary">{{ session('customer_name') ?? 'Guest' }}</span>!</h1>
                <p class="text-muted small mb-0">You have <strong>{{ $stats['total'] ?? 0 }} total ticket{{ ($stats['total'] ?? 0) !== 1 ? 's' : '' }}</strong> on record. <a href="#" class="text-primary text-decoration-none fw-500" data-bs-toggle="modal" data-bs-target="#stepperIssueModal">Submit a new ticket</a> or manage your existing requests below.</p>
            </div>
            <div>
                <button type="button" class="btn-bu-primary shadow-sm px-4 py-2" data-bs-toggle="modal" data-bs-target="#stepperIssueModal">
                    <i class="fa-solid fa-plus me-2"></i> Submit an Issue
                </button>
            </div>
        </header>

        <section class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="summary-card d-flex align-items-center justify-content-between p-4 bg-white rounded shadow-sm">
                    <div>
                        <div class="label text-muted small uppercase fw-bold">Total Issues Reported</div>
                        <div class="count h2 fw-bold mt-1 mb-0">{{ $stats['total'] ?? 0 }}</div>
                    </div>
                    <div class="icon-avatar p-3 rounded-circle" style="background: rgba(8, 27, 92, 0.06); color: var(--bu-navy);"><i class="fa-solid fa-receipt fs-4"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card d-flex align-items-center justify-content-between p-4 bg-white rounded shadow-sm">
                    <div>
                        <div class="label text-muted small uppercase fw-bold">Under Support Review</div>
                        <div class="count h2 fw-bold mt-1 mb-0">{{ $stats['pending'] ?? 0 }}</div>
                    </div>
                    <div class="icon-avatar p-3 rounded-circle" style="background: rgba(217, 119, 6, 0.08); color: var(--bu-amber);"><i class="fa-solid fa-clock-rotate-left fa-spin-pulse fs-4"></i></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card d-flex align-items-center justify-content-between p-4 bg-white rounded shadow-sm">
                    <div>
                        <div class="label text-muted small uppercase fw-bold">Resolved Concerns</div>
                        <div class="count h2 fw-bold mt-1 mb-0">{{ $stats['resolved'] ?? 0 }}</div>
                    </div>
                    <div class="icon-avatar p-3 rounded-circle" style="background: rgba(22, 163, 74, 0.08); color: var(--status-resolved);"><i class="fa-solid fa-square-check fs-4"></i></div>
                </div>
            </div>
        </section>

        <main class="ticket-container-card p-4 bg-white rounded shadow-sm">

            {{-- ── Filter Bar ── --}}
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">

                {{-- Search input --}}
                <div class="search-icon-wrapper flex-grow-1" style="min-width: 220px; max-width: 360px;">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text"
                           id="ticket-search"
                           class="form-control form-control-sm ps-4"
                           placeholder="Search by ID, category, or description…"
                           autocomplete="off">
                </div>

                {{-- Status filter tabs --}}
                <div class="d-flex flex-wrap gap-1" id="status-tabs">
                    <button class="status-tab active" data-status="all">All</button>
                    <button class="status-tab" data-status="pending">Under Review</button>
                    <button class="status-tab" data-status="assigned">Assigned</button>
                    <button class="status-tab" data-status="ongoing">Ongoing</button>
                    <button class="status-tab" data-status="resolved">Resolved</button>
                    <button class="status-tab" data-status="closed">Closed</button>
                </div>
            </div>

            {{-- Result count --}}
            <p class="text-muted mb-2" id="ticket-count"></p>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tickets-table">
                    <thead class="table-light">
                        <tr>
                            <th>Tracking ID</th>
                            <th>Category</th>
                            <th>Description Summary</th>
                            <th>Date Submitted</th>
                            <th>Progress Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr data-status="{{ $ticket->status }}"
                                data-search="{{ strtolower($ticket->ticket_id . ' ' . $ticket->p_category . ' ' . $ticket->p_desc) }}"
                                class="{{ $ticket->status === 'closed' ? 'status-closed' : '' }}">
                                <td class="fw-bold text-dark font-monospace">#{{ $ticket->ticket_id }}</td>
                                <td class="text-secondary text-capitalize">{{ $ticket->p_category }}</td>
                                <td class="text-dark text-truncate" style="max-width: 260px;">{{ $ticket->p_desc }}</td>
                                <td class="text-secondary small">{{ \Carbon\Carbon::parse($ticket->date_reported)->format('M d, Y • h:i A') }}</td>
                                <td>
                                    @if($ticket->status == 'pending')
                                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="fa-solid fa-circle-dot me-1"></i> Under Review</span>
                                    @elseif($ticket->status == 'assigned')
                                        <span class="badge bg-info text-dark px-3 py-2 rounded-pill"><i class="fa-solid fa-user-gear me-1"></i> Assigned</span>
                                    @elseif($ticket->status == 'ongoing')
                                        <span class="badge bg-primary text-white px-3 py-2 rounded-pill"><i class="fa-solid fa-spinner me-1"></i> Ongoing</span>
                                    @elseif($ticket->status == 'resolved')
                                        <span class="badge bg-success text-white px-3 py-2 rounded-pill"><i class="fa-solid fa-circle-check me-1"></i> Resolved</span>
                                    @else
                                        <span class="badge bg-secondary text-white px-3 py-2 rounded-pill"><i class="fa-solid fa-ban me-1"></i> Closed</span>
                                    @endif
                                </td>
                                <td>
                                    @if($ticket->status === 'pending')
                                        <div class="d-flex gap-2 align-items-center">
                                            <a href="{{ route('customer.tickets.edit', $ticket->ticket_id) }}"
                                               class="btn-action btn-action-edit">
                                                <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                                            </a>
                                            <a href="#"
                                               class="btn-action btn-action-cancel"
                                               data-bs-toggle="modal"
                                               data-bs-target="#cancelModal{{ $ticket->ticket_id }}">
                                                <i class="fa-solid fa-xmark me-1"></i> Cancel
                                            </a>
                                        </div>
                                    @else
                                        <span class="text-muted action-dash">—</span>
                                    @endif
                                </td>
                            </tr>

                            {{-- Per-ticket Cancel Confirmation Modal --}}
                            @if($ticket->status === 'pending')
                            <div class="modal fade" id="cancelModal{{ $ticket->ticket_id }}"
                                 tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered cancel-modal-dialog">
                                    <div class="modal-content cancel-modal-content">

                                        <div class="cancel-modal-body">

                                            {{-- Title --}}
                                            <h5 class="cancel-modal-title">Cancel ticket #{{ $ticket->ticket_id }}?</h5>

                                            {{-- Subtitle --}}
                                            <p class="cancel-modal-subtitle">You will lose all information entered for this ticket. This action cannot be undone.</p>

                                            {{-- Buttons --}}
                                            <div class="cancel-modal-actions">
                                                <button type="button"
                                                        class="btn-cancel-keep"
                                                        data-bs-dismiss="modal">
                                                    Keep it
                                                </button>
                                                <form action="{{ route('customer.tickets.destroy', $ticket->ticket_id) }}"
                                                      method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-cancel-confirm">
                                                        Cancel ticket
                                                    </button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif

                        @empty
                            <tr id="db-empty-row">
                                <td colspan="6" class="text-center py-5 text-secondary">
                                    <i class="fa-solid fa-envelope-open display-6 text-muted opacity-50 d-block mb-3"></i> No helpdesk tickets discovered.
                                </td>
                            </tr>
                        @endforelse
                        {{-- Shown by JS when search/filter returns zero matches --}}
                        <tr id="no-results-row" style="display:none;">
                            <td colspan="6" class="text-center py-5 text-secondary">
                                <i class="fa-solid fa-filter-circle-xmark display-6 text-muted opacity-50 d-block mb-3"></i>
                                No tickets match your search or filter.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div class="modal fade" id="stepperIssueModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="stepperModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg care-wizard-context">
                
                <div class="modal-header bg-bu-navy text-white py-3 d-flex justify-content-between align-items-center" style="padding-right: 1rem !important;">
                    <h5 class="modal-title fw-bold" id="stepperModalLabel">
                        <i class="fa-solid fa-file-invoice me-2 text-warning"></i> Open Technical Assistance Ticket
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="btn-modal-x-close" style="filter: brightness(1.2); opacity: 1 !important; flex-shrink: 0;"></button>
                </div>

                <div class="wz-stepper-header px-4 py-3 border-bottom bg-light">
                    <div class="wz-steps-track d-flex justify-content-between position-relative w-100">
                        <div class="wz-line-base"></div>
                        <div class="wz-line-fill" id="wz-fill-indicator"></div>
                        
                        <div class="wz-step-item active" id="wz-bubble-1">
                            <div class="wz-bubble-node"><i class="fa-solid fa-tags"></i></div>
                            <div class="wz-caption-label">Category</div>
                        </div>
                        <div class="wz-step-item" id="wz-bubble-2">
                            <div class="wz-bubble-node"><i class="fa-solid fa-pen-to-square"></i></div>
                            <div class="wz-caption-label">Description</div>
                        </div>
                        <div class="wz-step-item" id="wz-bubble-3">
                            <div class="wz-bubble-node"><i class="fa-solid fa-laptop"></i></div>
                            <div class="wz-caption-label">Device Info</div>
                        </div>
                        <div class="wz-step-item" id="wz-bubble-4">
                            <div class="wz-bubble-node"><i class="fa-solid fa-map-location-dot"></i></div>
                            <div class="wz-caption-label">Location</div>
                        </div>
                        <div class="wz-step-item" id="wz-bubble-5">
                            <div class="wz-bubble-node"><i class="fa-solid fa-square-check"></i></div>
                            <div class="wz-caption-label">Review</div>
                        </div>
                    </div>
                </div>

                <div class="modal-body p-4 bg-white">
                    <form id="wizard-issue-form" action="{{ route('customer.tickets.store') }}" method="POST" novalidate>
                        @csrf
                        
                        <div class="wz-panel-view active" id="wz-panel-1">
                            <div class="text-center mb-4">
                                <h5 class="fw-bold text-dark">Select Issue Category</h5>
                                <p class="text-muted small">Choose the problem classification area to help us route your request appropriately.</p>
                            </div>
                            
                            <input type="hidden" name="p_category" id="hidden_p_category" required>
                            
                            <div class="row g-3">
                                <div class="col-6 col-lg-3">
                                    <div class="wz-cat-card text-center p-3 h-100" data-cat="hardware">
                                        <div class="wz-img-frame mb-3">
                                            <img src="{{ asset('images/HARDWARE.jpg') }}" alt="Hardware Support">
                                        </div>
                                        <div class="fw-bold text-primary small">Hardware</div>
                                        <div class="text-muted xsmall mt-1">PC, monitor, mouse, printer, or lab equipment errors.</div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="wz-cat-card text-center p-3 h-100" data-cat="software">
                                        <div class="wz-img-frame mb-3">
                                            <img src="{{ asset('images/SOFTWARE.jpg') }}" alt="Software Support">
                                        </div>
                                        <div class="fw-bold text-primary small">Software</div>
                                        <div class="text-muted xsmall mt-1">OS crashes, portal errors, or app bugs.</div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="wz-cat-card text-center p-3 h-100" data-cat="network">
                                        <div class="wz-img-frame mb-3">
                                            <img src="{{ asset('images/NETWORK.png') }}" alt="Network Support">
                                        </div>
                                        <div class="fw-bold text-primary small">Network</div>
                                        <div class="text-muted xsmall mt-1">Wi-Fi drops, local area network, or cable faults.</div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-3">
                                    <div class="wz-cat-card text-center p-3 h-100" data-cat="account">
                                        <div class="wz-img-frame mb-3">
                                            <img src="{{ asset('images/ACCOUNT.png') }}" alt="Account Support">
                                        </div>
                                        <div class="fw-bold text-primary small">Account</div>
                                        <div class="text-muted xsmall mt-1">BU mail access, logins, or password blocks.</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="wz-panel-view" id="wz-panel-2">
                            <div class="mb-3">
                                <h5 class="fw-bold text-dark mb-1">Describe the Problem</h5>
                                <p class="text-muted small">Provide clear operational context to help our engineers diagnose the root cause quickly.</p>
                            </div>
                            <div class="p-3 bg-light rounded border">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="p_desc" class="form-label fw-bold small mb-0 text-dark">Technical Symptoms Summary Ledger</label>
                                    <span class="badge bg-secondary font-monospace" id="wz-counter-badge">0 / 500</span>
                                </div>
                                <textarea name="p_desc" id="p_desc" rows="5" class="form-control bg-white" placeholder="Example: The desktop system in Laboratory 2, Row B, freezes up during boot. It encounters a blue screen loop listing error 'INACCESSIBLE_BOOT_DEVICE' right after the system splash screen loads..." required minlength="10" maxlength="500"></textarea>
                                <div class="text-muted xsmall mt-2">
                                    <i class="fa-solid fa-circle-info text-primary me-1"></i> Minimum 10 characters required. Include any specific error codes, unique symptoms, or visible alert windows.
                                </div>
                            </div>
                        </div>

                        <div class="wz-panel-view" id="wz-panel-3">
                            <div class="mb-4">
                                <h5 class="fw-bold text-dark">Affected Hardware Node Identification</h5>
                                <p class="text-muted small">Specify if this issue concerns your registered device asset or unlisted campus equipment.</p>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="p-3 bg-light border rounded h-100">
                                        <label class="form-label fw-bold text-dark small mb-3 d-block">Device Context Type</label>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="device_entity_type" id="dev_reg" value="registered" checked>
                                            <label class="form-check-label fw-semibold small text-dark" for="dev_reg">Registered Personal Device</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="device_entity_type" id="dev_unreg" value="unregistered">
                                            <label class="form-check-label fw-semibold small text-dark" for="dev_unreg">Unregistered Station / Asset</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="p-4 border rounded bg-white h-100">
                                        <div id="wz-box-registered">
                                            <label for="model_id" class="form-label fw-bold small text-dark">Select your registered profile equipment</label>
                                            <select name="model_id" id="model_id" class="form-select" required>
                                                <option value="" disabled selected>Choose from your account's personal hardware logs...</option>
                                                @forelse($devices as $d)
                                                    <option value="{{ $d->model_id }}">{{ $d->brand }} {{ $d->model_name }} [{{ ucfirst($d->device_type) }}]</option>
                                                @empty
                                                    <option value="MDL001">No assets logged. Default System Profile will map.</option>
                                                @endforelse
                                            </select>
                                        </div>
                                        <div id="wz-box-unregistered" class="d-none">
                                            <label class="form-label fw-bold small text-dark mb-2">Provide equipment parameters</label>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <label class="xsmall text-secondary">Device Classification</label>
                                                    <input type="text" name="manual_device_type" id="manual_device_type" class="form-control" placeholder="e.g., Shared Desktop, Projector">
                                                </div>
                                                <div class="col-6">
                                                    <label class="xsmall text-secondary">Brand / Model Name</label>
                                                    <input type="text" name="manual_model_name" id="manual_model_name" class="form-control" placeholder="e.g., Lenovo ThinkCentre M70">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
                <p class="text-muted xsmall mb-0">
                    <i class="fa-solid fa-pen text-primary me-1"></i> Fill in the building and room details manually below.
                </p>
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

            {{-- ROW 3: Floor + Room No + Room Name + Room Type --}}
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

                        <div class="wz-panel-view" id="wz-panel-5">
                            <div class="text-center mb-4">
                                <h5 class="fw-bold text-dark">Review Support Ledger Confirmation</h5>
                                <p class="text-muted small">Please verify all input fields look correct before committing to the helpdesk ledger.</p>
                            </div>
                            <div class="row g-3">
                                <div class="col-lg-5">
                                    <div class="p-3 bg-light border rounded h-100">
                                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-2 small"><i class="fa-solid fa-list text-muted me-1"></i> Core Scope Metadata</h6>
                                        <div class="mb-2">
                                            <small class="text-muted d-block xsmall">PROBLEM CATEGORY</small>
                                            <span class="fw-bold text-primary text-capitalize" id="rev-cat">—</span>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted d-block xsmall">HARDWARE EQUIPMENT ASSET</small>
                                            <span class="fw-semibold text-dark small" id="rev-dev">—</span>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block xsmall">ROUTED TARGET LOCATION LOCATION</small>
                                            <div class="fw-semibold text-dark small" id="rev-loc" style="line-height:1.4;">—</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="p-3 border rounded h-100 bg-white">
                                        <h6 class="fw-bold text-dark border-bottom pb-2 mb-2 small"><i class="fa-solid fa-file-lines text-muted me-1"></i> Provided Problem Description Summary</h6>
                                        <p class="text-muted small mb-0 font-sans" id="rev-desc" style="white-space: pre-wrap; line-height: 1.5; font-style: italic;">—</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer bg-light d-flex justify-content-center px-4 py-3">
                    <div class="d-flex gap-2 align-items-center">
                        <button type="button" class="btn btn-secondary px-4 fw-bold small invisible" id="wz-btn-back">
                            <i class="fa-solid fa-arrow-left me-2"></i> Back
                        </button>
                        <span class="small fw-semibold text-muted font-monospace" id="wz-step-hint">Step 1 of 5</span>
                        <button type="button" class="btn btn-primary px-4 fw-bold" id="wz-btn-next">
                            Next <i class="fa-solid fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/customer-dashboard.js') }}"></script>
    <script src="{{ asset('js/customer-ticket-filters.js') }}"></script>
</body>
</html>