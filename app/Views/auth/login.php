<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Login' ?></title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?= base_url('wagateway.png') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
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
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        html.dark .glass {
            background: rgba(30, 41, 59, 0.7);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
    </style>
    <!-- Dark mode initialization -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-gray-100 dark:bg-dark-bg flex items-center justify-center h-screen relative overflow-hidden transition-colors duration-300">
    <!-- Decorative blobs -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-blue-300 dark:bg-blue-900/40 rounded-full mix-blend-multiply dark:mix-blend-lighten filter blur-3xl opacity-50 animate-blob"></div>
    <div class="absolute top-0 left-0 w-96 h-96 bg-purple-300 dark:bg-purple-900/40 rounded-full mix-blend-multiply dark:mix-blend-lighten filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-20 w-96 h-96 bg-pink-300 dark:bg-pink-900/40 rounded-full mix-blend-multiply dark:mix-blend-lighten filter blur-3xl opacity-50 animate-blob animation-delay-4000"></div>

    <div class="glass w-full max-w-md p-8 rounded-3xl shadow-2xl relative z-10 m-4">
        <!-- Language Switcher in Login -->
        <div class="absolute top-4 right-4 flex gap-2">
            <a href="<?= base_url('lang/en') ?>" class="text-xs px-2 py-1 rounded <?= session()->get('lang') === 'en' || !session()->get('lang') ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300' ?>">EN</a>
            <a href="<?= base_url('lang/id') ?>" class="text-xs px-2 py-1 rounded <?= session()->get('lang') === 'id' ? 'bg-primary text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300' ?>">ID</a>
        </div>

        <div class="text-center mb-8 mt-4">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary text-white mb-4 shadow-lg shadow-blue-500/50">
                <i class="fa-brands fa-whatsapp text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white"><?= lang('App.welcome_back') ?></h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1"><?= lang('App.please_login') ?></p>
        </div>

        <?php if(session()->getFlashdata('error')):?>
            <div class="bg-red-100 dark:bg-red-900/30 border-l-4 border-red-500 text-red-700 dark:text-red-400 p-4 mb-6 rounded-lg text-sm" role="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif;?>

        <form action="<?= base_url('login/process') ?>" method="POST" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= lang('App.username') ?></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-user text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <input type="text" name="username" required class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white/50 dark:bg-gray-800/50 dark:text-white" placeholder="<?= lang('App.username') ?>">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?= lang('App.password') ?></label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fa-solid fa-lock text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <input type="password" name="password" required class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white/50 dark:bg-gray-800/50 dark:text-white" placeholder="••••••••">
                </div>
            </div>

            <button type="submit" class="w-full py-3 bg-primary hover:bg-blue-600 text-white rounded-xl font-medium shadow-md hover:shadow-lg transition-all flex justify-center items-center">
                <?= lang('App.sign_in') ?> <i class="fa-solid fa-arrow-right ml-2"></i>
            </button>
        </form>
    </div>
</body>
</html>
