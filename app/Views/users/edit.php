<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="mb-8 flex items-center">
    <a href="<?= base_url('users') ?>" class="mr-4 text-gray-400 hover:text-primary transition-colors">
        <i class="fa-solid fa-arrow-left text-xl"></i>
    </a>
    <div>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Edit User</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Update user information</p>
    </div>
</div>

<div class="glass rounded-2xl shadow-sm overflow-hidden max-w-2xl mb-12">
    <div class="p-6 bg-white dark:bg-dark-panel bg-opacity-50 dark:bg-opacity-50">
        <form action="<?= base_url('users/update/' . $user['id']) ?>" method="POST" class="space-y-6">
            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username <span class="text-red-500">*</span></label>
                    <input type="text" name="username" value="<?= esc($user['username']) ?>" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password <span class="text-gray-400 text-xs">(Leave blank to keep current)</span></label>
                    <input type="password" name="password" placeholder="Enter new password" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-dark-border focus:ring-2 focus:ring-primary focus:border-primary transition-colors bg-white dark:bg-gray-800 dark:text-white">
                </div>
            </div>

            <div class="pt-4 flex justify-end">
                <a href="<?= base_url('users') ?>" class="px-6 py-2 text-gray-600 dark:text-gray-400 font-medium hover:text-gray-800 dark:hover:text-gray-200 transition-colors mr-4">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium shadow-md hover:shadow-lg transition-all flex items-center">
                    <i class="fa-solid fa-save mr-2"></i> Update User
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
