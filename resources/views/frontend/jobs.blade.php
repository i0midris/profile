@extends('frontend.layouts.app')

@section('title', __('frontend.jobs') . ' - ' . ($settings->company_name ?? __('frontend.company_fallback')))
@section('meta_description', __('frontend.jobs_page_description'))

@section('content')
    <section class="py-24 relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-1/4 {{ app()->isLocale('ar') ? 'right-0' : 'left-0' }} w-96 h-96 bg-primary-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 {{ app()->isLocale('ar') ? 'left-0' : 'right-0' }} w-96 h-96 bg-accent-500/10 rounded-full blur-3xl"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center" data-aos="fade-up">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">{{ __('frontend.join_our_team') }}</h1>
                <p class="text-white/60 max-w-2xl mx-auto">{{ __('frontend.jobs_page_description') }}</p>
            </div>
        </div>
    </section>

    <section class="pb-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-12">
                <div class="lg:col-span-2 space-y-6">
                    <h2 class="text-2xl md:text-3xl font-bold text-white" data-aos="fade-up">{{ __('frontend.open_positions') }}</h2>

                    @forelse($jobs as $index => $job)
                        <article class="global-card rounded-2xl p-6 md:p-8" data-aos="fade-up" data-aos-delay="{{ $index * 80 }}">
                            <div class="flex flex-wrap items-start justify-between gap-3 mb-5">
                                <div>
                                    <h3 class="global-card-title text-2xl font-bold text-white">{{ $job->title }}</h3>
                                    <div class="mt-2 flex flex-wrap items-center gap-2 text-sm">
                                        @if($job->department)
                                            <span class="px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 text-white/70">{{ $job->department }}</span>
                                        @endif
                                        @if($job->location)
                                            <span class="px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 text-white/70">{{ $job->location }}</span>
                                        @endif
                                        @if($job->employment_type)
                                            <span class="px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 text-white/70">{{ $job->employment_type }}</span>
                                        @endif
                                        @if($job->experience_level)
                                            <span class="px-2.5 py-1 rounded-lg bg-white/5 border border-white/10 text-white/70">{{ $job->experience_level }}</span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('jobs', ['locale' => app()->getLocale(), 'job' => $job->id]) }}#apply-form"
                                    class="inline-flex items-center px-4 py-2 rounded-xl gradient-primary text-white text-sm font-semibold hover:shadow-lg hover:shadow-primary-500/30 transition-all">
                                    {{ __('frontend.apply_now') }}
                                </a>
                            </div>

                            <p class="global-card-copy text-white/70 leading-relaxed mb-5">{{ $job->description }}</p>

                            @if(!empty($job->requirements))
                                <div>
                                    <h4 class="text-white font-semibold mb-3">{{ __('frontend.job_requirements') }}</h4>
                                    <ul class="space-y-2 text-white/70">
                                        @foreach($job->requirements as $requirement)
                                            <li class="global-card-item flex items-start gap-2">
                                                <svg class="w-4 h-4 mt-1 text-accent-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span>{{ $requirement }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </article>
                    @empty
                        <div class="glass rounded-2xl p-8 text-center text-white/60" data-aos="fade-up">
                            {{ __('frontend.no_open_positions') }}
                        </div>
                    @endforelse
                </div>

                <div class="lg:col-span-1" id="apply-form" data-aos="fade-left">
                    <div class="glass rounded-2xl p-6 md:p-8 sticky top-24">
                        <h2 class="text-2xl font-bold text-white mb-2">{{ __('frontend.apply_for_job') }}</h2>
                        <p class="text-white/60 mb-6">{{ __('frontend.apply_form_hint') }}</p>

                        @if(session('success'))
                            <div class="mb-6 p-4 rounded-lg bg-green-500/20 border border-green-500/30 text-green-300 text-sm">
                                {{ session('success') }}
                            </div>
                        @endif

                        @php
                            $initialJobValue = (string) old('job_opening_id', $selectedJobId ?? '');
                            $showDesiredPosition = $initialJobValue === '';
                        @endphp

                        <form method="POST" action="{{ route('jobs.apply', ['locale' => app()->getLocale()]) }}" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            <div>
                                <label for="job_opening_id" class="block text-sm font-medium text-white/80 mb-2">{{ __('frontend.job_position') }}</label>
                                <select id="job_opening_id" name="job_opening_id"
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all">
                                    <option value="" {{ $showDesiredPosition ? 'selected' : '' }} class="text-black">{{ __('frontend.job_not_listed') }}</option>
                                    @foreach($jobs as $job)
                                        <option value="{{ $job->id }}" {{ $initialJobValue === (string) $job->id ? 'selected' : '' }} class="text-black">
                                            {{ $job->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="desired_position_group" class="{{ $showDesiredPosition ? '' : 'hidden' }}">
                                <label for="desired_position" class="block text-sm font-medium text-white/80 mb-2">{{ __('frontend.desired_position') }} <span id="desired_position_required_mark" class="text-red-400">*</span></label>
                                <input type="text" id="desired_position" name="desired_position" value="{{ old('desired_position') }}" {{ $showDesiredPosition ? 'required' : '' }}
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-white/40 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all"
                                    placeholder="{{ __('frontend.desired_position_placeholder') }}">
                            </div>

                            <div>
                                <label for="name" class="block text-sm font-medium text-white/80 mb-2">{{ __('frontend.your_name') }} <span class="text-red-400">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-white/40 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all"
                                    placeholder="{{ __('frontend.placeholder_name') }}">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-white/80 mb-2">{{ __('frontend.email_address') }} <span class="text-red-400">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-white/40 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all"
                                    placeholder="{{ __('frontend.placeholder_email') }}">
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-white/80 mb-2">{{ __('frontend.phone_number') }}</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-white/40 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all"
                                    placeholder="{{ __('frontend.placeholder_phone') }}">
                            </div>

                            <div>
                                <label for="cover_letter" class="block text-sm font-medium text-white/80 mb-2">{{ __('frontend.cover_letter') }} <span class="text-red-400">*</span></label>
                                <textarea id="cover_letter" name="cover_letter" rows="5" required
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-white/40 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all resize-none"
                                    placeholder="{{ __('frontend.cover_letter_placeholder') }}">{{ old('cover_letter') }}</textarea>
                            </div>

                            <div>
                                <label for="cv" class="block text-sm font-medium text-white/80 mb-2">{{ __('frontend.cv_resume') }} <span class="text-red-400">*</span></label>
                                <input type="file" id="cv" name="cv" required accept=".pdf,.doc,.docx"
                                    class="w-full px-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white file:mr-3 file:px-3 file:py-1.5 file:rounded-lg file:border-0 file:bg-white/10 file:text-white file:text-sm hover:file:bg-white/20 focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all">
                                <p class="mt-2 text-xs text-white/50">{{ __('frontend.cv_allowed_types') }}</p>
                            </div>

                            <button type="submit"
                                class="w-full py-3.5 rounded-xl gradient-primary text-white font-semibold hover:shadow-lg hover:shadow-primary-500/30 transition-all duration-300">
                                {{ __('frontend.submit_application') }}
                            </button>
                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                var jobSelect = document.getElementById('job_opening_id');
                                var desiredPositionGroup = document.getElementById('desired_position_group');
                                var desiredPositionInput = document.getElementById('desired_position');
                                var desiredPositionRequiredMark = document.getElementById('desired_position_required_mark');

                                if (!jobSelect || !desiredPositionGroup || !desiredPositionInput || !desiredPositionRequiredMark) {
                                    return;
                                }

                                function toggleDesiredPositionField() {
                                    var isJobNotListed = jobSelect.value === '';
                                    desiredPositionGroup.classList.toggle('hidden', !isJobNotListed);
                                    desiredPositionInput.required = isJobNotListed;
                                    desiredPositionRequiredMark.classList.toggle('hidden', !isJobNotListed);
                                }

                                toggleDesiredPositionField();
                                jobSelect.addEventListener('change', toggleDesiredPositionField);
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
