// popup.js - FIXED: Always Track, Then Check Logic

document.addEventListener("DOMContentLoaded", function() {

    // --- 1. TRACKING FIRST (Always run this!) ---
    
    // Get or Create Device ID
    let deviceId = localStorage.getItem('unique_device_id');
    if (!deviceId) {
        deviceId = 'user_' + Math.random().toString(36).substr(2, 9);
        localStorage.setItem('unique_device_id', deviceId);
    }
    
    // Send Signal to Server
    var data = new FormData();
    data.append('url', window.location.href);
    data.append('device_id', deviceId);
    navigator.sendBeacon('track.php', data);


    // --- 2. STOP IF ALREADY SUBMITTED ---
    // If they already gave their number, stop here. 
    // (They are tracked above, but won't see the popup below)
    if (localStorage.getItem('leadSubmitted') === 'true') {
        return; 
    }


    // --- 3. INJECT HTML (The Lock Screen) ---
    const popupHTML = `
    <div id="leadModal" class="modal">
      <div class="modal-content">
        <h2>🔒 Unlock Your Exam Access</h2>
        <p>You have been using our free resources!</p>
        <p>To continue practicing and get <strong>Premium Templates</strong>, please verify your WhatsApp number.</p>
        
        <div id="leadForm">
            <input type="text" id="userName" placeholder="Your Name" class="input-field">
            <input type="tel" id="userPhone" placeholder="WhatsApp Number" class="input-field">
            <button onclick="submitLead()" class="submit-btn">Unlock Now</button>
        </div>

        <div id="successMessage" style="display:none; color: green; margin-top:20px;">
            <h3>✅ Unlocked!</h3>
            <p>You can now continue practicing.</p>
        </div>
      </div>
    </div>`;
    
    document.body.insertAdjacentHTML('beforeend', popupHTML);


    // --- 4. LOCK LOGIC (5th Visit OR 5 Minutes) ---
    
    // A. Check Immediate Lock (If they refreshed while locked)
    if (localStorage.getItem('strictLock') === 'true') {
        showLockScreen();
    }

    // B. Increment Visit Counter
    let visits = localStorage.getItem('visitCount') || 0;
    visits = parseInt(visits) + 1;
    localStorage.setItem('visitCount', visits);

    // C. Check if this is the 5th visit
    if (visits >= 5) {
        localStorage.setItem('strictLock', 'true'); 
        showLockScreen();
    }

    // D. 5-Minute Timer (300,000 ms)
    setTimeout(function() {
        localStorage.setItem('strictLock', 'true');
        showLockScreen();
    }, 300000); 

});


// --- HELPER FUNCTIONS ---

function showLockScreen() {
    document.getElementById('leadModal').style.display = "block";
    document.body.style.overflow = "hidden"; // Stop scrolling
}

function submitLead() {
    let name = document.getElementById('userName').value;
    let phone = document.getElementById('userPhone').value;
    let deviceId = localStorage.getItem('unique_device_id');

    if(name.length < 2 || phone.length < 9) { 
        alert("Please enter a valid Name and WhatsApp Number."); 
        return; 
    }

    var formData = new FormData();
    formData.append('device_id', deviceId);
    formData.append('name', name);
    formData.append('phone', phone);

    fetch('save_lead.php', { method: 'POST', body: formData })
    .then(response => response.text())
    .then(data => {
        document.getElementById('leadForm').style.display = 'none';
        document.getElementById('successMessage').style.display = 'block';
        
        // Mark as submitted
        localStorage.setItem('leadSubmitted', 'true');
        localStorage.removeItem('strictLock');
        
        // Unlock screen
        setTimeout(function() {
            document.getElementById('leadModal').style.display = "none";
            document.body.style.overflow = "auto"; 
        }, 2000);
    });
}