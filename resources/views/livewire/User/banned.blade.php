<div class="min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <!-- Main Ban Notice -->
        <div
            class="bg-gradient-to-br from-red-900/40 to-red-800/30 backdrop-blur-xl border border-red-500/30 rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-red-500/20 px-8 py-6 border-b border-red-500/30">
                <div class="flex items-center justify-center space-x-3">
                    <div class="w-12 h-12 bg-red-500/30 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <div class="text-center">
                        <h1 class="text-2xl font-bold text-white">Account Suspended</h1>
                        <p class="text-red-200 text-sm">Your account has been temporarily suspended</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="px-8 py-8 space-y-6">
                <!-- Reason Section -->
                <div class="bg-gray-800/40 rounded-xl p-6 border border-gray-700/50">
                    <h2 class="text-lg font-semibold text-white mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        Reason for Suspension
                    </h2>
                    <div class="bg-gray-900/50 rounded-lg p-4 border border-gray-600/30">
                        <p class="text-gray-200 leading-relaxed">{{ $reason }}</p>
                    </div>
                </div>

                <!-- Information Section -->
                <div class="bg-blue-900/20 rounded-xl p-6 border border-blue-500/30">
                    <h3 class="text-lg font-semibold text-blue-200 mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        What This Means
                    </h3>
                    <ul class="text-blue-100 space-y-2 text-sm">
                        <li class="flex items-start">
                            Your account access has been temporarily restricted
                        </li>
                        <li class="flex items-start">
                            You cannot create posts, comments, or interact with other users
                        </li>
                        <li class="flex items-start">
                            This suspension may be temporary or permanent depending on the violation
                        </li>
                    </ul>
                </div>

                <!-- Contact Section -->
                <div class="bg-green-900/20 rounded-xl p-6 border border-green-500/30">
                    <h3 class="text-lg font-semibold text-green-200 mb-3 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 mr-2" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Need Help?
                    </h3>
                    <div class="space-y-3">
                        <p class="text-green-100 text-sm">
                            If you believe this suspension was made in error or would like to appeal this decision,
                            please contact our moderation team.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="mailto:zander@zanderlewis.dev"
                                class="inline-flex items-center justify-center px-4 py-2 bg-green-600/80 hover:bg-green-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Contact Support
                            </a>
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center justify-center px-4 py-2 bg-gray-600/80 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to Login
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-gray-400 text-sm">
                &copy; 2025 Postaverse. Please review our
                <a href="{{ route('terms.show') }}"
                    class="text-indigo-400 hover:text-indigo-300 transition-colors">Community Guidelines</a>
            </p>
        </div>
    </div>
</div>
