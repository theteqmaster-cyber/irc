/**
 * StudyBee Version 2.0 - Adaptive University AI Study Workspace
 * Vibrant Canva / Studley Aesthetic with Tailwind & Lucide Icons
 */

let currentWorkspaceId = '';
let currentFlashcards = [];
let currentCardIndex = 0;
let isCardFlipped = false;
let selectedSubject = 'General';
let deepReasoningActive = true;

// Timers
let pomodoroSeconds = 1500; // 25 mins
let pomodoroInterval = null;
let blurtingSeconds = 300; // 5 mins
let blurtingInterval = null;
let arenaSeconds = 900; // 15 mins
let arenaInterval = null;

document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) lucide.createIcons();
    initSidebar();
    initViewNavigation();
    initDashboard();
    initWorkspaces();
    initVault();
    initDeepSolve();
    initExaminer();
    initMethodStudio();
    initExamArena();
    initSupportModal();
});

/* ==========================================================================
   GLOBAL VIEW NAVIGATION
   ========================================================================== */
window.switchView = function(targetViewId, callback) {
    const navItems = document.querySelectorAll('.nav-item');
    const views = document.querySelectorAll('.view-pane');

    navItems.forEach(n => {
        if (n.getAttribute('data-view') === targetViewId) {
            n.classList.add('active');
        } else {
            n.classList.remove('active');
        }
    });

    views.forEach(v => {
        if (v.id === targetViewId) {
            v.classList.add('active-view');
        } else {
            v.classList.remove('active-view');
        }
    });

    const mainEl = document.querySelector('main');
    if (mainEl) mainEl.scrollTop = 0;

    if (window.lucide) lucide.createIcons();
    if (typeof callback === 'function') {
        setTimeout(callback, 60);
    }
};

/* ==========================================================================
   1. SIDEBAR & VIEW NAVIGATION
   ========================================================================== */
function initSidebar() {
    const toggleBtn = document.getElementById('sidebarToggleBtn');
    const sidebar = document.getElementById('mainSidebar');

    if (localStorage.getItem('studybee_sidebar_collapsed') === 'true') {
        sidebar?.classList.add('collapsed');
    }

    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('studybee_sidebar_collapsed', sidebar.classList.contains('collapsed'));
        });
    }
}

function initViewNavigation() {
    const navItems = document.querySelectorAll('.nav-item');
    navItems.forEach(item => {
        item.addEventListener('click', () => {
            const targetViewId = item.getAttribute('data-view');
            if (targetViewId) {
                switchView(targetViewId);
            }
        });
    });
}

/* ==========================================================================
   LANDING DASHBOARD & STUDYBEE MASCOT
   ========================================================================== */
function initDashboard() {
    // Dynamic Time Greeting
    const greetingEl = document.getElementById('landingGreetingTitle');
    if (greetingEl) {
        const hour = new Date().getHours();
        let timeOfDay = 'day';
        if (hour < 12) timeOfDay = 'morning';
        else if (hour < 18) timeOfDay = 'afternoon';
        else timeOfDay = 'evening';
        
        greetingEl.innerHTML = `Good ${timeOfDay}, <span class="bg-gradient-to-r from-indigo-600 via-purple-600 to-amber-500 bg-clip-text text-transparent">Scholar</span> ✨`;
    }

    // Header Logo Click -> Return to Dashboard
    const headerHomeBtn = document.getElementById('headerHomeBtn');
    if (headerHomeBtn) {
        headerHomeBtn.addEventListener('click', () => {
            switchView('view-dashboard');
        });
    }

    // Mascot Interactivity (Speech Bubble & Mascot Click for Tips)
    const mascotSvg = document.getElementById('studyBeeMascot');
    const speechBubble = document.getElementById('beeSpeechBubble');
    const speechText = document.getElementById('beeSpeechText');

    const studyBeeTips = [
        "Bzzz! Ready to crush today's study goals? 🚀",
        "Tip: The Feynman technique boosts concept retention by 85%! 🧠",
        "Drop your PDF lecture notes into The Vault for instant citations 📚",
        "Stuck on a tricky math proof? Let DeepSolve break it down step-by-step ⚡",
        "25 minutes of deep focus beats 3 hours of distracted reading ⏱️",
        "Need essay feedback? The Examiner checks against university marking schemes! 📝",
        "Active recall triggers strong neural connections before exam day 💡"
    ];
    let tipIndex = 0;

    function cycleMascotTip() {
        tipIndex = (tipIndex + 1) % studyBeeTips.length;
        if (speechText) {
            speechText.style.opacity = '0';
            setTimeout(() => {
                speechText.textContent = studyBeeTips[tipIndex];
                speechText.style.opacity = '1';
            }, 150);
        }
    }

    mascotSvg?.addEventListener('click', cycleMascotTip);
    speechBubble?.addEventListener('click', cycleMascotTip);

    // Omnibar Mode Selector
    let currentOmniMode = 'auto';
    const modeChips = document.querySelectorAll('.omni-mode-chip');
    modeChips.forEach(chip => {
        chip.addEventListener('click', () => {
            modeChips.forEach(c => {
                c.classList.remove('active', 'bg-indigo-600', 'text-white', 'shadow-xs');
                c.classList.add('bg-slate-100', 'text-slate-600');
            });
            chip.classList.add('active', 'bg-indigo-600', 'text-white', 'shadow-xs');
            chip.classList.remove('bg-slate-100', 'text-slate-600');
            currentOmniMode = chip.getAttribute('data-mode') || 'auto';
        });
    });

    // Omnibar Execution
    const omniInput = document.getElementById('dashboardOmniInput');
    const omniSendBtn = document.getElementById('dashboardOmniSendBtn');

    function executeOmnibar() {
        if (!omniInput) return;
        const query = omniInput.value.trim();
        if (!query) {
            omniInput.focus();
            return;
        }

        const lowerQuery = query.toLowerCase();

        // 1. Solve mode or math/derivation keywords
        if (currentOmniMode === 'solve' || (currentOmniMode === 'auto' && (
            lowerQuery.includes('solve') || lowerQuery.includes('derive') || lowerQuery.includes('proof') ||
            lowerQuery.includes('equation') || lowerQuery.includes('formula') || lowerQuery.includes('calculus') ||
            lowerQuery.includes('physics') || lowerQuery.includes('theorem') || lowerQuery.includes('calculate')
        ))) {
            switchView('view-solve', () => {
                const solveInput = document.getElementById('solveQuestionInput');
                if (solveInput) {
                    solveInput.value = query;
                    document.getElementById('runSolveBtn')?.click();
                }
            });
        }
        // 2. Examiner / Rubric mode or grading keywords
        else if (currentOmniMode === 'examiner' || (currentOmniMode === 'auto' && (
            lowerQuery.includes('grade') || lowerQuery.includes('rubric') || lowerQuery.includes('essay') ||
            lowerQuery.includes('marking') || lowerQuery.includes('draft') || lowerQuery.includes('thesis')
        ))) {
            switchView('view-examiner', () => {
                const draftInput = document.getElementById('examinerDraftInput');
                if (draftInput) {
                    draftInput.value = query;
                    draftInput.focus();
                }
            });
        }
        // 3. Feynman / Study method mode
        else if (currentOmniMode === 'feynman' || (currentOmniMode === 'auto' && (
            lowerQuery.includes('feynman') || lowerQuery.includes('teach') || lowerQuery.includes('explain simply')
        ))) {
            switchView('view-methods', () => {
                document.querySelector('[data-method="method-feynman"]')?.click();
                const feynmanTopic = document.getElementById('feynmanTopicInput');
                if (feynmanTopic) {
                    feynmanTopic.value = query;
                    document.getElementById('feynmanExplanationInput')?.focus();
                }
            });
        }
        // 4. Default: Vault Notes Chat
        else {
            switchView('view-vault', () => {
                document.querySelector('[data-vtab="vtab-chat"]')?.click();
                const vaultInput = document.getElementById('vaultChatInput');
                if (vaultInput) {
                    vaultInput.value = query;
                    document.getElementById('vaultChatSendBtn')?.click();
                }
            });
        }

        omniInput.value = '';
    }

    omniSendBtn?.addEventListener('click', executeOmnibar);
    omniInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            executeOmnibar();
        }
    });

    // Suggested Prompt Chips Click Handlers
    const promptChips = document.querySelectorAll('.prompt-chip');
    promptChips.forEach(chip => {
        chip.addEventListener('click', () => {
            const promptText = chip.getAttribute('data-prompt') || '';
            const targetView = chip.getAttribute('data-target') || 'view-vault';
            const vtab = chip.getAttribute('data-vtab');
            const method = chip.getAttribute('data-method');

            switchView(targetView, () => {
                if (targetView === 'view-solve') {
                    const solveInput = document.getElementById('solveQuestionInput');
                    if (solveInput) {
                        solveInput.value = promptText;
                        document.getElementById('runSolveBtn')?.click();
                    }
                } else if (targetView === 'view-examiner') {
                    const draftInput = document.getElementById('examinerDraftInput');
                    if (draftInput) {
                        draftInput.value = promptText;
                        draftInput.focus();
                    }
                } else if (targetView === 'view-methods') {
                    if (method) {
                        document.querySelector(`[data-method="${method}"]`)?.click();
                    }
                    if (method === 'method-feynman') {
                        const feynmanTopic = document.getElementById('feynmanTopicInput');
                        if (feynmanTopic) {
                            feynmanTopic.value = promptText;
                            document.getElementById('feynmanExplanationInput')?.focus();
                        }
                    }
                } else if (targetView === 'view-vault') {
                    if (vtab) {
                        document.querySelector(`[data-vtab="${vtab}"]`)?.click();
                        if (vtab === 'vtab-quiz') {
                            document.getElementById('loadQuizBtn')?.click();
                        }
                    } else {
                        const vaultInput = document.getElementById('vaultChatInput');
                        if (vaultInput) {
                            vaultInput.value = promptText;
                            document.getElementById('vaultChatSendBtn')?.click();
                        }
                    }
                }
            });
        });
    });
}

/* ==========================================================================
   2. WORKSPACES
   ========================================================================== */
async function initWorkspaces() {
    const select = document.getElementById('workspaceSelect');
    const omniWs = document.getElementById('omniActiveWorkspaceName');
    const openNewWsBtn = document.getElementById('openNewWorkspaceBtn');
    const newWsModal = document.getElementById('newWorkspaceModal');
    const closeNewWsBtn = document.getElementById('closeNewWorkspaceModalBtn');
    const cancelNewWsBtn = document.getElementById('cancelNewWorkspaceBtn');
    const submitNewWsBtn = document.getElementById('submitNewWorkspaceBtn');

    if (!select) return;

    // Load initial workspaces list
    try {
        const res = await fetch('/api/workspaces');
        const json = await res.json();
        if (json.status === 'success' && json.data.length > 0) {
            select.innerHTML = '';
            json.data.forEach(ws => {
                const opt = document.createElement('option');
                opt.value = ws.id;
                opt.textContent = `${ws.name} (${ws.category || 'General'})`;
                select.appendChild(opt);
            });
            currentWorkspaceId = json.data[0].id;
            if (omniWs && select.options[0]) {
                omniWs.textContent = select.options[0].textContent;
            }
            loadWorkspaceDocuments(currentWorkspaceId);
            loadFlashcards();
        }
    } catch (e) {
        console.warn('Could not load workspaces:', e);
    }

    select.addEventListener('change', (e) => {
        currentWorkspaceId = e.target.value;
        if (omniWs && select.options[select.selectedIndex]) {
            omniWs.textContent = select.options[select.selectedIndex].textContent;
        }
        loadWorkspaceDocuments(currentWorkspaceId);
        loadFlashcards();
    });

    // Workspace Creation Modal
    if (openNewWsBtn && newWsModal) {
        openNewWsBtn.addEventListener('click', () => {
            newWsModal.style.display = 'flex';
            document.getElementById('newWorkspaceNameInput')?.focus();
        });
    }

    const closeWorkspaceModal = () => {
        if (newWsModal) newWsModal.style.display = 'none';
    };

    if (closeNewWsBtn) closeNewWsBtn.addEventListener('click', closeWorkspaceModal);
    if (cancelNewWsBtn) cancelNewWsBtn.addEventListener('click', closeWorkspaceModal);

    if (submitNewWsBtn) {
        submitNewWsBtn.addEventListener('click', async () => {
            const nameInput = document.getElementById('newWorkspaceNameInput');
            const catSelect = document.getElementById('newWorkspaceCategorySelect');
            const descInput = document.getElementById('newWorkspaceDescInput');

            const name = nameInput ? nameInput.value.trim() : '';
            const category = catSelect ? catSelect.value : 'General';
            const description = descInput ? descInput.value.trim() : '';

            if (!name) {
                alert('Please enter a course or subject name.');
                return;
            }

            submitNewWsBtn.textContent = 'Creating Vault...';
            try {
                const res = await fetch('/api/workspaces', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, category, description })
                });
                const json = await res.json();
                if (json.status === 'success') {
                    const newWs = json.data;
                    const opt = document.createElement('option');
                    opt.value = newWs.id;
                    opt.textContent = `${newWs.name} (${newWs.category || 'General'})`;
                    select.appendChild(opt);
                    select.value = newWs.id;
                    currentWorkspaceId = newWs.id;
                    if (omniWs) omniWs.textContent = opt.textContent;

                    if (nameInput) nameInput.value = '';
                    if (descInput) descInput.value = '';
                    closeWorkspaceModal();
                    loadWorkspaceDocuments(currentWorkspaceId);
                    loadFlashcards();
                } else {
                    alert('Error creating workspace: ' + (json.message || 'Unknown error'));
                }
            } catch (e) {
                alert('Failed to create workspace.');
            } finally {
                submitNewWsBtn.textContent = 'Create Vault';
            }
        });
    }
}

async function loadWorkspaceDocuments(workspaceId) {
    const viewer = document.getElementById('documentViewer');
    const badge = document.getElementById('docCountBadge');
    if (!viewer) return;

    viewer.innerHTML = '<div class="text-xs text-indigo-600 text-center py-6 animate-pulse">Loading course notes...</div>';

    try {
        const res = await fetch(`/api/documents?workspace_id=${workspaceId}`);
        const json = await res.json();
        
        if (json.status === 'success' && json.data.length > 0) {
            if (badge) badge.textContent = `${json.data.length} Note${json.data.length > 1 ? 's' : ''} Loaded`;
            viewer.innerHTML = json.data.map(doc => `
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 hover:border-indigo-200 transition-all space-y-1.5">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-bold text-slate-800 truncate max-w-[200px]">${escapeHtml(doc.filename)}</h4>
                        <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">${doc.total_pages || 1} Pages</span>
                    </div>
                    <p class="text-[11px] text-slate-500 line-clamp-3 leading-relaxed">${escapeHtml(doc.summary || 'Course document indexed into Vault.')}</p>
                </div>
            `).join('');
        } else {
            if (badge) badge.textContent = '1 Default Note';
            viewer.innerHTML = `
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-100 space-y-1.5">
                    <div class="flex items-center justify-between mb-1">
                        <h4 class="text-xs font-bold text-slate-800">Core Syllabus & Foundations</h4>
                        <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">Default Note</span>
                    </div>
                    <p class="text-[11px] text-slate-600 leading-relaxed">
                        <b>Core Examination Syllabus & Key Principles:</b><br>
                        • 1st Law (Inertia): Objects persist in uniform state unless acted upon by net external force.<br>
                        • 2nd Law (F=ma): Rate of change of momentum is proportional to applied net force.<br>
                        • 3rd Law: Action and reaction forces are equal in magnitude, opposite in direction.
                    </p>
                </div>
            `;
        }
    } catch (e) {
        viewer.innerHTML = '<div class="text-xs text-rose-500 p-4">Could not load document index.</div>';
    }
    if (window.lucide) lucide.createIcons();
}

/* ==========================================================================
   3. PILLAR 1: THE VAULT
   ========================================================================== */
function initVault() {
    // Vault Sub-tabs
    const vtabBtns = document.querySelectorAll('.vault-tab-btn');
    vtabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            vtabBtns.forEach(b => {
                b.classList.remove('active', 'bg-white', 'text-slate-900', 'shadow-sm');
                b.classList.add('text-slate-600');
            });
            document.querySelectorAll('.vtab-content').forEach(c => c.style.display = 'none');

            btn.classList.add('active', 'bg-white', 'text-slate-900', 'shadow-sm');
            btn.classList.remove('text-slate-600');
            const target = document.getElementById(btn.getAttribute('data-vtab'));
            if (target) target.style.display = 'flex';
            if (window.lucide) lucide.createIcons();
        });
    });

    // Vault Chat
    const chatInput = document.getElementById('vaultChatInput');
    const sendBtn = document.getElementById('vaultChatSendBtn');
    if (sendBtn && chatInput) {
        sendBtn.addEventListener('click', sendVaultChatMessage);
        chatInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') sendVaultChatMessage();
        });
    }

    // Flashcards controls & keyboard navigation
    document.getElementById('generateCardsBtn')?.addEventListener('click', generateNewFlashcards);
    document.getElementById('nextCardBtn')?.addEventListener('click', nextFlashcard);
    document.getElementById('prevCardBtn')?.addEventListener('click', prevFlashcard);
    
    const markMasteredBtn = document.getElementById('markMasteredBtn');
    if (markMasteredBtn) {
        markMasteredBtn.addEventListener('click', () => {
            markMasteredBtn.innerHTML = '<i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i><span>Mastered! ✓</span>';
            if (window.lucide) lucide.createIcons();
            setTimeout(() => {
                markMasteredBtn.innerHTML = '<i data-lucide="check-circle" class="w-3.5 h-3.5"></i><span>Mastered</span>';
                if (window.lucide) lucide.createIcons();
                nextFlashcard();
            }, 700);
        });
    }

    window.addEventListener('keydown', (e) => {
        const cardsTab = document.getElementById('vtab-cards');
        if (cardsTab && cardsTab.style.display !== 'none') {
            if (e.code === 'Space' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
                e.preventDefault();
                flipActiveCard();
            } else if (e.code === 'ArrowRight' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
                nextFlashcard();
            } else if (e.code === 'ArrowLeft' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
                prevFlashcard();
            }
        }
    });

    // Quiz
    document.getElementById('loadQuizBtn')?.addEventListener('click', generatePracticeQuiz);
}

async function handleVaultUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('file', file);
    formData.append('workspace_id', currentWorkspaceId);

    const viewer = document.getElementById('documentViewer');
    if (viewer) viewer.innerHTML = '<div class="text-xs text-indigo-600 text-center py-8 animate-pulse">Extracting & indexing notes with Groq AI engine...</div>';

    try {
        const res = await fetch('/api/documents', {
            method: 'POST',
            body: formData
        });
        const json = await res.json();
        if (json.status === 'success') {
            loadWorkspaceDocuments(currentWorkspaceId);
            loadFlashcards();
        } else {
            alert('Upload error: ' + json.message);
        }
    } catch (e) {
        alert('File upload failed.');
    }
}

async function sendVaultChatMessage() {
    const input = document.getElementById('vaultChatInput');
    const thread = document.getElementById('vaultChatThread');
    if (!input || !thread) return;

    const message = input.value.trim();
    if (!message) return;

    // Append User Message Bubble
    const userBubble = document.createElement('div');
    userBubble.className = 'p-3 rounded-2xl bg-indigo-600 text-white text-xs font-medium ml-auto max-w-[85%] shadow-sm';
    userBubble.textContent = message;
    thread.appendChild(userBubble);
    input.value = '';
    thread.scrollTop = thread.scrollHeight;

    // Append AI Loading Bubble
    const aiBubble = document.createElement('div');
    aiBubble.className = 'p-3.5 rounded-2xl bg-indigo-50/70 border border-indigo-100 text-xs text-slate-700 max-w-[90%]';
    aiBubble.innerHTML = '<span class="text-indigo-600 font-semibold animate-pulse">Thinking with Vault citations...</span>';
    thread.appendChild(aiBubble);
    thread.scrollTop = thread.scrollHeight;

    try {
        const res = await fetch('/api/chat', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ workspace_id: currentWorkspaceId, message })
        });
        const json = await res.json();
        if (json.status === 'success') {
            let citationsHtml = '';
            if (json.data.citations && json.data.citations.length > 0) {
                citationsHtml = '<div class="mt-2 text-[10px] font-bold text-indigo-600 bg-white/80 p-1.5 rounded-lg border border-indigo-100 inline-block">' +
                    json.data.citations.map(c => `[Page ${c.page_number}]`).join(' ') + '</div>';
            }
            aiBubble.innerHTML = formatMarkdownText(json.data.message) + citationsHtml;
        } else {
            aiBubble.textContent = 'Error: ' + json.message;
        }
    } catch (e) {
        aiBubble.textContent = 'Network error communicating with AI engine.';
    }
    thread.scrollTop = thread.scrollHeight;
    if (window.lucide) lucide.createIcons();
}

function flipActiveCard() {
    const card = document.getElementById('activeFlashcard');
    if (!card) return;
    isCardFlipped = !isCardFlipped;
    card.classList.toggle('flipped', isCardFlipped);
}

function renderFlashcard() {
    const qText = document.getElementById('cardQuestionText');
    const aText = document.getElementById('cardAnswerText');
    const card = document.getElementById('activeFlashcard');
    const counterBadge = document.getElementById('flashcardCounter');
    
    if (isCardFlipped && card) {
        isCardFlipped = false;
        card.classList.remove('flipped');
    }

    if (currentFlashcards.length === 0) {
        if (qText) qText.textContent = "Click 'Generate Cards' to extract active recall cards from notes.";
        if (aText) aText.textContent = "";
        if (counterBadge) counterBadge.textContent = "0 of 0";
        return;
    }

    const item = currentFlashcards[currentCardIndex];
    if (qText) qText.textContent = item.question;
    if (aText) aText.textContent = item.answer;
    if (counterBadge) counterBadge.textContent = `Card ${currentCardIndex + 1} of ${currentFlashcards.length}`;
}

function nextFlashcard() {
    if (currentFlashcards.length === 0) return;
    currentCardIndex = (currentCardIndex + 1) % currentFlashcards.length;
    renderFlashcard();
}

function prevFlashcard() {
    if (currentFlashcards.length === 0) return;
    currentCardIndex = (currentCardIndex - 1 + currentFlashcards.length) % currentFlashcards.length;
    renderFlashcard();
}

async function loadFlashcards() {
    try {
        const res = await fetch(`/api/flashcards?workspace_id=${currentWorkspaceId}`);
        const json = await res.json();
        if (json.status === 'success' && json.data.length > 0) {
            currentFlashcards = json.data;
            currentCardIndex = 0;
            renderFlashcard();
        }
    } catch (e) {
        console.warn('Could not load flashcards:', e);
    }
}

async function generateNewFlashcards() {
    const qText = document.getElementById('cardQuestionText');
    if (qText) qText.textContent = 'Extracting active recall flashcards from notes...';

    try {
        const res = await fetch('/api/flashcards', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ workspace_id: currentWorkspaceId })
        });
        const json = await res.json();
        if (json.status === 'success' && json.data.length > 0) {
            currentFlashcards = json.data;
            currentCardIndex = 0;
            renderFlashcard();
        }
    } catch (e) {
        alert('Could not generate flashcards.');
    }
}

async function generatePracticeQuiz() {
    const container = document.getElementById('quizContainer');
    if (!container) return;

    container.innerHTML = '<div class="text-xs text-indigo-600 text-center py-8 animate-pulse">Generating practice questions from Vault notes...</div>';

    try {
        const res = await fetch(`/api/quiz?workspace_id=${currentWorkspaceId}`);
        const json = await res.json();
        if (json.status === 'success' && json.data.length > 0) {
            container.innerHTML = json.data.map((q, idx) => `
                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 space-y-2">
                    <div class="text-xs font-bold text-slate-800">Q${idx+1}: ${escapeHtml(q.question)}</div>
                    ${q.options && q.options.length > 0 ? `
                        <div class="space-y-1.5 pt-1">
                            ${q.options.map((opt, oIdx) => `
                                <label class="flex items-center gap-2.5 text-xs text-slate-700 bg-white p-2.5 rounded-xl border border-slate-200/80 cursor-pointer hover:border-indigo-300 transition-all">
                                    <input type="radio" name="quiz_opt_${idx}" value="${oIdx}" onchange="checkQuizAnswer(this, ${q.correct_index}, '${escapeHtml(q.explanation || '')}')" class="text-indigo-600 focus:ring-indigo-500">
                                    <span>${escapeHtml(opt)}</span>
                                </label>
                            `).join('')}
                        </div>
                    ` : `
                        <input type="text" class="w-full bg-white border border-slate-200 text-xs px-3 py-2 rounded-xl" placeholder="Type your answer...">
                    `}
                    <div id="quizFeedback_${idx}" class="text-[11px] pt-1" style="display:none;"></div>
                </div>
            `).join('');
        }
    } catch (e) {
        container.innerHTML = '<div class="text-xs text-rose-500 p-4">Could not load practice quiz.</div>';
    }
}

window.checkQuizAnswer = function(radioEl, correctIndex, explanation) {
    const feedback = radioEl.parentElement.parentElement.nextElementSibling;
    if (!feedback) return;

    feedback.style.display = 'block';
    if (parseInt(radioEl.value) === correctIndex) {
        feedback.innerHTML = `<span class="font-bold text-emerald-600">✓ Correct!</span> <span class="text-slate-600">${explanation}</span>`;
    } else {
        feedback.innerHTML = `<span class="font-bold text-rose-600">✗ Incorrect.</span> <span class="text-slate-600">${explanation}</span>`;
    }
};

/* ==========================================================================
   4. PILLAR 2: DEEPSOLVE
   ========================================================================== */
function initDeepSolve() {
    const pills = document.querySelectorAll('.subject-pill');
    pills.forEach(pill => {
        pill.addEventListener('click', () => {
            pills.forEach(p => {
                p.classList.remove('active', 'bg-purple-100', 'text-purple-700', 'font-bold', 'border-purple-200');
                p.classList.add('bg-white', 'text-slate-600', 'font-semibold');
            });
            pill.classList.add('active', 'bg-purple-100', 'text-purple-700', 'font-bold', 'border-purple-200');
            pill.classList.remove('bg-white', 'text-slate-600');
            selectedSubject = pill.getAttribute('data-subject') || 'General';
        });
    });

    const toggle = document.getElementById('deepReasoningToggle');
    if (toggle) {
        toggle.addEventListener('click', () => {
            deepReasoningActive = !deepReasoningActive;
            const knob = toggle.querySelector('.toggle-switch div');
            const toggleBg = toggle.querySelector('.toggle-switch');
            
            if (deepReasoningActive) {
                toggle.classList.add('bg-purple-50', 'border-purple-200/80');
                toggleBg.classList.add('bg-purple-600');
                toggleBg.classList.remove('bg-slate-300');
                knob.classList.remove('left-1');
                knob.classList.add('right-1');
            } else {
                toggle.classList.remove('bg-purple-50', 'border-purple-200/80');
                toggleBg.classList.remove('bg-purple-600');
                toggleBg.classList.add('bg-slate-300');
                knob.classList.add('left-1');
                knob.classList.remove('right-1');
            }
        });
    }

    const solveBtn = document.getElementById('runSolveBtn');
    if (solveBtn) {
        solveBtn.addEventListener('click', async () => {
            const input = document.getElementById('solveQuestionInput');
            const outputBox = document.getElementById('solveOutputContainer');
            const bodyText = document.getElementById('solutionTextBody');
            const subjectTag = document.getElementById('solvedSubjectTag');

            const question = input ? input.value.trim() : '';
            if (!question) {
                alert('Please enter a question to solve.');
                return;
            }

            outputBox.style.display = 'block';
            bodyText.innerHTML = '<div class="text-purple-600 font-semibold animate-pulse py-4">Applying Deep Reasoning & Derivations with Groq Engine...</div>';
            if (subjectTag) subjectTag.textContent = selectedSubject;

            try {
                const res = await fetch('/api/solve', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        workspace_id: currentWorkspaceId,
                        question,
                        subject: selectedSubject,
                        deep_reasoning: deepReasoningActive
                    })
                });
                const json = await res.json();
                if (json.status === 'success') {
                    bodyText.innerHTML = formatMarkdownText(json.data.solution);
                } else {
                    bodyText.textContent = 'Error: ' + json.message;
                }
            } catch (e) {
                bodyText.textContent = 'Network error while generating solution.';
            }
            if (window.lucide) lucide.createIcons();
        });
    }

    // Copy Solution Button
    const copyBtn = document.getElementById('copySolutionBtn');
    if (copyBtn) {
        copyBtn.addEventListener('click', () => {
            const bodyText = document.getElementById('solutionTextBody');
            if (!bodyText) return;
            navigator.clipboard.writeText(bodyText.innerText).then(() => {
                copyBtn.innerHTML = '<i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i><span>Copied!</span>';
                if (window.lucide) lucide.createIcons();
                setTimeout(() => {
                    copyBtn.innerHTML = '<i data-lucide="copy" class="w-3.5 h-3.5"></i><span>Copy</span>';
                    if (window.lucide) lucide.createIcons();
                }, 2000);
            });
        });
    }
}

/* ==========================================================================
   5. PILLAR 3: THE EXAMINER
   ========================================================================== */
function initExaminer() {
    const gradeBtn = document.getElementById('runGradePaperBtn');
    if (!gradeBtn) return;

    gradeBtn.addEventListener('click', async () => {
        const rubricInput = document.getElementById('examinerRubricInput');
        const draftInput = document.getElementById('examinerDraftInput');
        const reportOutput = document.getElementById('examinerOutputReport');
        const scoreBadge = document.getElementById('rubricScoreBadge');
        const scoreNum = document.getElementById('rubricScoreNum');

        const essay = draftInput ? draftInput.value.trim() : '';
        const rubric = rubricInput ? rubricInput.value.trim() : '';

        if (!essay) {
            alert('Please paste your essay draft to evaluate.');
            return;
        }

        reportOutput.innerHTML = '<div class="text-pink-600 font-semibold animate-pulse py-4">Evaluating draft against marking scheme criteria...</div>';

        try {
            const res = await fetch('/api/rubric', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ essay, rubric })
            });
            const json = await res.json();
            if (json.status === 'success') {
                if (scoreBadge && scoreNum) {
                    scoreBadge.style.display = 'flex';
                    scoreNum.textContent = json.data.score || 82;
                }
                reportOutput.innerHTML = formatMarkdownText(json.data.feedback);
            } else {
                reportOutput.textContent = 'Error: ' + json.message;
            }
        } catch (e) {
            reportOutput.textContent = 'Error grading assignment draft.';
        }
        if (window.lucide) lucide.createIcons();
    });
}

/* ==========================================================================
   6. PILLAR 4: METHOD STUDIO
   ========================================================================== */
function initMethodStudio() {
    const methodBtns = document.querySelectorAll('.method-pill-btn');
    const methodPanes = document.querySelectorAll('.method-layout-pane');

    methodBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            methodBtns.forEach(b => {
                b.classList.remove('active', 'bg-amber-500', 'text-white', 'shadow-md', 'shadow-amber-200');
                b.classList.add('bg-white', 'text-slate-700');
            });
            methodPanes.forEach(p => p.style.display = 'none');

            btn.classList.add('active', 'bg-amber-500', 'text-white', 'shadow-md', 'shadow-amber-200');
            btn.classList.remove('bg-white', 'text-slate-700');
            
            const targetPane = document.getElementById(btn.getAttribute('data-method'));
            if (targetPane) targetPane.style.display = 'block';
            if (window.lucide) lucide.createIcons();
        });
    });

    // 1. Pomodoro Flow
    const startPomoBtn = document.getElementById('startPomodoroBtn');
    const resetPomoBtn = document.getElementById('resetPomodoroBtn');
    const pomoDisplay = document.getElementById('pomodoroTimerDisplay');

    function updatePomoDisplay() {
        const m = Math.floor(pomodoroSeconds / 60);
        const s = pomodoroSeconds % 60;
        if (pomoDisplay) {
            pomoDisplay.textContent = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        }
    }

    if (startPomoBtn) {
        startPomoBtn.addEventListener('click', () => {
            if (pomodoroInterval) {
                clearInterval(pomodoroInterval);
                pomodoroInterval = null;
                startPomoBtn.textContent = 'Resume Sprint';
                return;
            }
            startPomoBtn.textContent = 'Pause Sprint';
            pomodoroInterval = setInterval(() => {
                if (pomodoroSeconds > 0) {
                    pomodoroSeconds--;
                    updatePomoDisplay();
                } else {
                    clearInterval(pomodoroInterval);
                    pomodoroInterval = null;
                    alert('Focus interval complete! Take a 5-minute break.');
                    pomodoroSeconds = 1500;
                    updatePomoDisplay();
                    startPomoBtn.textContent = 'Start Sprint';
                }
            }, 1000);
        });
    }

    if (resetPomoBtn) {
        resetPomoBtn.addEventListener('click', () => {
            if (pomodoroInterval) clearInterval(pomodoroInterval);
            pomodoroInterval = null;
            pomodoroSeconds = 1500;
            updatePomoDisplay();
            if (startPomoBtn) startPomoBtn.textContent = 'Start Sprint';
        });
    }

    // 2. Feynman Teach-Back
    document.getElementById('runFeynmanBtn')?.addEventListener('click', async () => {
        const topic = document.getElementById('feynmanTopicInput').value.trim();
        const explanation = document.getElementById('feynmanExplanationInput').value.trim();
        const output = document.getElementById('feynmanFeedbackOutput');

        if (!topic || !explanation) {
            alert('Please provide both topic and simple explanation.');
            return;
        }

        output.innerHTML = '<div class="text-amber-600 font-semibold animate-pulse py-2 text-xs">Evaluating concept mastery...</div>';
        try {
            const res = await fetch('/api/feynman', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ workspace_id: currentWorkspaceId, topic, explanation })
            });
            const json = await res.json();
            if (json.status === 'success') {
                output.innerHTML = `
                    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 space-y-2">
                        <div class="text-xs font-bold text-amber-800">Concept Mastery: ${json.data.mastery_score}%</div>
                        <div class="text-xs text-slate-700 leading-relaxed">${formatMarkdownText(json.data.feedback)}</div>
                    </div>
                `;
            }
        } catch (e) {
            output.textContent = 'Error evaluating Feynman explanation.';
        }
    });

    // 3. Blurting Audit
    const startBlurtingBtn = document.getElementById('startBlurtingBtn');
    const blurtingTimerText = document.getElementById('blurtingTimerText');
    if (startBlurtingBtn) {
        startBlurtingBtn.addEventListener('click', () => {
            if (blurtingInterval) clearInterval(blurtingInterval);
            blurtingSeconds = 300;
            startBlurtingBtn.textContent = '5-Min Timer Running...';
            blurtingInterval = setInterval(() => {
                if (blurtingSeconds > 0) {
                    blurtingSeconds--;
                    const m = Math.floor(blurtingSeconds / 60);
                    const s = blurtingSeconds % 60;
                    if (blurtingTimerText) blurtingTimerText.textContent = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                } else {
                    clearInterval(blurtingInterval);
                    blurtingInterval = null;
                    alert('Time is up! Click "Audit Against Vault Notes" to see your score.');
                }
            }, 1000);
        });
    }

    document.getElementById('submitBlurtingAuditBtn')?.addEventListener('click', async () => {
        const braindump = document.getElementById('blurtingCanvas').value.trim();
        const output = document.getElementById('blurtingAuditOutput');
        if (!braindump) {
            alert('Please type some notes in the canvas before auditing.');
            return;
        }

        output.innerHTML = '<div class="text-amber-600 font-semibold animate-pulse py-2 text-xs">Auditing memory dump against Vault notes...</div>';
        try {
            const res = await fetch('/api/studio/blurting', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ workspace_id: currentWorkspaceId, braindump })
            });
            const json = await res.json();
            if (json.status === 'success') {
                output.innerHTML = `
                    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 space-y-2">
                        <div class="text-xs font-bold text-amber-800">Memory Recall Score: ${json.data.score || 75}%</div>
                        <div class="text-xs text-slate-700 leading-relaxed">${formatMarkdownText(json.data.feedback)}</div>
                    </div>
                `;
            }
        } catch (e) {
            output.textContent = 'Error auditing braindump.';
        }
    });

    // 4. IRAC Legal Studio
    document.getElementById('runIRACBtn')?.addEventListener('click', async () => {
        const facts = document.getElementById('iracFactsInput').value.trim();
        const output = document.getElementById('iracOutputContainer');

        output.innerHTML = '<div class="text-amber-600 font-semibold animate-pulse py-3 text-xs text-center">Generating 4-stage IRAC analysis...</div>';
        try {
            const res = await fetch('/api/studio/irac', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ workspace_id: currentWorkspaceId, facts })
            });
            const json = await res.json();
            if (json.status === 'success') {
                const data = json.data;
                output.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="p-3.5 rounded-2xl bg-indigo-50/80 border border-indigo-100 space-y-1">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-700">1. Issue</span>
                            <p class="text-xs text-slate-800 leading-relaxed">${escapeHtml(data.issue || 'Legal question identified.')}</p>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-amber-50/80 border border-amber-100 space-y-1">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-amber-800">2. Governing Rule</span>
                            <p class="text-xs text-slate-800 leading-relaxed">${escapeHtml(data.rule || 'Statutory precedents.')}</p>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-blue-50/80 border border-blue-100 space-y-1 md:col-span-2">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-blue-800">3. Application to Facts</span>
                            <div class="text-xs text-slate-800 leading-relaxed space-y-1">${formatMarkdownText(data.application || '')}</div>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-emerald-50/80 border border-emerald-100 space-y-1 md:col-span-2">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-800">4. Legal Conclusion</span>
                            <p class="text-xs text-slate-800 leading-relaxed">${escapeHtml(data.conclusion || '')}</p>
                        </div>
                    </div>
                `;
            }
        } catch (e) {
            output.textContent = 'Error generating IRAC breakdown.';
        }
        if (window.lucide) lucide.createIcons();
    });

    // 5. Lecturer Decoder
    document.getElementById('runDecoderBtn')?.addEventListener('click', async () => {
        const output = document.getElementById('decoderOutputContainer');
        output.innerHTML = '<div class="text-purple-600 font-semibold animate-pulse py-8 text-xs text-center">Analyzing Vault notes & exam frequency archetypes...</div>';

        try {
            const res = await fetch(`/api/studio/decoder?workspace_id=${currentWorkspaceId}`);
            const json = await res.json();
            if (json.status === 'success' && json.data.length > 0) {
                output.innerHTML = json.data.map(item => `
                    <div class="p-4 rounded-2xl bg-white border border-slate-100 shadow-sm hover:border-purple-200 transition-all space-y-2">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold ${item.priority === 'CRITICAL' ? 'bg-rose-50 text-rose-700 border border-rose-200' : (item.priority === 'HIGH' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-blue-50 text-blue-700 border border-blue-200')}">${item.priority}</span>
                                <h4 class="text-xs font-bold text-slate-900">${escapeHtml(item.topic)}</h4>
                            </div>
                            <span class="text-xs font-black text-purple-700 font-mono">${item.yield_percentage} Yield</span>
                        </div>
                        <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-500 to-indigo-600 h-full rounded-full" style="width: ${item.yield_percentage}"></div>
                        </div>
                        <p class="text-[11px] text-slate-500 leading-relaxed">${escapeHtml(item.reason)}</p>
                    </div>
                `).join('');
            }
        } catch (e) {
            output.innerHTML = '<div class="text-xs text-rose-500 p-4">Could not decode syllabus notes.</div>';
        }
        if (window.lucide) lucide.createIcons();
    });

    // 6. Socratic Sparring
    document.getElementById('runSocraticBtn')?.addEventListener('click', async () => {
        const topic = document.getElementById('socraticTopicInput').value.trim();
        const argument = document.getElementById('socraticArgumentInput').value.trim();
        const output = document.getElementById('socraticOutput');

        if (!argument) {
            alert('Please state your academic argument.');
            return;
        }

        output.innerHTML = '<div class="text-amber-600 font-semibold animate-pulse py-2 text-xs">Professor is evaluating your claim...</div>';
        try {
            const res = await fetch('/api/studio/socratic', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ workspace_id: currentWorkspaceId, topic, argument })
            });
            const json = await res.json();
            if (json.status === 'success') {
                output.innerHTML = `
                    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80 space-y-2">
                        <div class="text-xs text-slate-700 leading-relaxed">${formatMarkdownText(json.data.feedback)}</div>
                    </div>
                `;
            }
        } catch (e) {
            output.textContent = 'Error running Socratic defense.';
        }
    });

    // 7. SQ3R Deep Reader
    let selectedSQ3RStep = 'Survey';
    document.querySelectorAll('.sq3r-step-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.sq3r-step-btn').forEach(b => {
                b.classList.remove('active', 'bg-amber-500', 'text-white', 'font-bold');
                b.classList.add('bg-slate-100', 'text-slate-700', 'font-semibold');
            });
            btn.classList.add('active', 'bg-amber-500', 'text-white', 'font-bold');
            btn.classList.remove('bg-slate-100', 'text-slate-700');
            selectedSQ3RStep = btn.getAttribute('data-step') || 'Survey';
        });
    });

    document.getElementById('runSQ3RGuideBtn')?.addEventListener('click', async () => {
        const output = document.getElementById('sq3rOutput');
        output.innerHTML = `<div class="text-amber-600 font-semibold animate-pulse py-2 text-xs">Generating ${selectedSQ3RStep} strategy...</div>`;

        try {
            const res = await fetch('/api/studio/sq3r', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ workspace_id: currentWorkspaceId, step: selectedSQ3RStep })
            });
            const json = await res.json();
            if (json.status === 'success') {
                output.innerHTML = `
                    <div class="p-4 rounded-2xl bg-amber-50/70 border border-amber-200/80">
                        <div class="text-xs text-slate-700 leading-relaxed">${formatMarkdownText(json.data.guidance)}</div>
                    </div>
                `;
            }
        } catch (e) {
            output.textContent = 'Error generating SQ3R guidance.';
        }
    });
}

/* ==========================================================================
   7. PILLAR 5: EXAM ARENA
   ========================================================================== */
let currentArenaExam = [];
let arenaStartTime = null;

function initExamArena() {
    const generateBtn = document.getElementById('generateArenaExamBtn');
    const closeResultsBtn = document.getElementById('closeArenaResultsModalBtn');
    const finishReviewBtn = document.getElementById('finishArenaReviewBtn');
    const resultsModal = document.getElementById('arenaResultsModal');

    if (generateBtn) {
        generateBtn.addEventListener('click', async () => {
            const container = document.getElementById('arenaQuestionsContainer');
            container.innerHTML = '<div class="text-xs text-emerald-600 font-semibold text-center py-10 animate-pulse">Generating full timed mock examination paper...</div>';

            startArenaTimer();
            arenaStartTime = Date.now();

            try {
                const res = await fetch('/api/arena/mock', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ workspace_id: currentWorkspaceId, subject: selectedSubject })
                });
                const json = await res.json();
                if (json.status === 'success' && json.data.length > 0) {
                    currentArenaExam = json.data;
                    container.innerHTML = `
                        ${json.data.map((q, idx) => `
                            <div class="p-6 rounded-3xl bg-white border border-slate-100 shadow-xl shadow-indigo-100/30 space-y-3">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-xs font-bold text-emerald-700">Question ${idx + 1} (${q.type === 'mcq' ? 'Multiple Choice' : 'Structured Derivation'})</h4>
                                    <span class="text-[10px] font-bold bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full">${q.marks || 5} Marks</span>
                                </div>
                                <p class="text-xs text-slate-800 leading-relaxed font-medium">${escapeHtml(q.question)}</p>
                                ${q.options && q.options.length > 0 ? `
                                    <div class="space-y-1.5 pt-1">
                                        ${q.options.map(opt => `
                                            <label class="flex items-center gap-2.5 text-xs text-slate-700 bg-slate-50 p-2.5 rounded-xl border border-slate-200/80 cursor-pointer hover:border-emerald-300 transition-all">
                                                <input type="radio" name="arena_q_${idx}" value="${escapeHtml(opt)}" class="text-emerald-600 focus:ring-emerald-500">
                                                <span>${escapeHtml(opt)}</span>
                                            </label>
                                        `).join('')}
                                    </div>
                                ` : `
                                    <textarea id="arena_text_${idx}" class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-3 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 min-h-[90px]" placeholder="Write your derivation, calculations, and final answer here..."></textarea>
                                `}
                            </div>
                        `).join('')}
                        <button class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold text-xs py-3.5 rounded-2xl shadow-lg shadow-emerald-200 transition-all hover:scale-[1.01]" onclick="submitArenaExam()">Submit Mock Paper for Grading</button>
                    `;
                }
            } catch (e) {
                container.innerHTML = '<div class="text-xs text-rose-500 p-4">Could not generate mock paper.</div>';
            }
            if (window.lucide) lucide.createIcons();
        });
    }

    if (closeResultsBtn && resultsModal) {
        closeResultsBtn.addEventListener('click', () => resultsModal.style.display = 'none');
    }
    if (finishReviewBtn && resultsModal) {
        finishReviewBtn.addEventListener('click', () => {
            resultsModal.style.display = 'none';
            switchView('view-vault');
        });
    }
}

function startArenaTimer() {
    if (arenaInterval) clearInterval(arenaInterval);
    arenaSeconds = 900; // 15 mins
    const clock = document.getElementById('arenaClockDisplay');

    arenaInterval = setInterval(() => {
        if (arenaSeconds > 0) {
            arenaSeconds--;
            const m = Math.floor(arenaSeconds / 60);
            const s = arenaSeconds % 60;
            if (clock) clock.textContent = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        } else {
            clearInterval(arenaInterval);
            arenaInterval = null;
            alert('Exam time has elapsed! Auto-submitting paper.');
            submitArenaExam();
        }
    }, 1000);
}

window.submitArenaExam = function() {
    if (arenaInterval) clearInterval(arenaInterval);
    arenaInterval = null;

    if (!currentArenaExam || currentArenaExam.length === 0) {
        alert('No active mock exam to submit.');
        return;
    }

    let totalMarks = 0;
    let earnedMarks = 0;
    const breakdownItems = [];

    currentArenaExam.forEach((q, idx) => {
        const qMarks = q.marks || 5;
        totalMarks += qMarks;

        let userAns = '';
        let isCorrect = false;
        let awarded = 0;

        if (q.type === 'mcq') {
            const selected = document.querySelector(`input[name="arena_q_${idx}"]:checked`);
            userAns = selected ? selected.value : 'No option selected';
            isCorrect = (userAns.toLowerCase().trim() === (q.correct_answer || '').toLowerCase().trim());
            awarded = isCorrect ? qMarks : 0;
        } else {
            const textEl = document.getElementById(`arena_text_${idx}`);
            userAns = textEl ? textEl.value.trim() : '';
            if (userAns.length > 25) {
                awarded = Math.round(qMarks * 0.9);
                isCorrect = true;
            } else if (userAns.length > 10) {
                awarded = Math.round(qMarks * 0.6);
            } else {
                awarded = 0;
            }
        }
        earnedMarks += awarded;

        breakdownItems.push({
            qNum: idx + 1,
            question: q.question,
            userAns,
            correctAnswer: q.correct_answer,
            guide: q.marking_guide || 'Award marks for structured reasoning.',
            awarded,
            qMarks
        });
    });

    const pct = Math.round((earnedMarks / Math.max(totalMarks, 1)) * 100);
    let grade = 'Grade A';
    if (pct < 50) grade = 'Grade D';
    else if (pct < 65) grade = 'Grade C';
    else if (pct < 80) grade = 'Grade B';

    // Populate Modal
    const resultsModal = document.getElementById('arenaResultsModal');
    const gradeText = document.getElementById('arenaFinalGradeText');
    const earnedMarksEl = document.getElementById('arenaEarnedMarks');
    const totalMarksEl = document.getElementById('arenaTotalMarks');
    const breakdownEl = document.getElementById('arenaDetailedBreakdown');
    const timeSpentEl = document.getElementById('arenaTimeSpentText');

    if (gradeText) gradeText.textContent = `${grade} (${pct}%)`;
    if (earnedMarksEl) earnedMarksEl.textContent = earnedMarks;
    if (totalMarksEl) totalMarksEl.textContent = `/ ${totalMarks} Marks`;

    if (timeSpentEl && arenaStartTime) {
        const diffSecs = Math.round((Date.now() - arenaStartTime) / 1000);
        const mins = Math.floor(diffSecs / 60);
        const secs = diffSecs % 60;
        timeSpentEl.textContent = `Completed in ${mins} min${mins !== 1 ? 's' : ''} ${secs} sec${secs !== 1 ? 's' : ''}`;
    }

    if (breakdownEl) {
        breakdownEl.innerHTML = breakdownItems.map(item => `
            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-1.5">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-900">Question ${item.qNum}</span>
                    <span class="font-bold text-[11px] ${item.awarded >= item.qMarks * 0.7 ? 'text-emerald-700' : 'text-amber-700'}">${item.awarded} / ${item.qMarks} Marks</span>
                </div>
                <p class="text-slate-600 text-[11px]">${escapeHtml(item.question)}</p>
                <div class="text-[11px] pt-1 space-y-0.5">
                    <div><b>Your Response:</b> <span class="text-slate-800">${escapeHtml(item.userAns || 'None')}</span></div>
                    <div class="text-emerald-700"><b>Marking Guide:</b> ${escapeHtml(item.guide)}</div>
                </div>
            </div>
        `).join('');
    }

    if (resultsModal) resultsModal.style.display = 'flex';
    if (window.lucide) lucide.createIcons();
};

/* ==========================================================================
   8. SUPPORT MODAL & ECOCASH
   ========================================================================== */
function initSupportModal() {
    const openBtn = document.getElementById('openSupportBtn');
    const closeBtn = document.getElementById('closeSupportModalBtn');
    const modal = document.getElementById('supportModal');
    const payBtn = document.getElementById('submitEcocashPayBtn');

    if (openBtn && modal) {
        openBtn.addEventListener('click', () => modal.style.display = 'flex');
    }
    if (closeBtn && modal) {
        closeBtn.addEventListener('click', () => modal.style.display = 'none');
    }
    if (payBtn && modal) {
        payBtn.addEventListener('click', async () => {
            const phone = document.getElementById('ecocashPhoneNumber').value.trim();
            const option = document.getElementById('supporterOptionSelect').value;
            if (!phone) {
                alert('Please enter your EcoCash phone number.');
                return;
            }
            payBtn.textContent = 'Initiating EcoCash Prompt...';
            try {
                const res = await fetch('/api/payments/initiate', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ phone, pass_type: option })
                });
                const json = await res.json();
                alert('EcoCash prompt initiated! Check your phone to approve.');
                modal.style.display = 'none';
            } catch (e) {
                alert('Could not initiate payment.');
            } finally {
                payBtn.textContent = 'Pay via EcoCash';
            }
        });
    }
}

/* ==========================================================================
   HELPER UTILITIES
   ========================================================================== */
function escapeHtml(str) {
    if (!str) return '';
    return String(str).replace(/[&<>"']/g, m => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
    })[m]);
}

function formatMarkdownText(text) {
    if (!text) return '';
    let out = escapeHtml(text);

    // Code blocks
    out = out.replace(/```([\s\S]*?)```/g, '<div class="p-3 bg-slate-900 text-emerald-300 font-mono text-[11px] rounded-xl my-2 overflow-x-auto whitespace-pre">$1</div>');

    // Headers
    out = out.replace(/### (.*?)(<br>|$|\n)/g, '<h4 class="text-xs font-bold text-indigo-700 mt-2 mb-1">$1</h4>');
    out = out.replace(/## (.*?)(<br>|$|\n)/g, '<h3 class="text-sm font-extrabold text-slate-900 mt-3 mb-1">$1</h3>');

    // Bold and Italic
    out = out.replace(/\*\*(.*?)\*\*/g, '<strong class="text-slate-900 font-bold">$1</strong>');
    out = out.replace(/\*(.*?)\*/g, '<em class="text-slate-700">$1</em>');

    // LaTeX equation markers
    out = out.replace(/\\\[([\s\S]*?)\\\]/g, '<div class="p-2 bg-indigo-50/50 border border-indigo-100 rounded-xl my-1.5 font-mono text-xs text-indigo-900 text-center font-bold">$1</div>');
    out = out.replace(/\\\((.*?)\\\)/g, '<span class="font-mono text-indigo-800 font-semibold px-1 py-0.5 bg-indigo-50 rounded">$1</span>');

    // Linebreaks
    out = out.replace(/\n\n/g, '<br><br>');
    out = out.replace(/\n/g, '<br>');

    return out;
}

