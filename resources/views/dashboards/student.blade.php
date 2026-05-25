<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight" style="color:#e2e8f0">
            Student Dashboard
        </h2>
    </x-slot>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --bg:        #0f1117;
            --surface:   #181c27;
            --surface2:  #1e2333;
            --border:    #2a3045;
            --accent:    #6c8cff;
            --accent2:   #38e2b8;
            --accent3:   #f97316;
            --text:      #e2e8f0;
            --muted:     #7a85a0;
            --card-r:    16px;
        }

        .dash-wrap { background: var(--bg); min-height: 100vh; padding: 2rem 1.5rem; font-family: 'Plus Jakarta Sans', sans-serif; color: var(--text); }

        /* ── HERO BANNER ── */
        .hero {
            background: linear-gradient(135deg, #1a2040 0%, #0f1117 60%);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 2rem 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.75rem;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 220px; height: 220px;
            background: radial-gradient(circle, rgba(108,140,255,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        .hero-greeting { font-size: .8rem; color: var(--muted); letter-spacing: .08em; text-transform: uppercase; }
        .hero-name { font-size: 2rem; font-weight: 800; margin: .2rem 0 .4rem; background: linear-gradient(90deg, #fff, var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-sub { font-size: .85rem; color: var(--muted); }
        .hero-badge {
            background: linear-gradient(135deg, var(--accent), #4f6fd4);
            border-radius: 12px;
            padding: .6rem 1.2rem;
            font-size: .78rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: .04em;
            white-space: nowrap;
        }

        /* ── STAT CARDS ── */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; margin-bottom: 1.75rem; }
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--card-r);
            padding: 1.25rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: transform .2s, border-color .2s;
        }
        .stat-card:hover { transform: translateY(-3px); border-color: var(--accent); }
        .stat-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }
        .stat-val { font-size: 1.7rem; font-weight: 800; line-height: 1; }
        .stat-label { font-size: .75rem; color: var(--muted); margin-top: .25rem; font-weight: 500; }

        /* ── SECTION TITLE ── */
        .sec-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .sec-title { font-size: 1rem; font-weight: 700; letter-spacing: .02em; }
        .sec-link { font-size: .8rem; color: var(--accent); text-decoration: none; font-weight: 600; }
        .sec-link:hover { text-decoration: underline; }

        /* ── COURSE CARDS ── */
        .courses-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.1rem; margin-bottom: 1.75rem; }
        .course-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--card-r);
            overflow: hidden;
            display: flex; flex-direction: column;
            transition: transform .2s, box-shadow .2s;
        }
        .course-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.4); }
        .course-thumb { width: 100%; height: 130px; object-fit: cover; }
        .course-thumb-placeholder {
            width: 100%; height: 130px;
            background: linear-gradient(135deg, #1e2a50, #2a1e50);
            display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem;
        }
        .course-body { padding: 1rem; flex: 1; display: flex; flex-direction: column; }
        .course-title { font-size: .9rem; font-weight: 700; line-height: 1.35; margin-bottom: .25rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .course-instructor { font-size: .75rem; color: var(--muted); margin-bottom: .85rem; }
        .progress-row { display: flex; justify-content: space-between; font-size: .72rem; color: var(--muted); margin-bottom: .4rem; }
        .progress-pct { font-weight: 700; color: var(--accent); }
        .progress-bar-bg { background: var(--surface2); border-radius: 99px; height: 6px; overflow: hidden; margin-bottom: 1rem; }
        .progress-bar-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, var(--accent), var(--accent2)); transition: width .6s ease; }
        .course-btn {
            margin-top: auto;
            display: block; text-align: center;
            background: var(--surface2);
            border: 1px solid var(--border);
            color: var(--text);
            font-size: .8rem; font-weight: 700;
            padding: .6rem;
            border-radius: 10px;
            text-decoration: none;
            transition: background .2s, border-color .2s, color .2s;
        }
        .course-btn:hover, .course-btn.complete { background: var(--accent); border-color: var(--accent); color: #fff; }

        /* ── BOTTOM GRID ── */
        .bottom-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.1rem; }
        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--card-r);
            padding: 1.25rem 1.5rem;
        }
        .panel-title { font-size: .88rem; font-weight: 700; margin-bottom: 1rem; }

        /* Activity list */
        .activity-list { list-style: none; padding: 0; margin: 0; }
        .activity-item { display: flex; gap: .75rem; align-items: flex-start; padding: .65rem 0; border-bottom: 1px solid var(--border); }
        .activity-item:last-child { border-bottom: none; }
        .activity-dot { width: 28px; height: 28px; border-radius: 50%; background: rgba(56,226,184,.12); color: var(--accent2); display: flex; align-items: center; justify-content: center; font-size: .75rem; flex-shrink: 0; margin-top: 2px; }
        .activity-lesson { font-size: .82rem; font-weight: 600; }
        .activity-meta { font-size: .72rem; color: var(--muted); margin-top: .15rem; }

        /* Quiz list */
        .quiz-item { display: flex; justify-content: space-between; align-items: center; padding: .65rem 0; border-bottom: 1px solid var(--border); gap: .5rem; }
        .quiz-item:last-child { border-bottom: none; }
        .quiz-name { font-size: .82rem; font-weight: 600; }
        .quiz-meta { font-size: .72rem; color: var(--muted); margin-top: .15rem; }
        .quiz-btn {
            flex-shrink: 0;
            background: rgba(249,115,22,.15);
            color: var(--accent3);
            border: 1px solid rgba(249,115,22,.3);
            font-size: .72rem; font-weight: 700;
            padding: .35rem .8rem;
            border-radius: 8px;
            text-decoration: none;
            transition: background .2s;
        }
        .quiz-btn:hover { background: var(--accent3); color: #fff; }

        /* Empty states */
        .empty { text-align: center; padding: 2rem 0; color: var(--muted); font-size: .85rem; }
        .empty-icon { font-size: 2rem; margin-bottom: .5rem; }

        /* Chart panel */
        .chart-wrap { position: relative; height: 180px; }

        /* Responsive */
        @media (max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .courses-grid { grid-template-columns: repeat(2, 1fr); }
            .bottom-grid { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .courses-grid { grid-template-columns: 1fr; }
            .hero { flex-direction: column; align-items: flex-start; gap: 1rem; }
        }
    </style>

    <div class="dash-wrap">
        <div style="max-width:1280px; margin:0 auto;">

            {{-- ── HERO ── --}}
            <div class="hero">
                <div>
                    <div class="hero-greeting">Welcome back</div>
                    <div class="hero-name">{{ Auth::user()->name }} 👋</div>
                    <div class="hero-sub">Track your progress, complete your courses, ace your quizzes.</div>
                </div>
                <div class="hero-badge">🎓 Student Portal</div>
            </div>

            {{-- ── STATS ── --}}
            <div class="stats-grid">
                @php
                    $statCards = [
                        ['icon'=>'📚','bg'=>'rgba(108,140,255,.12)','val'=>$totalCourses,    'label'=>'Enrolled Courses'],
                        ['icon'=>'✅','bg'=>'rgba(56,226,184,.12)', 'val'=>$completedCourses,'label'=>'Completed'],
                        ['icon'=>'📈','bg'=>'rgba(249,115,22,.12)', 'val'=>$avgProgress.'%', 'label'=>'Avg Progress'],
                        ['icon'=>'🏆','bg'=>'rgba(234,179,8,.12)',  'val'=>$quizzesPassed,   'label'=>'Quizzes Passed'],
                    ];
                @endphp
                @foreach ($statCards as $s)
                    <div class="stat-card">
                        <div class="stat-icon" style="background:{{ $s['bg'] }}">{{ $s['icon'] }}</div>
                        <div>
                            <div class="stat-val">{{ $s['val'] }}</div>
                            <div class="stat-label">{{ $s['label'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- {{-- ── MY COURSES ── --}}
            <div class="sec-header">
                <div class="sec-title">My Courses</div>
                <a href="{{ route('student.courses.index') }}" class="sec-link">View all →</a>
            </div>

            @if ($enrolledCourses->isEmpty())
                <div class="panel" style="margin-bottom:1.75rem">
                    <div class="empty">
                        <div class="empty-icon">📭</div>
                        You haven't enrolled in any courses yet.
                        <br><a href="{{ route('student.courses.index') }}" class="sec-link" style="margin-top:.5rem;display:inline-block">Browse courses →</a>
                    </div>
                </div>
            @else
                <div class="courses-grid">
                    @foreach ($enrolledCourses as $course)
                        <div class="course-card">
                            @if ($course->thumbnail)
                                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" class="course-thumb">
                            @else
                                <div class="course-thumb-placeholder">📖</div>
                            @endif
                            <div class="course-body">
                                <div class="course-title">{{ $course->title }}</div>
                                <div class="course-instructor">by {{ $course->instructor->name ?? 'Instructor' }}</div>
                                <div class="progress-row">
                                    <span>Progress</span>
                                    <span class="progress-pct">{{ $course->progress_percent }}%</span>
                                </div>
                                <div class="progress-bar-bg">
                                    <div class="progress-bar-fill" style="width:{{ $course->progress_percent }}%"></div>
                                </div>
                                <a href="{{ route('student.lessons.index') }}?course={{ $course->id }}"
                                   class="course-btn {{ $course->progress_percent === 100 ? 'complete' : '' }}">
                                    {{ $course->progress_percent === 100 ? '🎉 Review Course' : '▶ Continue Learning' }}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif -->

            {{-- ── BOTTOM GRID ── --}}
            <div class="bottom-grid">

                {{-- Progress Chart --}}
                <div class="panel">
                    <div class="panel-title">📊 Course Progress Overview</div>
                    <div class="chart-wrap">
                        <canvas id="progressChart"></canvas>
                    </div>
                </div>

                {{-- Recent Activity --}}
                <div class="panel">
                    <div class="panel-title">⚡ Recent Activity</div>
                    @if ($recentActivity->isEmpty())
                        <div class="empty"><div class="empty-icon">🌱</div>No activity yet. Start a lesson!</div>
                    @else
                        <ul class="activity-list">
                            @foreach ($recentActivity as $a)
                                <li class="activity-item">
                                    <div class="activity-dot">✓</div>
                                    <div>
                                        <div class="activity-lesson">{{ $a->lesson->title ?? 'Lesson' }}</div>
                                        <div class="activity-meta">{{ $a->lesson->course->title ?? '' }} · {{ $a->created_at->diffForHumans() }}</div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                {{-- Upcoming Quizzes --}}
                <div class="panel">
                    <div class="panel-title">📝 Upcoming Quizzes</div>
                    @if ($upcomingQuizzes->isEmpty())
                        <div class="empty"><div class="empty-icon">🎉</div>No pending quizzes!</div>
                    @else
                        @foreach ($upcomingQuizzes as $quiz)
                            <div class="quiz-item">
                                <div>
                                    <div class="quiz-name">{{ $quiz->title }}</div>
                                    <div class="quiz-meta">{{ $quiz->lesson->course->title ?? '' }} · Pass: {{ $quiz->pass_mark }}%</div>
                                </div>
                                <a href="{{ route('student.lessons.show', $quiz->lesson_id) }}" class="quiz-btn">Take →</a>
                            </div>
                        @endforeach
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- ── CHART JS ── --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const labels = @json($enrolledCourses->pluck('title')->map(fn($t) => strlen($t) > 18 ? substr($t, 0, 18).'…' : $t));
            const data   = @json($enrolledCourses->pluck('progress_percent'));

            const ctx = document.getElementById('progressChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Progress %',
                        data,
                        backgroundColor: data.map(v =>
                            v === 100 ? 'rgba(56,226,184,0.75)' : 'rgba(108,140,255,0.7)'
                        ),
                        borderColor: data.map(v =>
                            v === 100 ? '#38e2b8' : '#6c8cff'
                        ),
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
                            callbacks: {
                                label: ctx => ` ${ctx.parsed.y}% complete`
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { color: '#7a85a0', font: { size: 11 } },
                            grid: { color: 'rgba(255,255,255,0.04)' }
                        },
                        y: {
                            min: 0, max: 100,
                            ticks: { color: '#7a85a0', font: { size: 11 }, callback: v => v + '%' },
                            grid: { color: 'rgba(255,255,255,0.06)' }
                        }
                    }
                }
            });
        });
    </script>

</x-app-layout>