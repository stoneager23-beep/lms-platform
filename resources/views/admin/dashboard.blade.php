<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-white tracking-tight">Admin Overview</h2>
                <p class="text-xs text-slate-500 mt-0.5">Platform statistics and instructor management</p>
            </div>
            <a href="{{ route('admin.instructors.index') }}"
               class="relative inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold
                      bg-amber-500/10 text-amber-400 border border-amber-500/25
                      hover:bg-amber-500/20 transition-all duration-200">
                @if($stats['pendingCount'] > 0)
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-400"></span>
                    </span>
                    Pending Approvals
                    <span class="bg-amber-500 text-white text-xs font-black px-1.5 py-0.5 rounded-full leading-none">{{ $stats['pendingCount'] }}</span>
                @else
                    ⚙ Manage Instructors
                @endif
            </a>
        </div>
    </x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --bg:        #080c14;
            --surf:      #0d1220;
            --surf2:     #111827;
            --surf3:     #1a2235;
            --border:    rgba(255,255,255,0.07);
            --border2:   rgba(255,255,255,0.04);
            --accent:    #818cf8;
            --accent2:   #34d399;
            --amber:     #fbbf24;
            --red:       #f87171;
            --blue:      #60a5fa;
            --text:      #f1f5f9;
            --muted:     #64748b;
            --muted2:    #94a3b8;
        }

        .adm { background: var(--bg); min-height: 100vh; padding: 2rem 1.5rem; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text); }
        .adm-inner { max-width: 1300px; margin: 0 auto; }

        /* ── STAT CARDS ── */
        .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 2rem; }
        .kpi {
            background: var(--surf);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 1.4rem 1.6rem;
            position: relative;
            overflow: hidden;
            transition: transform .2s, border-color .25s;
        }
        .kpi:hover { transform: translateY(-3px); border-color: rgba(255,255,255,.14); }
        .kpi::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; height: 2px;
            border-radius: 16px 16px 0 0;
        }
        .kpi-students::before  { background: linear-gradient(90deg, #3b82f6, #6366f1); }
        .kpi-instructors::before { background: linear-gradient(90deg, #8b5cf6, #a78bfa); }
        .kpi-courses::before   { background: linear-gradient(90deg, #10b981, #34d399); }
        .kpi-pending::before   { background: linear-gradient(90deg, #f59e0b, #fbbf24); }
        .kpi-glow {
            position: absolute; top: -20px; right: -20px;
            width: 80px; height: 80px; border-radius: 50%;
            opacity: .06; pointer-events: none;
        }
        .kpi-label { font-size: .7rem; font-weight: 700; text-transform: uppercase; letter-spacing: .1em; color: var(--muted); margin-bottom: .6rem; }
        .kpi-val { font-size: 2.4rem; font-weight: 800; line-height: 1; font-family: 'DM Mono', monospace; }
        .kpi-sub { font-size: .72rem; color: var(--muted); margin-top: .4rem; }

        /* ── SECTION HEADER ── */
        .sh { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
        .sh-title { font-size: .95rem; font-weight: 800; letter-spacing: -.01em; display: flex; align-items: center; gap: .6rem; }
        .sh-badge { font-size: .68rem; font-weight: 800; padding: .2rem .6rem; border-radius: 99px; }
        .sh-link { font-size: .78rem; font-weight: 600; color: var(--accent); text-decoration: none; opacity: .8; }
        .sh-link:hover { opacity: 1; text-decoration: underline; }

        /* ── TABLE CARD ── */
        .tcard {
            background: var(--surf);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .tcard table { width: 100%; border-collapse: collapse; }
        .tcard thead tr { background: var(--surf2); border-bottom: 1px solid var(--border); }
        .tcard th { padding: .85rem 1.25rem; font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: var(--muted); text-align: left; white-space: nowrap; }
        .tcard th.right { text-align: right; }
        .tcard th.center { text-align: center; }
        .tcard tbody tr { border-bottom: 1px solid var(--border2); transition: background .15s; }
        .tcard tbody tr:last-child { border-bottom: none; }
        .tcard tbody tr:hover { background: rgba(255,255,255,.025); }
        .tcard td { padding: 1rem 1.25rem; font-size: .83rem; vertical-align: middle; }
        .tcard td.right { text-align: right; }
        .tcard td.center { text-align: center; }

        /* ── AVATAR ── */
        .av { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: .8rem; font-weight: 800; color: #fff; flex-shrink: 0; }
        .av-purple { background: linear-gradient(135deg, #6366f1, #8b5cf6); box-shadow: 0 4px 12px rgba(99,102,241,.25); }
        .av-amber  { background: linear-gradient(135deg, #f59e0b, #ef4444); box-shadow: 0 4px 12px rgba(245,158,11,.25); }
        .av-green  { background: linear-gradient(135deg, #10b981, #059669); box-shadow: 0 4px 12px rgba(16,185,129,.25); }

        /* ── PILLS ── */
        .pill { display: inline-flex; align-items: center; font-size: .68rem; font-weight: 700; padding: .2rem .65rem; border-radius: 99px; }
        .pill-active  { background: rgba(52,211,153,.1);  color: #34d399; border: 1px solid rgba(52,211,153,.2); }
        .pill-pending { background: rgba(251,191,36,.1);  color: #fbbf24; border: 1px solid rgba(251,191,36,.2); }
        .pill-count   { background: rgba(129,140,248,.12); color: var(--accent); border: 1px solid rgba(129,140,248,.2); min-width: 26px; text-align: center; }
        .pill-zero    { background: rgba(255,255,255,.05); color: var(--muted); border: 1px solid var(--border); min-width: 26px; text-align: center; }

        /* ── BUTTONS ── */
        .btn-remove {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .4rem .9rem; border-radius: 8px; font-size: .72rem; font-weight: 700;
            background: rgba(248,113,113,.08); color: var(--red);
            border: 1px solid rgba(248,113,113,.18);
            cursor: pointer; transition: all .2s; white-space: nowrap;
        }
        .btn-remove:hover { background: rgba(248,113,113,.18); border-color: rgba(248,113,113,.35); transform: translateY(-1px); }
        .btn-approve {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .4rem .9rem; border-radius: 8px; font-size: .72rem; font-weight: 700;
            background: rgba(52,211,153,.08); color: var(--accent2);
            border: 1px solid rgba(52,211,153,.18);
            cursor: pointer; transition: all .2s;
        }
        .btn-approve:hover { background: rgba(52,211,153,.18); border-color: rgba(52,211,153,.35); }
        .btn-reject {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .4rem .9rem; border-radius: 8px; font-size: .72rem; font-weight: 700;
            background: rgba(248,113,113,.08); color: var(--red);
            border: 1px solid rgba(248,113,113,.18);
            cursor: pointer; transition: all .2s;
        }
        .btn-reject:hover { background: rgba(248,113,113,.18); border-color: rgba(248,113,113,.35); }
        .btn-review {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .4rem .9rem; border-radius: 8px; font-size: .72rem; font-weight: 700;
            background: rgba(251,191,36,.08); color: var(--amber);
            border: 1px solid rgba(251,191,36,.18);
            text-decoration: none; transition: all .2s;
        }
        .btn-review:hover { background: rgba(251,191,36,.18); }

        /* ── FLASH ── */
        .flash { padding: .9rem 1.25rem; border-radius: 12px; font-size: .82rem; font-weight: 500; margin-bottom: 1.5rem; display: flex; align-items: center; gap: .75rem; }
        .flash-success { background: rgba(52,211,153,.07); border: 1px solid rgba(52,211,153,.18); color: #34d399; }
        .flash-info    { background: rgba(96,165,250,.07);  border: 1px solid rgba(96,165,250,.18);  color: var(--blue); }
        .flash-error   { background: rgba(248,113,113,.07); border: 1px solid rgba(248,113,113,.18); color: var(--red); }

        /* ── EMPTY ── */
        .empty-state { padding: 3.5rem 1rem; text-align: center; }
        .empty-icon { font-size: 2rem; margin-bottom: .75rem; }
        .empty-text { font-size: .85rem; font-weight: 600; color: var(--muted2); }
        .empty-sub  { font-size: .75rem; color: var(--muted); margin-top: .3rem; }

        /* ── BOTTOM GRID ── */
        .bottom-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem; margin-bottom: 2rem; }
        .panel { background: var(--surf); border: 1px solid var(--border); border-radius: 16px; padding: 1.4rem 1.6rem; }
        .panel-title { font-size: .85rem; font-weight: 800; margin-bottom: 1.1rem; display: flex; align-items: center; gap: .5rem; }
        .chart-wrap { position: relative; height: 200px; }

        /* Quick stats sidebar */
        .qs-item { display: flex; justify-content: space-between; align-items: center; padding: .7rem 0; border-bottom: 1px solid var(--border2); }
        .qs-item:last-child { border-bottom: none; }
        .qs-label { font-size: .8rem; color: var(--muted2); font-weight: 500; }
        .qs-val { font-size: .95rem; font-weight: 800; font-family: 'DM Mono', monospace; }

        /* ── MODAL ── */
        .modal-bg { position: fixed; inset: 0; background: rgba(0,0,0,.7); backdrop-filter: blur(6px); z-index: 50; display: none; align-items: center; justify-content: center; padding: 1rem; }
        .modal-bg.open { display: flex; }
        .modal-box { background: #0f1729; border: 1px solid rgba(255,255,255,.1); border-radius: 20px; width: 100%; max-width: 420px; padding: 2rem; }
        .modal-icon { width: 52px; height: 52px; border-radius: 14px; background: rgba(248,113,113,.1); border: 1px solid rgba(248,113,113,.2); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; }
        .modal-title { font-size: 1.1rem; font-weight: 800; text-align: center; margin-bottom: .4rem; }
        .modal-sub { font-size: .82rem; color: var(--muted2); text-align: center; margin-bottom: 1.25rem; }
        .modal-warn { background: rgba(248,113,113,.05); border: 1px solid rgba(248,113,113,.12); border-radius: 12px; padding: 1rem 1.1rem; margin-bottom: 1.5rem; }
        .modal-warn-title { font-size: .78rem; font-weight: 700; color: var(--red); margin-bottom: .5rem; }
        .modal-warn ul { list-style: disc; padding-left: 1.1rem; }
        .modal-warn li { font-size: .75rem; color: var(--muted2); margin-bottom: .25rem; }
        .modal-actions { display: flex; gap: .75rem; }
        .modal-cancel { flex: 1; padding: .7rem; border-radius: 10px; background: var(--surf2); border: 1px solid var(--border); color: var(--muted2); font-size: .82rem; font-weight: 600; cursor: pointer; transition: background .2s; }
        .modal-cancel:hover { background: var(--surf3); }
        .modal-confirm { flex: 1; padding: .7rem; border-radius: 10px; background: #dc2626; border: none; color: #fff; font-size: .82rem; font-weight: 700; cursor: pointer; transition: background .2s; box-shadow: 0 4px 14px rgba(220,38,38,.3); }
        .modal-confirm:hover { background: #ef4444; }

        @media (max-width: 900px) {
            .kpi-grid { grid-template-columns: repeat(2,1fr); }
            .bottom-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 560px) {
            .kpi-grid { grid-template-columns: 1fr 1fr; }
        }
    </style>

    <div class="adm">
        <div class="adm-inner">

            {{-- Flash --}}
            @if(session('success'))
                <div class="flash flash-success">✓ {{ session('success') }}</div>
            @endif
            @if(session('info'))
                <div class="flash flash-info">ℹ {{ session('info') }}</div>
            @endif
            @if(session('error'))
                <div class="flash flash-error">✕ {{ session('error') }}</div>
            @endif

            {{-- ── KPI CARDS ── --}}
            <div class="kpi-grid">
                <div class="kpi kpi-students">
                    <div class="kpi-glow" style="background:#3b82f6"></div>
                    <div class="kpi-label">Total Students</div>
                    <div class="kpi-val" style="color:#60a5fa">{{ $stats['totalStudents'] }}</div>
                    <div class="kpi-sub">Registered learners</div>
                </div>
                <div class="kpi kpi-instructors">
                    <div class="kpi-glow" style="background:#8b5cf6"></div>
                    <div class="kpi-label">Instructors</div>
                    <div class="kpi-val" style="color:#a78bfa">{{ $stats['totalInstructors'] }}</div>
                    <div class="kpi-sub">Active & approved</div>
                </div>
                <div class="kpi kpi-courses">
                    <div class="kpi-glow" style="background:#10b981"></div>
                    <div class="kpi-label">Active Courses</div>
                    <div class="kpi-val" style="color:#34d399">{{ $stats['activeCourses'] }}</div>
                    <div class="kpi-sub">Published on platform</div>
                </div>
                <div class="kpi kpi-pending">
                    <div class="kpi-glow" style="background:#f59e0b"></div>
                    <div class="kpi-label">Pending Approval</div>
                    <div class="kpi-val" style="color:#fbbf24">{{ $stats['pendingCount'] }}</div>
                    <div class="kpi-sub">Awaiting review</div>
                </div>
            </div>

            {{-- ── APPROVED INSTRUCTORS ── --}}
            <div class="sh">
                <div class="sh-title">
                    👨‍🏫 Approved Instructors
                    <span class="sh-badge" style="background:rgba(129,140,248,.1);color:var(--accent);border:1px solid rgba(129,140,248,.2)">
                        {{ $approvedInstructors->count() }}
                    </span>
                </div>
                <a href="{{ route('admin.instructors.active') }}" class="sh-link">View all →</a>
            </div>

            <div class="tcard">
                <table>
                    <thead>
                        <tr>
                            <th>Instructor</th>
                            <th>Email</th>
                            <th class="center">Courses</th>
                            <th>Joined</th>
                            <th class="right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($approvedInstructors as $instructor)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:.75rem">
                                        <div class="av av-purple">{{ strtoupper(substr($instructor->name,0,1)) }}</div>
                                        <div>
                                            <div style="font-weight:700;font-size:.85rem">{{ $instructor->name }}</div>
                                            <span class="pill pill-active" style="margin-top:.2rem">Active</span>
                                        </div>
                                    </div>
                                </td>
                                <td style="color:var(--muted2)">{{ $instructor->email }}</td>
                                <td class="center">
                                    <span class="pill {{ $instructor->courses_count > 0 ? 'pill-count' : 'pill-zero' }}">
                                        {{ $instructor->courses_count }}
                                    </span>
                                </td>
                                <td>
                                    <div style="font-size:.82rem;color:var(--muted2)">{{ $instructor->created_at->format('M d, Y') }}</div>
                                    <div style="font-size:.7rem;color:var(--muted)">{{ $instructor->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="right">
                                    <button class="btn-remove"
                                            onclick="openModal({{ $instructor->id }}, '{{ addslashes($instructor->name) }}', {{ $instructor->courses_count }})">
                                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                  d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                        Remove Access
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5"><div class="empty-state"><div class="empty-icon">👨‍🏫</div><div class="empty-text">No approved instructors yet</div></div></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ── BOTTOM GRID: Chart + Quick Stats ── --}}
            <div class="bottom-grid">
                <div class="panel">
                    <div class="panel-title">📊 Courses per Instructor</div>
                    <div class="chart-wrap">
                        <canvas id="instrChart"></canvas>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-title">⚡ Quick Stats</div>
                    @php
                        $avgCourses = $approvedInstructors->count() > 0
                            ? round($approvedInstructors->avg('courses_count'), 1)
                            : 0;
                        $topInstructor = $approvedInstructors->sortByDesc('courses_count')->first();
                    @endphp
                    <div class="qs-item">
                        <span class="qs-label">Avg courses / instructor</span>
                        <span class="qs-val" style="color:var(--accent)">{{ $avgCourses }}</span>
                    </div>
                    <div class="qs-item">
                        <span class="qs-label">Top instructor</span>
                        <span class="qs-val" style="color:var(--accent2);font-size:.78rem">{{ $topInstructor?->name ?? '—' }}</span>
                    </div>
                    <div class="qs-item">
                        <span class="qs-label">Approval rate</span>
                        @php
                            $total = $stats['totalInstructors'] + $stats['pendingCount'];
                            $rate  = $total > 0 ? round(($stats['totalInstructors'] / $total) * 100) : 0;
                        @endphp
                        <span class="qs-val" style="color:var(--accent2)">{{ $rate }}%</span>
                    </div>
                    <div class="qs-item">
                        <span class="qs-label">Pending review</span>
                        <span class="qs-val" style="color:var(--amber)">{{ $stats['pendingCount'] }}</span>
                    </div>
                    <div class="qs-item">
                        <span class="qs-label">Total students</span>
                        <span class="qs-val" style="color:var(--blue)">{{ $stats['totalStudents'] }}</span>
                    </div>
                </div>
            </div>

            {{-- ── PENDING REQUESTS ── --}}
            <div class="sh">
                <div class="sh-title">
                    ⏳ Recent Pending Requests
                    @if($pendingInstructors->count() > 0)
                        <span class="sh-badge" style="background:rgba(251,191,36,.1);color:var(--amber);border:1px solid rgba(251,191,36,.2)">
                            {{ $pendingInstructors->count() }}
                        </span>
                    @endif
                </div>
                @if($pendingInstructors->count() > 0)
                    <a href="{{ route('admin.instructors.index') }}" class="sh-link">Review all →</a>
                @endif
            </div>

            <div class="tcard">
                <table>
                    <thead>
                        <tr>
                            <th>Applicant</th>
                            <th>Email</th>
                            <th>Applied</th>
                            <th class="right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingInstructors->take(5) as $instructor)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:.75rem">
                                        <div class="av av-amber">{{ strtoupper(substr($instructor->name,0,1)) }}</div>
                                        <div>
                                            <div style="font-weight:700;font-size:.85rem">{{ $instructor->name }}</div>
                                            <span class="pill pill-pending" style="margin-top:.2rem">Pending</span>
                                        </div>
                                    </div>
                                </td>
                                <td style="color:var(--muted2)">{{ $instructor->email }}</td>
                                <td>
                                    <div style="font-size:.82rem;color:var(--muted2)">{{ $instructor->created_at->format('M d, Y') }}</div>
                                    <div style="font-size:.7rem;color:var(--muted)">{{ $instructor->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="right">
                                    <div style="display:flex;align-items:center;justify-content:flex-end;gap:.5rem">
                                        <form method="POST" action="{{ route('admin.instructors.approve', $instructor) }}">
                                            @csrf
                                            <button type="submit" class="btn-approve">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Approve
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.instructors.reject', $instructor) }}"
                                              onsubmit="return confirm('Reject and remove this application?')">
                                            @csrf
                                            <button type="submit" class="btn-reject">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-icon">✅</div>
                                        <div class="empty-text">No pending applications</div>
                                        <div class="empty-sub">All instructor requests have been reviewed 🎉</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- ── TERMINATE MODAL ── --}}
    <div id="modal" class="modal-bg" onclick="if(event.target===this)closeModal()">
        <div class="modal-box">
            <div class="modal-icon">
                <svg width="22" height="22" fill="none" stroke="#f87171" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
            <div class="modal-title">Remove Instructor Access</div>
            <div class="modal-sub">
                You are about to terminate <strong id="mName" style="color:#f87171"></strong>
            </div>
            <div class="modal-warn">
                <div class="modal-warn-title">⚠ This action will:</div>
                <ul>
                    <li>Soft-delete the instructor's account</li>
                    <li>Unpublish all their courses (<span id="mCount" style="color:#fff;font-weight:700"></span> courses affected)</li>
                    <li>Permanently remove all student enrollments in those courses</li>
                </ul>
            </div>
            <div class="modal-actions">
                <button class="modal-cancel" onclick="closeModal()">Cancel</button>
                <form id="mForm" method="POST" style="flex:1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="modal-confirm" style="width:100%">Confirm Remove</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Modal
        function openModal(id, name, count) {
            document.getElementById('mName').textContent = name;
            document.getElementById('mCount').textContent = count;
            document.getElementById('mForm').action = '/admin/instructors/' + id + '/terminate';
            document.getElementById('modal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeModal() {
            document.getElementById('modal').classList.remove('open');
            document.body.style.overflow = '';
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });

        // Chart
        document.addEventListener('DOMContentLoaded', function () {
            const labels = @json($approvedInstructors->map(fn($i) => strlen($i->name) > 14 ? substr($i->name,0,14).'…' : $i->name)->values());
            const data   = @json($approvedInstructors->pluck('courses_count')->values());

            new Chart(document.getElementById('instrChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Courses',
                        data,
                        backgroundColor: data.map((v,i) => {
                            const colors = ['rgba(129,140,248,.65)','rgba(52,211,153,.65)','rgba(251,191,36,.65)','rgba(96,165,250,.65)','rgba(248,113,113,.65)'];
                            return colors[i % colors.length];
                        }),
                        borderColor: data.map((v,i) => {
                            const colors = ['#818cf8','#34d399','#fbbf24','#60a5fa','#f87171'];
                            return colors[i % colors.length];
                        }),
                        borderWidth: 1.5,
                        borderRadius: 8,
                        borderSkipped: false,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0d1220',
                            titleColor: '#f1f5f9',
                            bodyColor: '#64748b',
                            borderColor: 'rgba(255,255,255,.07)',
                            borderWidth: 1,
                            callbacks: { label: ctx => ` ${ctx.parsed.y} course${ctx.parsed.y !== 1 ? 's' : ''}` }
                        }
                    },
                    scales: {
                        x: { ticks: { color: '#64748b', font: { size: 11, family: 'Plus Jakarta Sans' } }, grid: { color: 'rgba(255,255,255,0.03)' } },
                        y: { beginAtZero: true, ticks: { color: '#64748b', font: { size: 11 }, precision: 0 }, grid: { color: 'rgba(255,255,255,0.04)' } }
                    }
                }
            });
        });
    </script>
</x-app-layout>