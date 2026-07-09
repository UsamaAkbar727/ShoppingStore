<?php
include("../configshoppingstore.php");

$res_arr = [];
try {
    $stmt = $conn->query("SELECT * FROM `user` ORDER BY id DESC");
    $res_arr = $stmt->fetchAll();
} catch (Exception $e) {
    error_log($e->getMessage());
}
?>

<div class="space-y-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div class="space-y-2">
            <h2 class="font-serif text-5xl text-white">Client Directory</h2>
            <p class="text-gray-500 text-sm tracking-[0.2em] uppercase font-bold">Manage access and status of members</p>
        </div>
        <div class="px-6 py-3 glass-dark rounded-full flex items-center gap-4 border border-gold/20">
            <i class="fas fa-user-friends text-gold"></i>
            <span class="text-[10px] font-black uppercase tracking-widest text-white"><?php echo count($res_arr); ?> Members</span>
        </div>
    </div>

    <!-- Users Table -->
    <div class="glass-dark rounded-[40px] overflow-hidden luxury-shadow border border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/5 bg-white/5">
                        <th class="px-8 py-6 text-[10px] uppercase tracking-[0.3em] text-gold font-black">Member</th>
                        <th class="px-8 py-6 text-[10px] uppercase tracking-[0.3em] text-gold font-black">Identity</th>
                        <th class="px-8 py-6 text-[10px] uppercase tracking-[0.3em] text-gold font-black">Role</th>
                        <th class="px-8 py-6 text-[10px] uppercase tracking-[0.3em] text-gold font-black">Status</th>
                        <th class="px-8 py-6 text-[10px] uppercase tracking-[0.3em] text-gold font-black text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($res_arr as $user): ?>
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        <!-- Member Profile -->
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full border border-gold/20 p-0.5">
                                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['fullname']); ?>&background=1a1a1a&color=c5a059" 
                                         class="w-full h-full rounded-full object-cover">
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white"><?php echo htmlspecialchars($user['fullname']); ?></p>
                                    <p class="text-[10px] text-gray-500 font-medium">Joined <?php echo date('M Y'); ?></p>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Identity (Email) -->
                        <td class="px-8 py-6">
                            <p class="text-xs text-gray-400 font-medium"><?php echo htmlspecialchars($user['email']); ?></p>
                        </td>

                        <!-- Role Badge -->
                        <td class="px-8 py-6">
                            <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest <?php echo $user['role'] === 'admin' ? 'bg-gold/20 text-gold border border-gold/30' : 'bg-white/5 text-gray-400 border border-white/10'; ?>">
                                <?php echo strtoupper($user['role'] ?: 'user'); ?>
                            </span>
                        </td>

                        <!-- Status Badge -->
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full <?php echo $user['isBlock'] == '1' ? 'bg-red-500' : 'bg-green-500 animate-pulse'; ?>"></span>
                                <span class="text-[10px] font-bold uppercase tracking-widest <?php echo $user['isBlock'] == '1' ? 'text-red-400' : 'text-green-400'; ?>">
                                    <?php echo $user['isBlock'] == '1' ? 'Sanctioned' : 'Active'; ?>
                                </span>
                            </div>
                        </td>

                        <!-- Action Buttons -->
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <?php if ($user['isBlock'] == '1'): ?>
                                    <button onclick="window.location.href='index.php?id=<?php echo $user['id']; ?>&action=unblock'" 
                                       class="px-4 py-2 bg-green-500/10 text-green-400 text-[8px] font-black uppercase tracking-widest rounded-lg border border-green-500/20 hover:bg-green-500 hover:text-white transition-all">
                                        Authorize
                                    </button>
                                <?php else: ?>
                                    <button onclick="window.location.href='index.php?id=<?php echo $user['id']; ?>&action=block'" 
                                       class="px-4 py-2 bg-red-500/10 text-red-400 text-[8px] font-black uppercase tracking-widest rounded-lg border border-red-500/20 hover:bg-red-500 hover:text-white transition-all">
                                        Restrict
                                    </button>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>