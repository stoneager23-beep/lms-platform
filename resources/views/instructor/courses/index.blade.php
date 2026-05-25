<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-white tracking-tight">My Courses</h2>
                <p class="text-xs text-slate-500 mt-0.5">Manage and monitor your educational content</p>
            </div>
            <a href="{{ route('instructor.courses.create') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold
                      bg-indigo-600 hover:bg-indigo-500 text-white shadow-lg shadow-indigo-600/25
                      transition-all duration-200 hover:-translate-y-0.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                New Course
            </a>
        </div>
    </x-slot>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg:      #080c14;
            --surf:    #0d1220;
            --surf2:   #111827;
            --surf3:   #1a2235;
            --border:  rgba(255,255,255,0.07);
            --border2: rgba(255,255,255,0.04);
            --accent:  #818cf8;
            --green:   #34d399;
            --amber:   #fbbf24;
            --text:    #f1f5f9;
            --muted:   #64748b;
            --muted2:  #94a3b8;
        }

        .courses-wrap {
            background: var(--bg);
            min-height: 100vh;
            padding: 2rem 1.5rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text);
        }
        .courses-inner { max-width: 1300px; margin: 0 auto; }

        /* Flash */
        .flash { padding: .85rem 1.2rem; border-radius: 12px; font-size: .82rem; font-weight: 500; margin-bottom: 1.5rem; }
        .flash-success { background: rgba(52,211,153,.07); border: 1px solid rgba(52,211,153,.18); color: #34d399; }
        .flash-error   { background: rgba(248,113,113,.07); border: 1px solid rgba(248,113,113,.18); color: #f87171; }

        /* Summary bar */
        .summary {
            display: flex; align-items: center; gap: 1.5rem;
            background: var(--surf);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: .9rem 1.4rem;
            margin-bottom: 1.75rem;
        }
        .sum-item { display: flex; align-items: center; gap: .5rem; font-size: .8rem; color: var(--muted2); }
        .sum-val { font-size: 1rem; font-weight: 800; font-family: 'DM Mono', monospace; color: var(--text); }
        .sum-div { width: 1px; height: 20px; background: var(--border); }

        /* Grid */
        .grid-courses {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
            gap: 1.1rem;
        }

        /* Card */
        .course-card {
            background: var(--surf);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform .2s, border-color .25s, box-shadow .25s;
            position: relative;
        }
        .course-card:hover {
            transform: translateY(-4px);
            border-color: rgba(129,140,248,.3);
            box-shadow: 0 16px 40px rgba(0,0,0,.4), 0 0 0 1px rgba(129,140,248,.08);
        }

        /* Card accent line */
        .card-accent {
            height: 3px;
            background: linear-gradient(90deg, #6366f1, #8b5cf6);
        }
        .card-accent.draft { background: linear-gradient(90deg, #f59e0b, #ef4444); }

        /* Thumbnail */
        .card-thumb { width: 100%; height: 150px; object-fit: cover; }
        .card-thumb-placeholder {
            width: 100%; height: 150px;
            background: linear-gradient(135deg, #111827 0%, #1e1b4b 100%);
            display: flex; align-items: center; justify-content: center;
            font-size: 3rem;
        }

        /* Card body */
        .card-body { padding: 1.1rem 1.25rem; flex: 1; display: flex; flex-direction: column; gap: .75rem; }

        /* Title row */
        .card-title-row { display: flex; align-items: flex-start; justify-content: space-between; gap: .5rem; }
        .card-title { font-size: .95rem; font-weight: 800; line-height: 1.3; flex: 1; }
        .pill-live  { font-size: .65rem; font-weight: 800; padding: .22rem .65rem; border-radius: 99px; background: rgba(52,211,153,.1);  color: #34d399; border: 1px solid rgba(52,211,153,.2);  white-space: nowrap; flex-shrink: 0; }
        .pill-draft { font-size: .65rem; font-weight: 800; padding: .22rem .65rem; border-radius: 99px; background: rgba(251,191,36,.1); color: #fbbf24; border: 1px solid rgba(251,191,36,.2); white-space: nowrap; flex-shrink: 0; }

        /* Description */
        .card-desc { font-size: .78rem; color: var(--muted2); line-height: 1.55; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }

        /* Stats row */
        .card-stats { display: flex; gap: .6rem; }
        .stat-box {
            flex: 1;
            background: var(--surf2);
            border: 1px solid var(--border2);
            border-radius: 10px;
            padding: .6rem .5rem;
            text-align: center;
        }
        .stat-box-label { font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; color: var(--muted); margin-bottom: .2rem; }
        .stat-box-val { font-size: 1.1rem; font-weight: 800; font-family: 'DM Mono', monospace; color: var(--text); }

        /* Actions */
        .card-actions { display: flex; gap: .6rem; margin-top: auto; }
        .btn-edit {
            flex: 1; text-align: center; padding: .6rem; border-radius: 9px;
            font-size: .78rem; font-weight: 700;
            background: var(--surf3); border: 1px solid var(--border);
            color: var(--muted2); text-decoration: none;
            transition: background .2s, color .2s, border-color .2s;
        }
        .btn-edit:hover { background: rgba(255,255,255,.08); color: var(--text); border-color: rgba(255,255,255,.12); }
        .btn-lessons {
            flex: 1; text-align: center; padding: .6rem; border-radius: 9px;
            font-size: .78rem; font-weight: 700;
            background: rgba(99,102,241,.15); border: 1px solid rgba(99,102,241,.25);
            color: var(--accent); text-decoration: none;
            transition: background .2s, border-color .2s;
        }
        .btn-lessons:hover { background: rgba(99,102,241,.25); border-color: rgba(99,102,241,.4); }

        /* Empty state */
        .empty {
            text-align: center;
            background: var(--surf);
            border: 1px dashed rgba(255,255,255,.08);
            border-radius: 20px;
            padding: 5rem 2rem;
        }
        .empty-icon { font-size: 3rem; margin-bottom: 1rem; }
        .empty-title { font-size: 1.1rem; font-weight: 800; color: var(--text); margin-bottom: .4rem; }
        .empty-sub { font-size: .85rem; color: var(--muted); margin-bottom: 1.5rem; }
        .btn-create-empty {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .7rem 1.5rem; border-radius: 12px;
            font-size: .85rem; font-weight: 700;
            background: var(--accent); color: #fff; text-decoration: none;
            transition: opacity .2s;
        }
        .btn-create-empty:hover { opacity: .85; }

        @media (max-width: 640px) {
            .summary { flex-wrap: wrap; gap: .75rem; }
            .sum-div { display: none; }
        }
    </style>

    <div class="courses-wrap">
        <div class="courses-inner">

            {{-- Flash --}}
            @if(session('success'))
                <div class="flash flash-success">✓ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash flash-error">✕ {{ session('error') }}</div>
            @endif

            @if($courses->isEmpty())
                <div class="empty">
                    <div class="empty-icon">📚</div>
                    <div class="empty-title">No courses yet</div>
                    <div class="empty-sub">Start your journey by creating your first course today.</div>
                    <a href="{{ route('instructor.courses.create') }}" class="btn-create-empty">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create First Course
                    </a>
                </div>
            @else
                {{-- Summary bar --}}
                <div class="summary">
                    <div class="sum-item">
                        <span class="sum-val">{{ $courses->count() }}</span>
                        <span>Total Courses</span>
                    </div>
                    <div class="sum-div"></div>
                    <div class="sum-item">
                        <span class="sum-val" style="color:var(--green)">{{ $courses->where('status','published')->count() }}</span>
                        <span>Published</span>
                    </div>
                    <div class="sum-div"></div>
                    <div class="sum-item">
                        <span class="sum-val" style="color:var(--amber)">{{ $courses->where('status','draft')->count() }}</span>
                        <span>Drafts</span>
                    </div>
                    <div class="sum-div"></div>
                    <div class="sum-item">
                        <span class="sum-val" style="color:var(--accent)">{{ $courses->sum(fn($c) => $c->students->count()) }}</span>
                        <span>Total Students</span>
                    </div>
                </div>

                {{-- Course Grid --}}
                <div class="grid-courses">
                    @foreach($courses as $course)
                        <div class="course-card">
                            <div class="card-accent {{ $course->status === 'published' ? '' : 'draft' }}"></div>

                            {{-- Thumbnail --}}
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="{{ $course->title }}" class="card-thumb">
                            @else
                                <div class="card-thumb-placeholder">
                                    {{ $course->status === 'published' ? '🎓' : '✏️' }}
                                </div>
                            @endif

                            <div class="card-body">
                                {{-- Title + status --}}
                                <div class="card-title-row">
                                    <div class="card-title">{{ $course->title }}</div>
                                    @if($course->status === 'published')
                                        <span class="pill-live">Live</span>
                                    @else
                                        <span class="pill-draft">Draft</span>
                                    @endif
                                </div>

                                {{-- Description --}}
                                <div class="card-desc">{{ $course->description ?? 'No description provided.' }}</div>

                                {{-- Stats --}}
                                <div class="card-stats">
                                    <div class="stat-box">
                                        <div class="stat-box-label">Lessons</div>
                                        <div class="stat-box-val">{{ $course->lessons->count() }}</div>
                                    </div>
                                    <div class="stat-box">
                                        <div class="stat-box-label">Students</div>
                                        <div class="stat-box-val">{{ $course->students->count() }}</div>
                                    </div>
                                    <div class="stat-box">
                                        <div class="stat-box-label">Status</div>
                                        <div class="stat-box-val" style="font-size:.7rem;font-family:'Plus Jakarta Sans',sans-serif;color:{{ $course->status === 'published' ? '#34d399' : '#fbbf24' }}">
                                            {{ ucfirst($course->status) }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                <div class="card-actions">
                                    <a href="{{ route('instructor.courses.edit', $course) }}" class="btn-edit">
                                        ✏ Edit
                                    </a>
                                    <a href="{{ route('instructor.courses.lessons.index', $course) }}" class="btn-lessons">
                                        📋 Lessons
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

</x-app-layout>