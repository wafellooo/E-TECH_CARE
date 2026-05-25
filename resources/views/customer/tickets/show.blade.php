<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket {{ $ticket->ticket_id }} — BU CARE System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/customer-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/customer-ticket-show.css') }}">
</head>
<body>

    {{-- ── Sidebar (identical to dashboard) ── --}}
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
        <form id="logout-form-processor" action="{{ route('customer.logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
        <a href="#" id="account-signout-link"
           class="nav-link mt-auto mx-3 py-2 text-white-50 text-decoration-none">
            <i class="fa-solid fa-right-from-bracket me-2"></i> Log Out
        </a>
    </div>

    {{-- ── Main content ── --}}
    <div class="main-wrapper">

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Back link --}}
        <a href="{{ route('customer.tickets.index') }}"
           class="d-inline-flex align-items-center gap-2 text-decoration-none text-muted small fw-semibold mb-4">
            <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
        </a>

        @php
            $categoryIcons = [
                'hardware'       => 'fa-microchip',
                'software'       => 'fa-code',
                'network'        => 'fa-wifi',
                'account'        => 'fa-user-lock',
                'infrastructure' => 'fa-building-columns',
            ];
            $statusIcons = [
                'pending'  => 'fa-hourglass-half',
                'assigned' => 'fa-user-gear',
                'ongoing'  => 'fa-spinner',
                'resolved' => 'fa-circle-check',
                'closed'   => 'fa-ban',
            ];
            $statusLabels = [
                'pending'  => 'Under Support Review',
                'assigned' => 'Assigned',
                'ongoing'  => 'Ongoing',
                'resolved' => 'Resolved',
                'closed'   => 'Closed',
            ];
        @endphp

        {{-- ── Hero ── --}}
        <div class="ticket-hero">
            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                <div>
                    <span class="ticket-id-badge"># {{ $ticket->ticket_id }}</span>
                    <h2>
                        <i class="fa-solid {{ $categoryIcons[$ticket->p_category] ?? 'fa-ticket' }} me-2"
                           style="opacity:.8;"></i>
                        {{ ucfirst($ticket->p_category) }} Issue
                    </h2>
                    <div class="meta">
                        <i class="fa-regular fa-clock me-1"></i>
                        Reported {{ \Carbon\Carbon::parse($ticket->date_reported)->format('F j, Y \a\t g:i A') }}
                        &nbsp;·&nbsp;
                        <i class="fa-solid fa-user me-1"></i>{{ session('customer_name') }}
                    </div>
                </div>
                <div class="text-end">
                    <span class="status-pill {{ $ticket->status }}">
                        <span class="status-dot"></span>
                        <i class="fa-solid {{ $statusIcons[$ticket->status] ?? 'fa-circle' }}"></i>
                        {{ $statusLabels[$ticket->status] ?? ucfirst($ticket->status) }}
                    </span>
                    @if($ticket->date_resolved)
                        <div class="mt-2" style="font-size:.78rem; color:rgba(255,255,255,.5);">
                            <i class="fa-solid fa-calendar-check me-1"></i>
                            Resolved {{ \Carbon\Carbon::parse($ticket->date_resolved)->format('M j, Y') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Issue Details ── --}}
        <div class="detail-card">
            <div class="detail-card-title">
                <i class="fa-solid fa-file-lines"></i> Issue Details
            </div>
            <div class="field-grid mb-3">
                <div class="field-item">
                    <label>Category</label>
                    <div class="field-value">
                        <span class="chip chip-category">
                            <i class="fa-solid {{ $categoryIcons[$ticket->p_category] ?? 'fa-tag' }}"></i>
                            {{ ucfirst($ticket->p_category) }}
                        </span>
                    </div>
                </div>
                <div class="field-item">
                    <label>Priority Level</label>
                    <div class="field-value">
                        <span class="chip chip-priority-{{ $ticket->p_priority }}">
                            <i class="fa-solid fa-flag"></i>
                            {{ ucfirst($ticket->p_priority) }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="field-item">
                <label>Problem Description</label>
                <div class="desc-block mt-1">{{ $ticket->p_desc }}</div>
            </div>
        </div>

        {{-- ── Location ── --}}
        <div class="detail-card">
            <div class="detail-card-title">
                <i class="fa-solid fa-location-dot"></i> Location
            </div>
            <div class="field-grid">
                <div class="field-item">
                    <label>Campus</label>
                    <div class="field-value">{{ $location->campus_name ?? '—' }}</div>
                </div>
                <div class="field-item">
                    <label>Building</label>
                    <div class="field-value">{{ $location->building_name ?? '—' }}</div>
                </div>
                <div class="field-item">
                    <label>Floor</label>
                    <div class="field-value">Floor {{ $location->floor_no ?? '—' }}</div>
                </div>
                <div class="field-item">
                    <label>Room No.</label>
                    <div class="field-value mono">{{ $location->room_no ?? '—' }}</div>
                </div>
                <div class="field-item">
                    <label>Room Name</label>
                    <div class="field-value">{{ $location->room_name ?? '—' }}</div>
                </div>
                <div class="field-item">
                    <label>Room Type</label>
                    <div class="field-value">{{ ucfirst(str_replace('_', ' ', $location->room_type ?? '—')) }}</div>
                </div>
            </div>
        </div>

        {{-- ── Device ── --}}
        <div class="detail-card">
            <div class="detail-card-title">
                <i class="fa-solid fa-desktop"></i> Device
            </div>
            <div class="field-grid">
                <div class="field-item">
                    <label>Model ID</label>
                    <div class="field-value mono">{{ $ticket->model_id }}</div>
                </div>
                <div class="field-item">
                    <label>Brand</label>
                    <div class="field-value">{{ $device->brand ?? '—' }}</div>
                </div>
                <div class="field-item">
                    <label>Model Name</label>
                    <div class="field-value">{{ $device->model_name ?? '—' }}</div>
                </div>
                <div class="field-item">
                    <label>Device Type</label>
                    <div class="field-value">{{ ucfirst(str_replace('_', ' ', $device->device_type ?? '—')) }}</div>
                </div>
            </div>
        </div>

        {{-- ── Action Bar ── --}}
        <div class="action-bar">
            @if($ticket->status === 'pending')
                <a href="{{ route('customer.tickets.edit', $ticket->ticket_id) }}"
                   class="link-edit">Edit Ticket</a>
                <span style="color:#dee2e6;">|</span>
                <button type="button" class="text-link link-cancel"
                        data-bs-toggle="modal" data-bs-target="#cancelModal">
                    Cancel Ticket
                </button>
            @else
                <span class="link-locked">
                    <i class="fa-solid fa-lock me-1"></i>
                    This ticket is <strong>{{ $statusLabels[$ticket->status] }}</strong> and cannot be modified.
                </span>
            @endif
        </div>

    </div>{{-- /.main-wrapper --}}


    {{-- ── Cancel Confirmation Modal ── --}}
    @if($ticket->status === 'pending')
    <div class="modal fade" id="cancelModal" tabindex="-1"
         data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                <div style="height:5px; background:linear-gradient(90deg,#dc3545,#ff6b6b);"></div>

                <div class="modal-body p-5">

                    <div class="text-center mb-4">
                        <div class="mb-3" style="font-size:3rem; color:#dc3545; line-height:1;">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <h4 class="fw-bold text-dark mb-1">Cancel This Support Ticket?</h4>
                        <p class="text-muted mb-0" style="font-size:.95rem;">Please read carefully before proceeding.</p>
                    </div>

                    <div class="rounded-3 border px-4 py-3 mb-4" style="background:#f8f9fa;">
                        <div class="row g-3 align-items-center">
                            <div class="col-sm-4 text-center text-sm-start">
                                <div class="text-muted" style="font-size:.7rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase;">Ticket ID</div>
                                <div class="fw-bold font-monospace text-dark" style="font-size:1.1rem; letter-spacing:.06em;">#{{ $ticket->ticket_id }}</div>
                            </div>
                            <div class="col-sm-4 text-center">
                                <div class="text-muted" style="font-size:.7rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase;">Category</div>
                                <div class="fw-semibold text-dark text-capitalize">{{ $ticket->p_category }}</div>
                            </div>
                            <div class="col-sm-4 text-center text-sm-end">
                                <div class="text-muted" style="font-size:.7rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase;">Date Filed</div>
                                <div class="fw-semibold text-dark" style="font-size:.88rem;">{{ \Carbon\Carbon::parse($ticket->date_reported)->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="fw-semibold text-dark mb-2" style="font-size:.9rem;">What happens when you cancel:</p>
                        <ul class="list-unstyled mb-0" style="font-size:.88rem; color:#495057;">
                            <li class="mb-2">
                                <i class="fa-solid fa-xmark text-danger me-2"></i>
                                The ticket will be permanently marked as <strong>Closed</strong> and removed from the active queue.
                            </li>
                            <li class="mb-2">
                                <i class="fa-solid fa-xmark text-danger me-2"></i>
                                No technician will be dispatched for this concern.
                            </li>
                            <li>
                                <i class="fa-solid fa-xmark text-danger me-2"></i>
                                This action <strong>cannot be undone</strong>. You will need to submit a new ticket if the issue persists.
                            </li>
                        </ul>
                    </div>

                    <div class="rounded-3 border border-danger px-4 py-3 mb-4"
                         style="background:rgba(220,53,69,.04);">
                        <p class="mb-0 fw-semibold text-danger" style="font-size:.92rem;">
                            <i class="fa-solid fa-circle-question me-2"></i>
                            Are you sure you no longer need assistance for this concern?
                        </p>
                    </div>

                    <div class="d-flex gap-3">
                        <button type="button"
                                class="btn btn-light fw-semibold flex-fill py-2 rounded-3"
                                data-bs-dismiss="modal">
                            <i class="fa-solid fa-arrow-left me-2"></i> No, Keep Ticket
                        </button>
                        <form action="{{ route('customer.tickets.destroy', $ticket->ticket_id) }}"
                              method="POST" class="flex-fill">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger fw-bold w-100 py-2 rounded-3">
                                <i class="fa-solid fa-ban me-2"></i> Yes, Cancel Ticket
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/customer-ticket-show.js') }}"></script>
</body>
</html>