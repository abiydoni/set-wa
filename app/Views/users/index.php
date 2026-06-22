<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-end mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">Users</h1>
        <p class="text-gray-500 dark:text-gray-400 mt-1">Manage system users and access</p>
    </div>
    <a href="<?= base_url('users/create') ?>" class="px-6 py-3 bg-primary hover:bg-blue-600 text-white rounded-xl font-medium shadow-md hover:shadow-lg transition-all flex items-center">
        <i class="fa-solid fa-plus mr-2"></i> Add User
    </a>
</div>

<div class="glass rounded-2xl shadow-sm overflow-hidden mb-12">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-dark-border text-gray-600 dark:text-gray-400 text-sm">
                    <th class="p-4 font-semibold w-16">ID</th>
                    <th class="p-4 font-semibold">Username</th>
                    <th class="p-4 font-semibold text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-dark-border bg-white dark:bg-dark-panel bg-opacity-50 dark:bg-opacity-50">
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="3" class="p-8 text-center text-gray-500 dark:text-gray-400">
                        <i class="fa-solid fa-users text-4xl mb-3 text-gray-300 dark:text-gray-600 block"></i>
                        No users found.
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group">
                        <td class="p-4 font-bold text-gray-800 dark:text-gray-200">#<?= esc($user['id']) ?></td>
                        <td class="p-4 font-medium text-gray-800 dark:text-gray-200">
                            <?= esc($user['username']) ?>
                            <?php if (session()->get('id') == $user['id']): ?>
                                <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-700 text-xs font-bold rounded-md">You</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 text-center space-x-2">
                            <a href="<?= base_url('users/edit/'.$user['id']) ?>" class="text-blue-500 hover:text-blue-600 dark:text-blue-400 dark:hover:text-blue-300 p-2 transition-colors" title="Edit User">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <?php if (session()->get('id') == $user['id']): ?>
                                <button type="button" class="text-gray-400 p-2 cursor-not-allowed opacity-50" title="Cannot delete your own account">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            <?php else: ?>
                                <a href="<?= base_url('users/delete/'.$user['id']) ?>" class="text-red-400 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300 p-2 transition-colors" hx-confirm="Are you sure you want to delete this user?" title="Delete User">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
