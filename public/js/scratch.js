const CAT_ICON = { hardware: '#i-cpu', software: '#i-apps', network: '#i-wifi', account: '#i-key' };
let cur = 1;

/* Step Control Navigation */
function setStep(n) {
  document.getElementById('panel' + cur).classList.remove('active');
  document.getElementById('panel' + n).classList.add('active');
  
  for (let i = 1; i <= 5; i++) {
    const s = document.getElementById('stp' + i);
    if (!s) continue;
    s.classList.remove('active', 'done');
    if (i < n) s.classList.add('done');
    else if (i === n) s.classList.add('active');
    
    const b = s.querySelector('.step-bubble');
    if (b) {
      if (i < n) {
        b.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width:13px;height:13px;stroke:var(--green)"><path d="M20 6 9 17l-5-5"/></svg>';
      } else {
        b.textContent = i;
      }
    }
    const line = document.getElementById('line' + i);
    if (line) {
      line.classList.remove('done');
      if (i < n) line.classList.add('done');
    }
  }
  cur = n;
  document.getElementById('err-bar').classList.remove('show');
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function back(from) { setStep(from); }

function goStep(from) {
  let ok = true, missing = [];
  
  if (from === 1) {
    const cat = document.querySelector('input[name="p_category"]:checked')?.value;
    if (!cat) {
      document.querySelectorAll('#cat-grid .rcard-inner').forEach(el => el.classList.add('invalid'));
      missing.push('Problem category'); ok = false;
    } else {
      document.querySelectorAll('#cat-grid .rcard-inner').forEach(el => el.classList.remove('invalid'));
    }
  }
  if (from === 2) {
    const desc = document.getElementById('desc');
    if (!desc.value.trim()) { desc.classList.add('invalid'); missing.push('Description'); ok = false; }
  }
  if (from === 3) {
    ['dtype', 'brand', 'model'].forEach(id => {
      const el = document.getElementById(id);
      if (!el.value.trim()) { el.classList.add('invalid'); missing.push(id); ok = false; }
    });
  }
  if (from === 4) {
    ['campus', 'college', 'building', 'room'].forEach(id => {
      const el = document.getElementById(id);
      if (!el || !el.value) {
        if(el) el.classList.add('invalid');
        missing.push(id); ok = false;
      }
    });
  }
  
  if (!ok) {
    document.getElementById('err-msg').textContent = 'Please fill in: ' + missing.join(', ') + '.';
    document.getElementById('err-bar').classList.add('show');
    return;
  }
  
  document.getElementById('err-bar').classList.remove('show');
  const next = from + 1;
  if (next === 5) buildReview();
  setStep(next);
}

/* ── Live Cascading Location Ajax Calls ── */

function onCampusChange() {
  const campusId = document.getElementById('campus').value;
  const college = document.getElementById('college'), b = document.getElementById('building'), r = document.getElementById('room');
  
  // Clear downstream inputs
  college.innerHTML = '<option value="">Select college</option>';
  b.innerHTML = '<option value="">Select building</option>';
  r.innerHTML = '<option value="">Select room</option>';
  
  b.disabled = true; r.disabled = true;
  document.getElementById('campus').classList.remove('invalid');
  
  if (!campusId) { college.disabled = true; return; }
  
  college.disabled = false;
  college.innerHTML = '<option value="">Loading colleges...</option>';
  
  fetch(`/customer/tickets/get-colleges/${campusId}`)
    .then(res => res.json())
    .then(data => {
      college.innerHTML = '<option value="">Select college</option>';
      data.forEach(item => {
        const o = document.createElement('option');
        o.value = item.college_id;
        o.textContent = item.college_name;
        college.appendChild(o);
      });
    }).catch(err => {
      college.innerHTML = '<option value="">Error loading records</option>';
      console.error(err);
    });
}

function onCollegeChange() {
  const collegeId = document.getElementById('college').value;
  const b = document.getElementById('building'), r = document.getElementById('room');
  
  b.innerHTML = '<option value="">Select building</option>';
  r.innerHTML = '<option value="">Select room</option>';
  r.disabled = true;
  document.getElementById('college').classList.remove('invalid');
  
  if (!collegeId) { b.disabled = true; return; }
  
  b.disabled = false;
  b.innerHTML = '<option value="">Loading buildings...</option>';
  
  fetch(`/customer/tickets/get-buildings/${collegeId}`)
    .then(res => res.json())
    .then(data => {
      b.innerHTML = '<option value="">Select building</option>';
      data.forEach(item => {
        const o = document.createElement('option');
        o.value = item.building_id;
        o.textContent = item.building_name;
        b.appendChild(o);
      });
    }).catch(err => {
      b.innerHTML = '<option value="">Error loading buildings</option>';
    });
}

function onBuildingChange() {
  const buildingId = document.getElementById('building').value;
  const r = document.getElementById('room');
  
  r.innerHTML = '<option value="">Select room</option>';
  document.getElementById('building').classList.remove('invalid');
  
  if (!buildingId) { r.disabled = true; return; }
  
  r.disabled = false;
  r.innerHTML = '<option value="">Loading rooms...</option>';
  
  fetch(`/customer/tickets/get-rooms/${buildingId}`)
    .then(res => res.json())
    .then(data => {
      r.innerHTML = '<option value="">Select room</option>';
      data.forEach(item => {
        const o = document.createElement('option');
        o.value = item.loc_id;
        o.textContent = `Room ${item.room_no} - ${item.room_name} (Floor ${item.floor_no})`;
        r.appendChild(o);
      });
    }).catch(err => {
      r.innerHTML = '<option value="">Error loading locations</option>';
    });
}

/* Review Compilation Node Mapping */
function buildReview() {
  const campEl = document.getElementById('campus');
  const collEl = document.getElementById('college');
  const bldEl = document.getElementById('building');
  const rmEl = document.getElementById('room');

  sv('r-campus', campEl.options[campEl.selectedIndex]?.text);
  sv('r-college', collEl.options[collEl.selectedIndex]?.text);
  sv('r-building', bldEl.options[bldEl.selectedIndex]?.text);
  sv('r-room', rmEl.options[rmEl.selectedIndex]?.text);
  
  const cat = document.querySelector('input[name="p_category"]:checked')?.value;
  const desc = document.getElementById('desc').value.trim();
  const ce = document.getElementById('r-cat');
  
  if (cat) {
    ce.innerHTML = `<span class="badge">${cat.toUpperCase()}</span>`;
    ce.classList.remove('empty');
  } else {
    ce.textContent = 'Not selected'; ce.classList.add('empty');
  }
  
  const de = document.getElementById('r-desc');
  if (desc) { de.textContent = desc; de.classList.remove('empty'); }
  else { de.textContent = 'No description provided'; de.classList.add('empty'); }
  
  sv('r-dtype', document.getElementById('dtype').value);
  sv('r-brand', document.getElementById('brand').value);
  sv('r-model', document.getElementById('model').value.trim());
}

function sv(id, val) {
  const el = document.getElementById(id);
  if (!el) return;
  if (!val || val.includes('Select')) {
    el.textContent = '—'; el.classList.add('empty');
  } else {
    el.textContent = val; el.classList.remove('empty');
  }
}

/* Submission Handler */
function trySubmit() {
  const form = document.getElementById('live-ticket-form');
  const btn = document.getElementById('sub-btn'), ico = document.getElementById('sub-ico'), lbl = document.getElementById('sub-lbl');
  
  btn.disabled = true;
  ico.classList.add('spin');
  ico.innerHTML = '<use href="#i-loader"/>';
  lbl.textContent = 'Uploading to ledger...';
  
  // Submit the real HTML form to Laravel backend action controller channel safely
  form.submit();
}

function handleExit() {
  if (confirm("Are you sure you want to exit? Your current ticket draft will be lost.")) {
    window.location.href = "/customer/tickets";
  }
}

function closeModal() { document.getElementById('modal').classList.remove('show'); }
function clearErr() { document.getElementById('err-bar').classList.remove('show'); }
function clearInvalid(el) { el.classList.remove('invalid'); }
function updateChar() { document.getElementById('cc').textContent = document.getElementById('desc').value.length + ' / 500'; }