<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudyBee 🐝 v2.0 - Live AI Exam Copilot & Interactive Studio Rooms</title>
    <meta name="description" content="StudyBee v2.0: The live AI exam copilot and interactive studio workspace for varsity & college students. Features IRAC Legal Studio, Socratic Feynman Tutor, Blurting Memory Audit, Lecturer Decoder, and Co-Execution Sprints.">
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <div id="app-container">
        <!-- Top Navbar -->
        <header class="navbar">
            <div class="logo-group">
                <div class="logo-badge">StudyBee 🐝 2.0</div>
                <div class="brand-text">
                    <span class="brand-title">StudyBee</span>
                    <span class="brand-subtitle">⚡ Live Studio Engine</span>
                </div>
            </div>
            
            <div class="nav-actions">
                <div class="energy-selector">
                    <span style="font-size:0.75rem; color:var(--text-muted); font-weight:600;">ENERGY:</span>
                    <button class="energy-btn active" data-energy="high">🔋 High</button>
                    <button class="energy-btn" data-energy="fog">🪫 Fog</button>
                    <button class="energy-btn" data-energy="commute">🚌 Commute</button>
                </div>

                <select id="workspaceSelect" class="workspace-select">
                    <!-- Populated dynamically -->
                </select>

                <button id="passBadgeBtn" class="pass-badge-btn">
                    <span id="userTierStatus">🎓 Trial</span>
                    <span class="ecocash-badge">Support StudyBee</span>
                </button>
            </div>
        </header>

        <!-- Live Co-Execution Sprint & Mastery Bar -->
        <section class="mastery-tracker-bar">
            <div class="mastery-info-group">
                <span class="mastery-label">🐝 EXAM READINESS:</span>
                <div class="mastery-progress-track">
                    <div id="masteryProgressBar"></div>
                </div>
                <span id="masteryScoreText" class="mastery-score-text">88%</span>
            </div>

            <div class="mastery-badges-group">
                <span class="badge-tag unfamiliar">🔴 Unfamiliar (2)</span>
                <span class="badge-tag learning">🟡 Learning (4)</span>
                <span class="badge-tag mastered">🟢 Mastered (22)</span>
            </div>
        </section>

        <!-- Main Workspace Dual Pane -->
        <main class="workspace-view">
            <!-- Left Pane: Document Vault & Source Viewer -->
            <section class="pane-left">
                <div class="pane-header">
                    <span class="pane-title">📚 Notes & Past Papers Vault</span>
                    <button class="upload-btn" onclick="document.getElementById('fileUploadInput').click()">+ Upload Notes/Papers</button>
                    <input type="file" id="fileUploadInput" style="display:none;" accept=".pdf,.txt,.docx" onchange="uploadFile(event)">
                </div>

                <div id="documentViewer" class="document-viewer">
                    <!-- Loaded dynamically -->
                </div>
            </section>

            <!-- Right Pane: StudyBee Interactive Studio Rooms -->
            <section class="pane-right">
                <!-- Studio Navigation Tabs (Desktop) -->
                <div class="study-tabs">
                    <button class="tab-btn active" data-tab="tab-sprint">⚡ Co-Exec Sprint</button>
                    <button class="tab-btn" data-tab="tab-irac">⚖️ IRAC Studio</button>
                    <button class="tab-btn" data-tab="tab-blurting">🧠 Blurting Audit</button>
                    <button class="tab-btn" data-tab="tab-decoder">🔍 Lecturer Decoder</button>
                    <button class="tab-btn" data-tab="tab-feynman">🎯 Feynman Tutor</button>
                    <button class="tab-btn" data-tab="tab-flashcards">Active Recall (SRS)</button>
                    <button class="tab-btn" data-tab="tab-chat">💬 AI Chat</button>
                </div>

                <!-- Tab 0: Live Co-Execution Sprint Runner -->
                <div id="tab-sprint" class="tab-content active">
                    <div class="feynman-box">
                        <div class="sprint-banner">
                            <div>
                                <h4 style="color:var(--primary); margin-bottom:4px;">⚡ Live Co-Execution Study Sprint</h4>
                                <p style="font-size:0.85rem; color:#e2e8f0;">Zero initiation friction. 15 minutes of guided, real-time focus with AI beat partner.</p>
                            </div>
                            <div class="sprint-timer-text" id="sprintClock">15:00</div>
                        </div>

                        <div style="display:flex; gap:10px; margin-bottom:14px;">
                            <button id="startSprintBtn" class="send-btn" style="flex:1;">▶ Start 15-Min Sprint</button>
                            <button id="resetSprintBtn" class="tab-btn" style="border:1px solid var(--border-glass);">Reset Timer</button>
                        </div>

                        <div id="sprintBeatContent" style="background:rgba(255,183,3,0.08); border:1px solid var(--border-glass-glow); padding:16px; border-radius:12px; font-size:0.9rem; line-height:1.6;">
                            <b>Current Beat: Beat 1 - High-Yield Focus (Min 0-3)</b><br>
                            Review the primary concept from your notes on the left. Prepare to answer 1 application question.
                        </div>
                    </div>
                </div>

                <!-- Tab 1: IRAC Legal Studio Room -->
                <div id="tab-irac" class="tab-content">
                    <div class="feynman-box">
                        <div style="background:rgba(255,183,3,0.1); border:1px solid var(--primary); padding:14px; border-radius:12px;">
                            <h4 style="color:var(--primary); margin-bottom:4px;">⚖️ IRAC Legal Studio Room</h4>
                            <p style="font-size:0.85rem; color:#cbd5e1;">Legal & Criminology case analysis. Step-by-step Issue, Rule, Application, & Conclusion structuring.</p>
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:6px; font-weight:600; font-size:0.9rem;">Case Facts / Legal Scenario:</label>
                            <textarea id="iracFactsText" class="chat-input" style="width:100%; height:110px; resize:none;" placeholder="Paste case facts or legal problem scenario here..."></textarea>
                        </div>

                        <button id="evalIracBtn" class="send-btn" style="width:100%;">Generate IRAC Analysis</button>

                        <div id="iracOutput" style="margin-top:12px;"></div>
                    </div>
                </div>

                <!-- Tab 2: Blurting Memory Audit Room -->
                <div id="tab-blurting" class="tab-content">
                    <div class="feynman-box">
                        <div style="background:rgba(0,230,118,0.1); border:1px solid var(--accent-green); padding:14px; border-radius:12px;">
                            <h4 style="color:var(--accent-green); margin-bottom:4px;">🧠 Blurting & Memory Audit Room</h4>
                            <p style="font-size:0.85rem; color:#cbd5e1;">Timed 5-minute braindump. Type everything you remember, and StudyBee AI will audit your text against your uploaded notes!</p>
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:6px; font-weight:600; font-size:0.9rem;">Target Topic:</label>
                            <input type="text" id="blurtingTopic" class="chat-input" style="width:100%;" placeholder="e.g. Contract Law Exceptions or Photosynthesis Light Reactions">
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:6px; font-weight:600; font-size:0.9rem;">Your 5-Minute Memory Dump (Braindump):</label>
                            <textarea id="blurtingBraindump" class="chat-input" style="width:100%; height:110px; resize:none;" placeholder="Type every fact, term, formula, or detail you can recall without looking at notes..."></textarea>
                        </div>

                        <button id="evalBlurtingBtn" class="send-btn" style="width:100%; background:var(--accent-green); color:#000;">Audit My Memory Retention</button>

                        <div id="blurtingOutput" style="margin-top:12px;"></div>
                    </div>
                </div>

                <!-- Tab 3: Lecturer Decoder & Exam Pattern Engine -->
                <div id="tab-decoder" class="tab-content">
                    <div class="feynman-box">
                        <div style="background:rgba(251,133,0,0.1); border:1px solid var(--primary-orange); padding:14px; border-radius:12px;">
                            <h4 style="color:var(--primary-orange); margin-bottom:4px;">🔍 Lecturer Decoder & Exam Pattern Engine</h4>
                            <p style="font-size:0.85rem; color:#cbd5e1;">Reverse-engineers past exam papers and uploaded slides to pinpoint high-yield exam topics.</p>
                        </div>

                        <button id="loadDecoderBtn" class="send-btn" style="width:100%; background:var(--primary-orange); color:#000;">Decode Lecturer Exam Weightings</button>

                        <div id="decoderContainer" style="margin-top:14px; display:flex; flex-direction:column; gap:10px;"></div>
                    </div>
                </div>


                <!-- Tab 1: Grounded RAG Chat -->
                <div id="tab-chat" class="tab-content active">
                    <div id="chatMessages" class="chat-messages">
                        <div class="message-bubble ai">
                            Welcome to <b>IRC Zim AI Ultimate 2.0</b>! Powered by lightning-fast <b>Groq Llama 3.3 70B</b>. Ask any question about your notes or ZIMSEC/University syllabus. You have <b>100% full features unlocked</b> in your trial to help you pass!
                        </div>
                    </div>

                    <div class="chat-input-bar">
                        <input type="text" id="chatInput" class="chat-input" placeholder="Ask a study question about your notes..." autocomplete="off">
                        <button id="sendChatBtn" class="send-btn">Ask Groq AI</button>
                    </div>
                </div>

                <!-- Tab 2: Active Recall Flashcards (SRS) -->
                <div id="tab-flashcards" class="tab-content">
                    <div class="flashcard-wrapper">
                        <div id="flashcardContainer" class="card-container">
                            <div class="card-inner">
                                <div class="card-face card-front">
                                    <span style="color:#00e5ff; font-weight:700; font-size:0.8rem; margin-bottom:12px;">ACTIVE RECALL QUESTION</span>
                                    <h3 id="cardQuestion">Click card to reveal answer...</h3>
                                </div>
                                <div class="card-face card-back">
                                    <span style="color:#00e676; font-weight:700; font-size:0.8rem; margin-bottom:12px;">MODEL ANSWER</span>
                                    <p id="cardAnswer" style="font-size:1.05rem; line-height:1.5;">Answer explanation will appear here.</p>
                                </div>
                            </div>
                        </div>

                        <div class="srs-buttons">
                            <button class="srs-btn hard" onclick="updateMastery(5)">Again (Hard)</button>
                            <button class="srs-btn good" onclick="updateMastery(15)">Good</button>
                            <button class="srs-btn easy" onclick="updateMastery(25)">Easy</button>
                            <button id="nextCardBtn" class="upload-btn" style="margin-left:12px;">Next Card ➔</button>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Feynman Interactive Tutor -->
                <div id="tab-feynman" class="tab-content">
                    <div class="feynman-box">
                        <div class="feynman-intro">
                            <b>Feynman Technique:</b> Explain a concept in simple terms without jargon. Groq Llama 3.3 70B will review your explanation, score your mastery, and point out missing details from your notes.
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:6px; font-weight:600; font-size:0.9rem;">Target Topic:</label>
                            <input type="text" id="feynmanTopic" class="chat-input" style="width:100%;" placeholder="e.g. Newton's 2nd Law (F=ma) or Photosynthesis">
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:6px; font-weight:600; font-size:0.9rem;">Your Simple Explanation:</label>
                            <textarea id="feynmanExplanation" class="chat-input" style="width:100%; height:110px; resize:none;" placeholder="Explain it like you are teaching a 10-year old student..."></textarea>
                        </div>

                        <button id="evalFeynmanBtn" class="send-btn" style="width:100%;">Evaluate My Explanation</button>

                        <div id="feynmanFeedback" style="margin-top:12px;"></div>
                    </div>
                </div>

                <!-- Tab 4: Practice Exam Quizzer (Studley AI Rival Feature) -->
                <div id="tab-quiz" class="tab-content">
                    <div class="feynman-box">
                        <div style="background:rgba(0,229,255,0.1); border:1px solid var(--primary); padding:14px; border-radius:12px;">
                            <h4 style="color:var(--primary); margin-bottom:4px;">📝 Practice Exam & Quiz Generator</h4>
                            <p style="font-size:0.85rem; color:#cbd5e1;">Test your exam readiness with multiple-choice and fill-in-the-blank questions generated directly from your uploaded notes.</p>
                        </div>

                        <button id="loadQuizBtn" class="send-btn" style="width:100%;">Generate Exam Practice Quiz</button>

                        <div id="quizContainer" style="display:flex; flex-direction:column; gap:16px;"></div>
                    </div>
                </div>

                <!-- Tab 5: Assignment Rubric Grader (Studley AI Rival Feature) -->
                <div id="tab-rubric" class="tab-content">
                    <div class="feynman-box">
                        <div style="background:rgba(124,77,255,0.1); border:1px solid var(--accent-purple); padding:14px; border-radius:12px;">
                            <h4 style="color:var(--accent-purple); margin-bottom:4px;">📑 Assignment & Rubric Grader</h4>
                            <p style="font-size:0.85rem; color:#cbd5e1;">Paste your assignment draft and rubric to get detailed examiner feedback and estimated marks before submitting.</p>
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:4px; font-weight:600; font-size:0.85rem;">Marking Rubric / Criteria:</label>
                            <input type="text" id="rubricCriteria" class="chat-input" style="width:100%;" placeholder="e.g. Structure (30%), Accuracy (40%), Evidence (30%)">
                        </div>

                        <div>
                            <label style="display:block; margin-bottom:4px; font-weight:600; font-size:0.85rem;">Your Assignment Draft:</label>
                            <textarea id="essayDraftText" class="chat-input" style="width:100%; height:110px; resize:none;" placeholder="Paste your essay draft here..."></textarea>
                        </div>

                        <button id="gradeRubricBtn" class="send-btn" style="width:100%;">Grade Draft Against Rubric</button>

                        <div id="rubricOutput" style="margin-top:12px;"></div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Mobile Bottom Navigation Dock (Visible on Mobile View) -->
        <nav class="mobile-bottom-nav">
            <button class="mobile-nav-btn active" data-tab="tab-chat">
                <span class="nav-icon">💬</span>
                <span class="nav-label">Chat</span>
            </button>
            <button class="mobile-nav-btn" data-tab="tab-flashcards">
                <span class="nav-icon">🧠</span>
                <span class="nav-label">Cards</span>
            </button>
            <button class="mobile-nav-btn" data-tab="tab-feynman">
                <span class="nav-icon">🎯</span>
                <span class="nav-label">Feynman</span>
            </button>
            <button class="mobile-nav-btn" data-tab="tab-quiz">
                <span class="nav-icon">📝</span>
                <span class="nav-label">Practice</span>
            </button>
            <button class="mobile-nav-btn" data-tab="tab-vault">
                <span class="nav-icon">📚</span>
                <span class="nav-label">Vault</span>
            </button>
        </nav>
    </div>

    <!-- EcoCash / Paynow Modal -->
    <div id="paymentModal" class="modal-overlay">
        <div class="modal-content">
            <h3 style="font-family:var(--font-heading); font-size:1.3rem; margin-bottom:8px; color:#fff;">
                Support IRC Zim AI 🇿🇼
            </h3>
            <p style="color:#00e5ff; font-size:0.88rem; line-height:1.4; margin-bottom:14px; background:rgba(0,229,255,0.1); padding:10px; border-radius:8px;">
                💡 <b>IRC Philosophy:</b> Your free trial includes 100% full features so you can pass your exams. You pay because the app helped you pass—not to paywall study tools!
            </p>

            <div style="margin-bottom:14px;">
                <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:4px;">Select Supporter Option:</label>
                <select id="passTypeSelect" class="workspace-select" style="width:100%;">
                    <option value="pass_supporter">Option 1: Pass Supporter Pass - $0.50 EcoCash</option>
                    <option value="scholar_supporter">Option 2: Scholar Supporter Pass - $5.00 EcoCash</option>
                </select>
            </div>

            <div style="margin-bottom:18px;">
                <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:4px;">EcoCash / Mobile Number:</label>
                <input type="text" id="ecocashPhone" class="chat-input" style="width:100%;" placeholder="077XXXXXXX">
            </div>

            <div style="display:flex; gap:10px;">
                <button id="closeModalBtn" class="tab-btn" style="flex:1; border:1px solid var(--border-glass);">Close</button>
                <button id="payNowBtn" class="send-btn" style="flex:2; background:var(--ecocash-red); color:#fff;">Pay via EcoCash</button>
            </div>
        </div>
    </div>

    <!-- Coming Soon Wall Modal -->
    <div id="comingSoonModal" class="modal-overlay">
        <div class="modal-content" style="border-color:var(--accent-purple); box-shadow:0 0 40px rgba(124,77,255,0.3);">
            <h3 style="font-family:var(--font-heading); font-size:1.4rem; color:var(--accent-purple); margin-bottom:10px;">
                🚀 EcoCash Payments - Feature Coming Soon!
            </h3>
            <p style="color:#f0f4f8; font-size:0.95rem; line-height:1.5; margin-bottom:16px;">
                Thank you for wanting to support local Zimbabwean AI innovation!
            </p>
            <div style="background:rgba(124,77,255,0.15); border:1px solid var(--accent-purple); padding:14px; border-radius:12px; font-size:0.9rem; line-height:1.5; color:#e2e8f0; margin-bottom:20px;">
                🎓 <b>Remember:</b> Your current free trial already gives you <b>100% FULL ACCESS</b> to all tools (Groq Llama 3.3 RAG, Active Recall Flashcards, Feynman Tutor, Quizzer, and Rubric Grader). Focus on your studies and ace those exams first!
            </div>
            <button id="closeComingSoonBtn" class="send-btn" style="width:100%;">Back to Studying 📚</button>
        </div>
    </div>

    <script src="/js/app.js"></script>
    <script>
        async function uploadFile(event) {
            const file = event.target.files[0];
            if (!file) return;
            
            const formData = new FormData();
            formData.append('file', file);
            formData.append('workspace_id', currentWorkspaceId);

            const viewer = document.getElementById('documentViewer');
            viewer.innerHTML = '<div style="color:#00e5ff; text-align:center; padding:40px;">Indexing notes into Firebase & Groq engine...</div>';

            try {
                const res = await fetch('/api/documents', {
                    method: 'POST',
                    body: formData
                });
                const json = await res.json();
                if (json.status === 'success') {
                    alert('✅ Document indexed into Firebase successfully!');
                    loadWorkspaceDocuments(currentWorkspaceId);
                } else {
                    alert('Upload error: ' + json.message);
                }
            } catch (e) {
                alert('File upload failed.');
            }
        }
    </script>
</body>
</html>
