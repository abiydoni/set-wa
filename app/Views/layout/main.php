<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'WA Gateway Dashboard' ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Check local storage for dark mode before rendering to prevent flash
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#1e293b',
                        accent: '#10b981',
                        dark: {
                            bg: '#0f172a',
                            panel: '#1e293b',
                            border: '#334155'
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- NProgress CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />

    <!-- CodeMirror CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/dracula.min.css">

    <!-- NProgress & HTMX & SweetAlert2 & CodeMirror -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <script src="https://unpkg.com/htmx.org@1.9.12"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/addon/edit/matchbrackets.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/clike/clike.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/php/php.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            NProgress.configure({ showSpinner: false, speed: 400, minimum: 0.2 });
            document.addEventListener('htmx:configRequest', function() { NProgress.start(); });
            document.addEventListener('htmx:afterSettle', function() { NProgress.done(); });
            document.addEventListener('htmx:sendError', function() { NProgress.done(); });
        });
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .dark .glass {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .sidebar-item { transition: all 0.3s ease; }
        .sidebar-item:hover { background-color: rgba(59, 130, 246, 0.1); color: #3b82f6; transform: translateX(5px); }
        .dark .sidebar-item:hover { background-color: rgba(59, 130, 246, 0.2); }
        .sidebar-item.active { background-color: #3b82f6; color: white; }
        .sidebar-item.active:hover { transform: none; }
        /* SweetAlert2 Dark Mode */
        .dark .swal2-popup {
            background-color: #1e293b !important;
            color: #f1f5f9 !important;
        }
        .dark .swal2-title {
            color: #f8fafc !important;
        }
        .dark .swal2-html-container {
            color: #cbd5e1 !important;
        }
        /* NProgress Customization */
        #nprogress .bar {
            background: #3b82f6 !important;
            height: 3px !important;
        }
        #nprogress .peg {
            box-shadow: 0 0 10px #3b82f6, 0 0 5px #3b82f6 !important;
        }
        /* Page Transition */
        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.98) translateY(10px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .page-transition {
            animation: fadeInScale 0.4s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        }
    </style>
</head>
<body hx-boost="true" class="text-gray-800 dark:text-gray-200 bg-[#f8fafc] dark:bg-dark-bg flex h-screen overflow-hidden transition-colors duration-300">

    <!-- Sidebar -->
    <aside class="w-64 bg-white dark:bg-dark-panel shadow-xl hidden md:flex flex-col z-20 transition-colors duration-300">
        <div class="p-6 flex items-center justify-center border-b border-gray-100 dark:border-dark-border">
            <div class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-primary to-accent">
                <i class="fa-brands fa-whatsapp text-accent mr-2"></i>WA Gateway
            </div>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <?php
            $currentUrl = current_url();
            $isApp = strpos($currentUrl, '/applications') !== false;
            $isSettings = strpos($currentUrl, '/settings') !== false;
            $isDash = !$isApp && !$isSettings;
            ?>
            <a href="<?= base_url() ?>" class="sidebar-item flex items-center px-4 py-3 rounded-xl font-medium <?= $isDash ? 'active text-white dark:text-white' : 'text-gray-600 dark:text-gray-400' ?>">
                <i class="fa-solid fa-chart-pie w-6"></i> <?= lang('App.dashboard') ?>
            </a>
            <a href="<?= base_url('applications') ?>" class="sidebar-item flex items-center px-4 py-3 rounded-xl font-medium <?= $isApp ? 'active text-white dark:text-white' : 'text-gray-600 dark:text-gray-400' ?>">
                <i class="fa-solid fa-layer-group w-6"></i> <?= lang('App.applications') ?>
            </a>
            <a href="<?= base_url('settings') ?>" class="sidebar-item flex items-center px-4 py-3 rounded-xl font-medium <?= $isSettings ? 'active text-white dark:text-white' : 'text-gray-600 dark:text-gray-400' ?>">
                <i class="fa-solid fa-gear w-6"></i> <?= lang('App.settings') ?>
            </a>
        </nav>
        <div class="p-4 border-t border-gray-100 dark:border-dark-border text-center text-sm text-gray-500 dark:text-gray-400">
            &copy; <?= date('Y') ?> Appsbee
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        <!-- Top Header -->
        <header class="bg-white/80 dark:bg-dark-panel/80 backdrop-blur-md shadow-sm p-4 flex justify-between items-center z-20 transition-colors duration-300 relative">
            <!-- Left Side -->
            <div class="flex items-center">
                <!-- Mobile Logo -->
                <div class="md:hidden text-xl font-bold text-primary mr-4">
                    <i class="fa-brands fa-whatsapp text-accent mr-2"></i>WA Gateway
                </div>
            </div>
            
            <!-- Right Side (Toggles) -->
            <div class="flex items-center space-x-3">
                <!-- Language Toggle -->
                <div class="flex bg-gray-100 dark:bg-gray-800 p-1 rounded-lg">
                    <a href="<?= base_url('lang/en') ?>" class="<?= service('request')->getLocale() == 'en' ? 'bg-primary text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' ?> px-2 py-1 rounded-md text-sm font-medium transition-all">EN</a>
                    <a href="<?= base_url('lang/id') ?>" class="<?= service('request')->getLocale() == 'id' ? 'bg-primary text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200' ?> px-2 py-1 rounded-md text-sm font-medium transition-all">ID</a>
                </div>

                <!-- Theme Toggle -->
                <button id="headerThemeToggleBtn" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:text-primary dark:hover:text-primary transition-colors focus:outline-none">
                    <i class="fa-solid fa-moon block dark:hidden text-lg w-5 text-center" title="Switch to Dark Mode"></i>
                    <i class="fa-solid fa-sun hidden dark:block text-lg w-5 text-center" title="Switch to Light Mode"></i>
                </button>

                <!-- Mobile Menu Button -->
                <button id="mobileMenuBtn" class="md:hidden p-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 focus:outline-none">
                    <i class="fa-solid fa-bars text-lg w-5 text-center"></i>
                </button>
            </div>
        </header>

        <!-- Mobile Menu Overlay -->
        <div id="mobileMenu" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-30 hidden">
            <div class="bg-white dark:bg-dark-panel w-64 h-full shadow-xl flex flex-col transform -translate-x-full transition-all duration-300" id="mobileSidebar">
                <div class="p-4 flex justify-end">
                    <button id="closeMenuBtn" class="text-gray-600 dark:text-gray-400"><i class="fa-solid fa-xmark text-2xl"></i></button>
                </div>
                <nav class="px-4 py-2 space-y-2">
            <a href="<?= base_url() ?>" class="sidebar-item flex items-center px-4 py-3 rounded-xl font-medium <?= $isDash ? 'active text-white dark:text-white' : 'text-gray-600 dark:text-gray-400' ?>">
                <i class="fa-solid fa-chart-pie w-6"></i> <?= lang('App.dashboard') ?>
            </a>
            <a href="<?= base_url('applications') ?>" class="sidebar-item flex items-center px-4 py-3 rounded-xl font-medium <?= $isApp ? 'active text-white dark:text-white' : 'text-gray-600 dark:text-gray-400' ?>">
                <i class="fa-solid fa-layer-group w-6"></i> <?= lang('App.applications') ?>
            </a>
            <a href="<?= base_url('settings') ?>" class="sidebar-item flex items-center px-4 py-3 rounded-xl font-medium <?= $isSettings ? 'active text-white dark:text-white' : 'text-gray-600 dark:text-gray-400' ?>">
                <i class="fa-solid fa-gear w-6"></i> <?= lang('App.settings') ?>
            </a>
                </nav>
            </div>
        </div>

        <!-- Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-8 relative">
            <!-- Decorative background blob -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-blue-100 opacity-50 blur-3xl z-0 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 rounded-full bg-green-100 opacity-50 blur-3xl z-0 pointer-events-none"></div>
            
            <div class="relative z-10 max-w-7xl mx-auto page-transition">
                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

    <script>
        (function() {
            // Unregister any leftover Service Workers from previous PWA experiments
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.getRegistrations().then(function(registrations) {
                    for(let registration of registrations) {
                        registration.unregister();
                        console.log('ServiceWorker unregistered.');
                    }
                });
            }

            // Mobile menu toggle
            const mobileMenuBtn = document.getElementById('mobileMenuBtn');
            const closeMenuBtn = document.getElementById('closeMenuBtn');
            const mobileMenu = document.getElementById('mobileMenu');
            const mobileSidebar = document.getElementById('mobileSidebar');

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', () => {
                    mobileMenu.classList.remove('hidden');
                    setTimeout(() => {
                        mobileSidebar.classList.remove('-translate-x-full');
                    }, 10);
                });
            }

            if (closeMenuBtn) {
                closeMenuBtn.addEventListener('click', () => {
                    mobileSidebar.classList.add('-translate-x-full');
                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                    }, 300);
                });
            }

            // Theme Toggle Logic
            const headerThemeToggleBtn = document.getElementById('headerThemeToggleBtn');
            
            function toggleTheme() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }
            
            if (headerThemeToggleBtn) headerThemeToggleBtn.addEventListener('click', toggleTheme);

            // Override HTMX Confirm to use SweetAlert2
            document.addEventListener('htmx:confirm', function(e) {
                if (!e.detail.question) return; // Hanya tangani jika ada attribute hx-confirm
                
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi',
                    text: e.detail.question,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    customClass: { popup: 'rounded-2xl' }
                }).then(function(result) {
                    if(result.isConfirmed) {
                        e.detail.issueRequest(true);
                    }
                });
            });

            // Flash message handling
            <?php if (session()->getFlashdata('success')) : ?>
                Swal.fire({
                    icon: 'success',
                    title: '<?= lang('App.success') ?>',
                    html: <?= json_encode(nl2br(session()->getFlashdata('success'))) ?>,
                    confirmButtonColor: '#3b82f6',
                    customClass: { popup: 'rounded-2xl' }
                });
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')) : ?>
                Swal.fire({
                    icon: 'error',
                    title: '<?= lang('App.error_oops') ?>',
                    html: <?= json_encode(nl2br(session()->getFlashdata('error'))) ?>,
                    confirmButtonColor: '#3b82f6',
                    customClass: { popup: 'rounded-2xl' }
                });
            <?php endif; ?>
        })();
    </script>
</body>
</html>
