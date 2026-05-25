<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color:#e2e8f0">
            Instructor Dashboard
        </h2>
    </x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --bg:       #0f1117;
            --surface:  #181c27;
            --surface2: #1e2333;
            --border:   #2a3045;
            --accent:   #6c8cff;
            --green:    #38e2b8;
            --orange:   #f97316;
            --yellow:   #facc15;
            --red:      #f87171;
            --text:     #e2e8f0;
            --muted:    #7a85a0;
            --r:        16px;
        }
        .dash { background: var(--bg); min-height: 100vh; padding: 2rem 1.5rem; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text); }
        .inner { max-width: 1280px; margin: 0 auto; }

        /* HERO */
        .hero {
            background: linear-gradient(135deg, #101828 0%, #0f1117 70%);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem 2.5rem;
            margin-bottom: 1.75rem;
            display: flex; justify-content: space-between; align-items: center;
            position: relative; overflow: hidden;
        }
        .hero::after {
            content: '';
            position: absolute; top: -80px; right: -80px;
            width: 260px; height: 260px;
            background: radial-gradient(circle, rgba(56,226,184,.1) 0%, transparent 70%);
            border-radius: 50%; pointer-events: none;
        }
        .hero-greeting { font-size: .78rem; color: var(--muted); text-transform: uppercase; letter-spacing: .08em; }
        .hero-name { font-size: 2rem; font-weight: 800; margin: .2rem 0 .4rem; background: linear-gradient(90deg, #fff, var(--green)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-sub { font-size: .85rem; color: var(--muted); }
        .hero-pill { background: linear-gradient(135deg, var(--green), #2ab894); color: #0a2e24; font-size: .78rem; font-weight: 800; padding: .6rem 1.3rem; border-radius: 12px; letter-spacing: .04em; }

        /* STATS */
        .stats-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem; margin-bottom: 1.75rem; }
        .stat {
            background: var(--surface); border: 1px solid var(--border); border-radius: var(--r);
            padding: 1.2rem 1.4rem; display: flex; align-items: center; gap: .9rem;
            transition: transform .2s, border-color .2s;
        }
        .stat:hover { transform: translateY(-3px); border-color: var(--accent); }
        .stat-icon { width: 42px; height: 42px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
        .stat-val { font-size: 1.6rem; font-weight: 800; line-height: 1; }
        .stat-lbl { font-size: .72rem; color: var(--muted); margin-top: .2rem; font-weight: 500; }

        /* SECTION */
        .sec { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .sec-title { font-size: 1rem; font-weight: 700; }
        .sec-link { font-size: .8rem; color: var(--accent); text-decoration: none; font-weight: 600; }
        .sec-link:hover { text-decoration: underline; }

        /* COURSE TABLE */
        .course-table { width: 100%; border-collapse: collapse; margin-bottom: 1.75rem; }
        .course-table th { font-size: .72rem; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: .06em; padding: .6rem 1rem; text-align: left; border-bottom: 1px solid var(--border); }
        .course-table td { padding: .85rem 1rem; border-bottom: 1px solid rgba(42,48,69,.5); font-size: .85rem; vertical-align: middle; }
        .course-table tr:last-child td { border-bottom: none; }
        .course-table tr:hover td { background: rgba(255,255,255,.02); }
        .table-wrap { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); overflow: hidden; margin-bottom: 1.75rem; }
        .status-pill { font-size: .7rem; font-weight: 700; padding: .25rem .7rem; border-radius: 99px; }
        .status-published { background: rgba(56,226,184,.12); color: var(--green); }
        .status-draft { background: rgba(249,115,22,.12); color: var(--orange); }
        .tbl-btn { font-size: .75rem; font-weight: 700; padding: .3rem .8rem; border-radius: 8px; text-decoration: none; border: 1px solid var(--border); color: var(--text); transition: background .15s; }
        .tbl-btn:hover { background: var(--accent); border-color: var(--accent); color: #fff; }
        .thumb-sm { width: 44px; height: 32px; object-fit: cover; border-radius: 6px; }
        .thumb-placeholder { width: 44px; height: 32px; border-radius: 6px; background: var(--surface2); display:flex; align-items:center; justify-content:center; font-size:.9rem; }

        /* BOTTOM GRID */
        .bottom { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.1rem; }
        .panel { background: var(--surface); border: 1px solid var(--border); border-radius: var(--r); padding: 1.25rem 1.5rem; }
        .panel-title { font-size: .88rem; font-weight: 700; margin-bottom: 1rem; }
        .chart-wrap { position: relative; height: 190px; }

        /* Enrollment list */
        .enroll-item { display: flex; align-items: center; gap: .75rem; padding: .6rem 0; border-bottom: 1px solid var(--border); }
        .enroll-item:last-child { border-bottom: none; }
        .enroll-avatar { width: 30px; height: 30px; border-radius: 50%; background: linear-gradient(135deg, var(--accent), #4f6fd4); display:flex; align-items:center; justify-content:center; font-size:.75rem; font-weight:700; color:#fff; flex-shrink:0; }
        .enroll-name { font-size: .82rem; font-weight: 600; }
        .enroll-course { font-size: .72rem; color: var(--muted); }
        .enroll-time { font-size: .7rem; color: var(--muted); margin-left: auto; white-space: nowrap; }

        /* Quiz stats */
        .quiz-row { padding: .65rem 0; border-bottom: 1px solid var(--border); }
        .quiz-row:last-child { border-bottom: none; }
        .quiz-course { font-size: .82rem; font-weight: 600; margin-bottom: .4rem; }
        .quiz-meta-row { display: flex; gap: 1rem; font-size: .72rem; color: var(--muted); }
        .quiz-stat { display: flex; flex-direction: column; gap: .1rem; }
        .quiz-stat-val { font-size: .9rem; font-weight: 700; color: var(--text); }

        .empty { text-align: center; padding: 1.5rem 0; color: var(--muted); font-size: .83rem; }

        @media (max-width: 1100px) {
            .stats-grid { grid-template-columns: repeat(3, 1fr); }
            .bottom { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 720px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .bottom { grid-template-columns: 1fr; }
            .hero { flex-direction: column; align-items: flex-start; gap: 1rem; }
        }
    </style>

    <div class="dash">
        <div class="inner">

            {{-- HERO --}}
            <div class="hero">
                <div>
                    <div class="hero-greeting">Instructor Portal</div>
                    <div class="hero-name">{{ Auth::user()->name }} 👨‍🏫</div>
                    <div class="hero-sub">Manage your courses, track student progress, review quiz performance.</div>
                </div>
                <div class="hero-pill">🎓 Instructor</div>
            </div>

            {{-- STATS --}}
            <div class="stats-grid">
                @php
                    $stats = [
                        ['icon'=>'📚','bg'=>'rgba(108,140,255,.12)','val'=>$totalCourses,    'lbl'=>'Total Courses'],
                        ['icon'=>'✅','bg'=>'rgba(56,226,184,.12)', 'val'=>$publishedCourses,'lbl'=>'Published'],
                        ['icon'=>'✏️', 'bg'=>'rgba(249,115,22,.12)', 'val'=>$draftCourses,    'lbl'=>'Drafts'],
                        ['icon'=>'👥','bg'=>'rgba(234,179,8,.12)',  'val'=>$totalStudents,   'lbl'=>'Total Students'],
                        ['icon'=>'📝','bg'=>'rgba(248,113,113,.12)','val'=>$totalAttempts,   'lbl'=>'Quiz Attempts'],
                    ];
                @endphp
                @foreach ($stats as $s)
                    <div class="stat">
                        <div class="stat-icon" style="background:{{ $s['bg'] }}">{{ $s['icon'] }}</div>
                        <div>
                            <div class="stat-val">{{ $s['val'] }}</div>
                            <div class="stat-lbl">{{ $s['lbl'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- COURSES TABLE --}}
            <div class="sec">
                <div class="sec-title">My Courses</div>
                <a href="{{ route('instructor.courses.index') }}" class="sec-link">Manage all →</a>
            </div>

            <div class="table-wrap">
                @if ($courses->isEmpty())
                    <div class="empty" style="padding:2rem">No courses yet. <a href="{{ route('instructor.courses.create') }}" class="sec-link">Create one →</a></div>
                @else
                    <table class="course-table">
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Status</th>
                                <th>Lessons</th>
                                <th>Students</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($courses as $course)
                                <tr>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:.75rem">
                                            @if ($course->thumbnail)
                                                <img src="{{ asset('storage/'.$course->thumbnail) }}" class="thumb-sm" alt="">
                                            @else
                                                <div class="thumb-placeholder">📖</div>
                                            @endif
                                            <span style="font-weight:600">{{ $course->title }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-pill status-{{ $course->status }}">
                                            {{ ucfirst($course->status) }}
                                        </span>
                                    </td>
                                    <td style="color:var(--muted)">{{ $course->lessons_count }}</td>
                                    <td style="color:var(--muted)">{{ $course->students_count }}</td>
                                    <td>
                                        <a href="{{ route('instructor.courses.edit', $course) }}" class="tbl-btn">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- BOTTOM GRID --}}
            <div class="bottom">

                {{-- Students per course chart --}}
                <div class="panel">
                    <div class="panel-title">👥 Students per Course</div>
                    <div class="chart-wrap">
                        <canvas id="studentsChart"></canvas>
                    </div>
                </div>

                {{-- Recent Enrollments --}}
                <div class="panel">
                    <div class="panel-title">🔔 Recent Enrollments</div>
                    @if ($recentEnrollments->isEmpty())
                        <div class="empty">No enrollments yet.</div>
                    @else
                        @foreach ($recentEnrollments as $e)
                            <div class="enroll-item">
                                <div class="enroll-avatar">{{ strtoupper(substr($e->student_name, 0, 1)) }}</div>
                                <div>
                                    <div class="enroll-name">{{ $e->student_name }}</div>
                                    <div class="enroll-course">{{ Str::limit($e->course_title, 28) }}</div>
                                </div>
                                <div class="enroll-time">{{ \Carbon\Carbon::parse($e->created_at)->diffForHumans() }}</div>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Quiz Performance --}}
                <div class="panel">
                    <div class="panel-title">📊 Quiz Performance</div>
                    @if ($quizStats->isEmpty())
                        <div class="empty">No quiz attempts yet.</div>
                    @else
                        @foreach ($quizStats as $qs)
                            <div class="quiz-row">
                                <div class="quiz-course">{{ Str::limit($qs['title'], 30) }}</div>
                                <div class="quiz-meta-row">
                                    <div class="quiz-stat">
                                        <span class="quiz-stat-val">{{ $qs['total'] }}</span>
                                        <span>Attempts</span>
                                    </div>
                                    <div class="quiz-stat">
                                        <span class="quiz-stat-val" style="color:var(--green)">{{ $qs['pass_rate'] }}%</span>
                                        <span>Pass Rate</span>
                                    </div>
                                    <div class="quiz-stat">
                                        <span class="quiz-stat-val" style="color:var(--yellow)">{{ $qs['avg_score'] }}%</span>
                                        <span>Avg Score</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const labels = @json($chartLabels->values());
            const data   = @json($chartData->values());

            new Chart(document.getElementById('studentsChart').getContext('2d'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Students',
                        data,
                        backgroundColor: 'rgba(56,226,184,0.65)',
                        borderColor: '#38e2b8',
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
                            backgroundColor: '#1e2333',
                            titleColor: '#e2e8f0',
                            bodyColor: '#7a85a0',
                            borderColor: '#2a3045',
                            borderWidth: 1,
                            callbacks: { label: ctx => ` ${ctx.parsed.y} students` }
                        }
                    },
                    scales: {
                        x: { ticks: { color: '#7a85a0', font: { size: 11 } }, grid: { color: 'rgba(255,255,255,0.04)' } },
                        y: { beginAtZero: true, ticks: { color: '#7a85a0', font: { size: 11 }, precision: 0 }, grid: { color: 'rgba(255,255,255,0.06)' } }
                    }
                }
            });
        });
    </script>

</x-app-layout>