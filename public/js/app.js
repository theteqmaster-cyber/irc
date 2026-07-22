/**
 * IRC Zim AI v2.0 - Beat Studley AI & NotebookLM Engine
 */

let currentWorkspaceId = '';
let currentFlashcards = [];
let currentFlashcardIndex = 0;
let userMasteryScore = 85;

document.addEventListener('DOMContentLoaded', () => {
    initWorkspaces();
    initTabs();
    initChat();
    initFlashcards();
    initFeynman();
    initQuizzer();
    initRubricGrader();
    initPayments();
    initSprintTimer();
    initIRACStudio();
    initBlurtingAudit();
    initLecturerDecoder();
    initEnergySelector();
});

// 10. StudyBee Studio Rooms & Sprint Timer Handlers
let sprintSeconds = 900; // 15 mins
let sprintInterval = null;

function initSprintTimer() {
    const startBtn = document.getElementById('startSprintBtn');
    const resetBtn = document.getElementById('resetSprintBtn');
    const clockEl = document.getElementById('sprintClock');

    function updateClockDisplay() {
        const m = Math.floor(sprintSeconds / 60);
        const s = sprintSeconds % 60;
        if (clockEl) {
            clockEl.textContent = `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        }
    }

    if (startBtn) {
        startBtn.addEventListener('click', () => {
            if (sprintInterval) {
                clearInterval(sprintInterval);
                sprintInterval = null;
                startBtn.textContent = '▶ Resume Sprint';
                return;
            }

            startBtn.textContent = '⏸ Pause Sprint';
            sprintInterval = setInterval(() => {
                if (sprintSeconds > 0) {
                    sprintSeconds--;
                    updateClockDisplay();
                } else {
                    clearInterval(sprintInterval);
                    sprintInterval = null;
                    alert('🎉 15-Minute Co-Execution Sprint Complete! +15 XP Earned!');
                    updateMastery(15);
                    startBtn.textContent = '▶ Start 15-Min Sprint';
                    sprintSeconds = 900;
                    updateClockDisplay();
                }
            }, 1000);
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            if (sprintInterval) clearInterval(sprintInterval);
            sprintInterval = null;
            sprintSeconds = 900;
            updateClockDisplay();
            if (startBtn) startBtn.textContent = '▶ Start 15-Min Sprint';
        });
    }
}

function initIRACStudio() {
    const btn = document.getElementById('evalIracBtn');
    const output = document.getElementById('iracOutput');

    if (btn) {
        btn.addEventListener('click', async () => {
            const facts = document.getElementById('iracFactsText').value.trim();
            output.innerHTML = '<div style="color:var(--primary);">StudyBee IRAC AI is analyzing legal case facts...</div>';

            try {
                const res = await fetch('/api/studio/irac', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ workspace_id: currentWorkspaceId, facts })
                });
                const json = await res.json();
                if (json.status === 'success') {
                    output.innerHTML = `
                        <div style="background:rgba(255,183,3,0.1); border:1px solid var(--primary); padding:16px; border-radius:12px; line-height:1.6;">
                            <div style="color:#f0f4f8;">${json.data.raw_text.replace(/\n/g, '<br>')}</div>
                        </div>
                    `;
                }
            } catch (e) {
                output.textContent = 'Error executing IRAC analysis.';
            }
        });
    }
}

function initBlurtingAudit() {
    const btn = document.getElementById('evalBlurtingBtn');
    const output = document.getElementById('blurtingOutput');

    if (btn) {
        btn.addEventListener('click', async () => {
            const topic = document.getElementById('blurtingTopic').value.trim();
            const braindump = document.getElementById('blurtingBraindump').value.trim();

            if (!braindump) {
                alert('Please enter your 5-minute memory braindump.');
                return;
            }

            output.innerHTML = '<div style="color:var(--accent-green);">StudyBee Memory Engine is auditing your braindump...</div>';

            try {
                const res = await fetch('/api/studio/blurting', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ workspace_id: currentWorkspaceId, topic, braindump })
                });
                const json = await res.json();
                if (json.status === 'success') {
                    updateMastery(json.data.score >= 75 ? 5 : -2);
                    output.innerHTML = `
                        <div style="background:rgba(0,230,118,0.1); border:1px solid var(--accent-green); padding:16px; border-radius:12px; line-height:1.6;">
                            ${json.data.feedback.replace(/\n/g, '<br>')}
                        </div>
                    `;
                }
            } catch (e) {
                output.textContent = 'Error conducting memory audit.';
            }
        });
    }
}

function initLecturerDecoder() {
    const btn = document.getElementById('loadDecoderBtn');
    const container = document.getElementById('decoderContainer');

    if (btn) {
        btn.addEventListener('click', async () => {
            container.innerHTML = '<div style="color:var(--primary-orange); text-align:center; padding:20px;">Decoding lecturer exam weightings & past papers...</div>';

            try {
                const res = await fetch(`/api/studio/decoder?workspace_id=${currentWorkspaceId}`);
                const json = await res.json();
                container.innerHTML = '';

                if (json.data && json.data.length > 0) {
                    json.data.forEach(item => {
                        const card = document.createElement('div');
                        card.className = 'decoder-card';
                        card.innerHTML = `
                            <div>
                                <span style="font-size:0.75rem; color:var(--text-muted); font-weight:700;">PRIORITY: ${item.priority}</span>
                                <h4 style="color:#fff; margin:4px 0;">${item.topic}</h4>
                                <p style="font-size:0.85rem; color:#cbd5e1;">${item.reason}</p>
                            </div>
                            <span class="yield-badge">${item.yield_percentage} Yield</span>
                        `;
                        container.appendChild(card);
                    });
                }
            } catch (e) {
                container.textContent = 'Error decoding exam patterns.';
            }
        });
    }
}

function initEnergySelector() {
    const btns = document.querySelectorAll('.energy-btn');
    btns.forEach(btn => {
        btn.addEventListener('click', () => {
            btns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const level = btn.dataset.energy;
            console.log('Energy mode selected:', level);
        });
    });
}


// 1. Workspaces
async function initWorkspaces() {
    try {
        const res = await fetch('/api/workspaces');
        const json = await res.json();
        const select = document.getElementById('workspaceSelect');
        select.innerHTML = '';
        
        if (json.data && json.data.length > 0) {
            json.data.forEach(ws => {
                const opt = document.createElement('option');
                opt.value = ws.id;
                opt.textContent = `${ws.name} (${ws.category || 'ZIMSEC'})`;
                select.appendChild(opt);
            });
            currentWorkspaceId = json.data[0].id;
            loadWorkspaceDocuments(currentWorkspaceId);
        }
        
        select.addEventListener('change', (e) => {
            currentWorkspaceId = e.target.value;
            loadWorkspaceDocuments(currentWorkspaceId);
        });
    } catch (err) {
        console.error('Workspaces load error:', err);
    }
}

async function loadWorkspaceDocuments(wsId) {
    const viewer = document.getElementById('documentViewer');
    viewer.innerHTML = '<div style="color:#94a3b8; text-align:center; padding:40px;">Loading course materials...</div>';

    try {
        const res = await fetch(`/api/documents?workspace_id=${wsId}`);
        const json = await res.json();
        
        viewer.innerHTML = '';
        if (!json.data || json.data.length === 0) {
            viewer.innerHTML = `
                <div class="source-card" id="source-page-1">
                    <span class="page-tag">PAGE 1 • DEFAULT ZIMSEC COURSE NOTE</span>
                    <h3>ZIMSEC A-Level Physics: Newton's Laws & Dynamics</h3>
                    <p style="margin-top:10px; color:#cbd5e1;">
                        1. <b>First Law:</b> An object remains at rest or in uniform motion unless acted upon by a net external force.<br>
                        2. <b>Second Law (F=ma):</b> The rate of change of momentum is directly proportional to the applied force.<br>
                        3. <b>Third Law:</b> Action and reaction forces are equal in magnitude and opposite in direction.
                    </p>
                </div>
            `;
            return;
        }

        json.data.forEach((doc, idx) => {
            const card = document.createElement('div');
            card.className = 'source-card';
            card.id = `source-page-${idx + 1}`;
            card.innerHTML = `
                <span class="page-tag">PAGE ${idx + 1} • ${doc.file_name}</span>
                <h4>${doc.file_name}</h4>
                <p style="margin-top:8px; font-size:0.9rem; color:#94a3b8;">
                    Indexed in Firebase & Groq Llama 3.3 store. Ready for vector Q&A, active recall flashcards, practice exams, and Feynman evaluation.
                </p>
            `;
            viewer.appendChild(card);
        });
    } catch (err) {
        console.error('Doc load error:', err);
    }
}

// 2. Navigation Tabs & Mobile View Switcher
function initTabs() {
    const desktopTabs = document.querySelectorAll('.tab-btn');
    const mobileNavBtns = document.querySelectorAll('.mobile-nav-btn');
    const paneLeft = document.querySelector('.pane-left');
    const paneRight = document.querySelector('.pane-right');

    function switchTab(targetTabId) {
        // Handle Mobile View Switching (Vault pane vs Study tools pane)
        if (targetTabId === 'tab-vault') {
            if (paneLeft && paneRight) {
                paneLeft.classList.add('mobile-active');
                paneRight.classList.remove('mobile-active');
            }
        } else {
            if (paneLeft && paneRight) {
                paneLeft.classList.remove('mobile-active');
                paneRight.classList.add('mobile-active');
            }

            // Activate content panel inside pane-right
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            const contentEl = document.getElementById(targetTabId);
            if (contentEl) contentEl.classList.add('active');

            if (targetTabId === 'tab-flashcards') {
                loadFlashcards();
            }
        }

        // Synchronize active state across Desktop tabs
        desktopTabs.forEach(t => {
            if (t.dataset.tab === targetTabId) {
                t.classList.add('active');
            } else {
                t.classList.remove('active');
            }
        });

        // Synchronize active state across Mobile bottom nav dock
        mobileNavBtns.forEach(m => {
            if (m.dataset.tab === targetTabId) {
                m.classList.add('active');
            } else {
                m.classList.remove('active');
            }
        });
    }

    // Default mobile active pane initialization
    if (paneRight) paneRight.classList.add('mobile-active');

    desktopTabs.forEach(tab => {
        tab.addEventListener('click', () => switchTab(tab.dataset.tab));
    });

    mobileNavBtns.forEach(btn => {
        btn.addEventListener('click', () => switchTab(btn.dataset.tab));
    });
}

// 3. RAG AI Chat
function initChat() {
    const sendBtn = document.getElementById('sendChatBtn');
    const input = document.getElementById('chatInput');
    const messages = document.getElementById('chatMessages');

    const handleSend = async () => {
        const text = input.value.trim();
        if (!text) return;

        const userMsg = document.createElement('div');
        userMsg.className = 'message-bubble user';
        userMsg.textContent = text;
        messages.appendChild(userMsg);
        input.value = '';
        messages.scrollTop = messages.scrollHeight;

        const loading = document.createElement('div');
        loading.className = 'message-bubble ai';
        loading.innerHTML = '<span style="color:#00e5ff;">Groq Llama 3.3 70B is analyzing course notes...</span>';
        messages.appendChild(loading);
        messages.scrollTop = messages.scrollHeight;

        try {
            const res = await fetch('/api/chat', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ workspace_id: currentWorkspaceId, question: text })
            });
            const json = await res.json();
            messages.removeChild(loading);

            if (json.status === 'success') {
                const aiMsg = document.createElement('div');
                aiMsg.className = 'message-bubble ai';
                
                let citationsHtml = '';
                if (json.data.citations && json.data.citations.length > 0) {
                    json.data.citations.forEach(c => {
                        citationsHtml += `
                            <span class="citation-badge" onclick="highlightSource(${c.page_number})">
                                📄 Page ${c.page_number} Citation
                            </span>
                        `;
                    });
                }

                aiMsg.innerHTML = `<div>${json.data.answer.replace(/\n/g, '<br>')}</div>${citationsHtml}`;
                messages.appendChild(aiMsg);
            } else {
                const err = document.createElement('div');
                err.className = 'message-bubble ai';
                err.textContent = 'Unable to answer: ' + (json.message || 'Error');
                messages.appendChild(err);
            }
        } catch (e) {
            messages.removeChild(loading);
            const err = document.createElement('div');
            err.className = 'message-bubble ai';
            err.textContent = 'Network error fetching response.';
            messages.appendChild(err);
        }

        messages.scrollTop = messages.scrollHeight;
    };

    sendBtn.addEventListener('click', handleSend);
    input.addEventListener('keypress', (e) => { if (e.key === 'Enter') handleSend(); });
}

window.highlightSource = function(pageNumber) {
    const el = document.getElementById(`source-page-${pageNumber}`) || document.querySelector('.source-card');
    if (el) {
        document.querySelectorAll('.source-card').forEach(c => c.classList.remove('highlighted'));
        el.classList.add('highlighted');
        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
};

// 4. Mastery Tracker Update
window.updateMastery = function(delta) {
    userMasteryScore = Math.min(100, Math.max(0, userMasteryScore + delta));
    const bar = document.getElementById('masteryProgressBar');
    const text = document.getElementById('masteryScoreText');
    if (bar && text) {
        bar.style.width = userMasteryScore + '%';
        text.textContent = userMasteryScore + '%';
    }
};

// 5. Active Recall Flashcards Player
function initFlashcards() {
    const card = document.getElementById('flashcardContainer');
    card.addEventListener('click', () => {
        card.classList.toggle('flipped');
    });

    document.getElementById('nextCardBtn').addEventListener('click', () => {
        currentFlashcardIndex = (currentFlashcardIndex + 1) % Math.max(currentFlashcards.length, 1);
        renderCurrentCard();
    });
}

async function loadFlashcards() {
    try {
        const res = await fetch(`/api/flashcards?workspace_id=${currentWorkspaceId}`);
        const json = await res.json();
        if (json.data && json.data.length > 0) {
            currentFlashcards = json.data;
            currentFlashcardIndex = 0;
            renderCurrentCard();
        }
    } catch (e) {
        console.error('Flashcard error:', e);
    }
}

function renderCurrentCard() {
    if (currentFlashcards.length === 0) return;
    const card = currentFlashcards[currentFlashcardIndex];
    document.getElementById('cardQuestion').textContent = card.question;
    document.getElementById('cardAnswer').textContent = card.answer;
    document.getElementById('flashcardContainer').classList.remove('flipped');
}

// 6. Feynman Technique Mode
function initFeynman() {
    const evalBtn = document.getElementById('evalFeynmanBtn');
    evalBtn.addEventListener('click', async () => {
        const topic = document.getElementById('feynmanTopic').value.trim();
        const explanation = document.getElementById('feynmanExplanation').value.trim();
        const output = document.getElementById('feynmanFeedback');

        if (!topic || !explanation) {
            alert('Please enter a topic and your explanation.');
            return;
        }

        output.innerHTML = '<div style="color:#00e5ff;">Groq Feynman Tutor is analyzing your explanation...</div>';

        try {
            const res = await fetch('/api/feynman', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ workspace_id: currentWorkspaceId, topic, explanation })
            });
            const json = await res.json();
            if (json.status === 'success') {
                updateMastery(json.data.mastery_score >= 80 ? 5 : -2);
                output.innerHTML = `
                    <div style="background: rgba(0, 230, 118, 0.1); border:1px solid #00e676; padding:16px; border-radius:12px;">
                        <h4 style="color:#00e676; margin-bottom:8px;">Concept Mastery: ${json.data.mastery_score}%</h4>
                        <div style="line-height:1.6; color:#f0f4f8;">${json.data.feedback.replace(/\n/g, '<br>')}</div>
                    </div>
                `;
            }
        } catch (e) {
            output.textContent = 'Error evaluating explanation.';
        }
    });
}

// 7. Practice Exam Quizzer
function initQuizzer() {
    const loadQuizBtn = document.getElementById('loadQuizBtn');
    const container = document.getElementById('quizContainer');

    loadQuizBtn.addEventListener('click', async () => {
        container.innerHTML = '<div style="color:#00e5ff; text-align:center; padding:20px;">Groq Llama 3.3 is generating exam practice questions...</div>';

        try {
            const res = await fetch(`/api/quiz?workspace_id=${currentWorkspaceId}`);
            const json = await res.json();
            container.innerHTML = '';

            if (json.data && json.data.length > 0) {
                json.data.forEach((q, idx) => {
                    const card = document.createElement('div');
                    card.style.cssText = 'background:var(--bg-card); border:1px solid var(--border-glass); padding:16px; border-radius:12px;';
                    
                    let optionsHtml = '';
                    if (q.type === 'mcq' && q.options) {
                        q.options.forEach((opt, oIdx) => {
                            optionsHtml += `
                                <button onclick="checkAnswer(${idx}, ${oIdx}, ${q.correct_index})" class="tab-btn" style="display:block; width:100%; text-align:left; border:1px solid var(--border-glass); margin-top:8px; padding:10px; border-radius:8px;">
                                    ${String.fromCharCode(65 + oIdx)}. ${opt}
                                </button>
                            `;
                        });
                    } else {
                        optionsHtml = `
                            <input type="text" id="fillIn_${idx}" class="chat-input" style="width:100%; margin-top:8px;" placeholder="Type your answer...">
                            <button onclick="checkFillIn(${idx}, '${q.correct_answer}')" class="send-btn" style="width:100%; margin-top:8px;">Check Answer</button>
                        `;
                    }

                    card.innerHTML = `
                        <span style="font-weight:700; color:#00e5ff; font-size:0.8rem;">QUESTION ${idx + 1}</span>
                        <h4 style="margin:6px 0 10px 0; font-size:0.95rem;">${q.question}</h4>
                        ${optionsHtml}
                        <div id="quizResult_${idx}" style="margin-top:10px; font-weight:600;"></div>
                    `;
                    container.appendChild(card);
                });
            }
        } catch (e) {
            container.textContent = 'Failed to generate quiz questions.';
        }
    });
}

window.checkAnswer = function(qIdx, selectedIdx, correctIdx) {
    const resEl = document.getElementById(`quizResult_${qIdx}`);
    if (selectedIdx === correctIdx) {
        updateMastery(10);
        resEl.innerHTML = '<span style="color:#00e676;">✅ Correct! +10% Mastery</span>';
    } else {
        updateMastery(-2);
        resEl.innerHTML = '<span style="color:#ef5350;">❌ Incorrect. Review your notes for this concept.</span>';
    }
};

window.checkFillIn = function(qIdx, correctAnswer) {
    const input = document.getElementById(`fillIn_${qIdx}`).value.trim().toLowerCase();
    const resEl = document.getElementById(`quizResult_${qIdx}`);
    if (input.includes(correctAnswer.toLowerCase()) || correctAnswer.toLowerCase().includes(input)) {
        updateMastery(10);
        resEl.innerHTML = `<span style="color:#00e676;">✅ Correct! Model answer: ${correctAnswer}</span>`;
    } else {
        resEl.innerHTML = `<span style="color:#ef5350;">❌ Model Answer: ${correctAnswer}</span>`;
    }
};

// 8. Assignment Rubric Grader
function initRubricGrader() {
    const gradeBtn = document.getElementById('gradeRubricBtn');
    const output = document.getElementById('rubricOutput');

    gradeBtn.addEventListener('click', async () => {
        const rubric = document.getElementById('rubricCriteria').value.trim();
        const essay = document.getElementById('essayDraftText').value.trim();

        if (!essay) {
            alert('Please paste your essay draft.');
            return;
        }

        output.innerHTML = '<div style="color:#00e5ff;">Groq Academic Grader is evaluating your assignment against the rubric...</div>';

        try {
            const res = await fetch('/api/rubric', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ essay, rubric })
            });
            const json = await res.json();
            if (json.status === 'success') {
                output.innerHTML = `
                    <div style="background: rgba(124,77,255,0.15); border:1px solid var(--accent-purple); padding:16px; border-radius:12px;">
                        <h4 style="color:var(--accent-purple); margin-bottom:8px;">Examiner Score: ${json.data.score}/100</h4>
                        <div style="line-height:1.6; color:#f0f4f8;">${json.data.feedback.replace(/\n/g, '<br>')}</div>
                    </div>
                `;
            }
        } catch (e) {
            output.textContent = 'Error grading assignment.';
        }
    });
}

// 9. EcoCash Payment Modal
function initPayments() {
    const passBtn = document.getElementById('passBadgeBtn');
    const modal = document.getElementById('paymentModal');
    const closeBtn = document.getElementById('closeModalBtn');
    const payNowBtn = document.getElementById('payNowBtn');

    const comingSoonModal = document.getElementById('comingSoonModal');
    const closeComingSoonBtn = document.getElementById('closeComingSoonBtn');

    passBtn.addEventListener('click', () => modal.classList.add('active'));
    closeBtn.addEventListener('click', () => modal.classList.remove('active'));
    closeComingSoonBtn.addEventListener('click', () => comingSoonModal.classList.remove('active'));

    payNowBtn.addEventListener('click', async () => {
        const phone = document.getElementById('ecocashPhone').value.trim();
        const passType = document.getElementById('passTypeSelect').value;

        modal.classList.remove('active');
        comingSoonModal.classList.add('active');

        try {
            await fetch('/api/payments/initiate', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ phone, pass_type: passType })
            });
        } catch (e) {
            console.log('Payment API log:', e);
        }
    });
}
