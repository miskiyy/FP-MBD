<!-- user_sidebar.php -->
<!-- Tailwind & Lucide -->
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>

<!-- TOMBOL BURGER (100% LURUS DENGAN ICON SIDEBAR) -->
<div class="fixed top-3 left-0 z-50 flex items-center pl-2">
  <button id="sidebarToggle" class="p-2">
    <i id="burgerIcon" data-lucide="menu" class="text-white w-6 h-6"></i>
  </button>
</div>


<!-- SIDEBAR -->
<aside id="userSidebar" class="bg-purple-900 text-white min-h-screen px-4 py-8 w-64 transition-all duration-300 ease-in-out fixed top-0 left-0 z-40">
  <!-- LOGO & TITLE -->
  <div class="flex items-center mt-8 mb-10 space-x-2 sidebar-header transition-all duration-300">
    <div class="w-10 h-10 rounded-full bg-purple-700 flex items-center justify-center font-bold text-2xl">U</div>
    <span class="font-semibold text-xl tracking-wide sidebar-label">CodingIn</span>
  </div>

  <!-- MENU -->
  <nav>
    <ul class="space-y-2 text-sm font-medium">
      <li>
        <a href="/codingin/public/dashboard_user.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-purple-700">
          <i data-lucide="home" class="mr-2"></i> <span class="sidebar-label">Dashboard</span>
        </a>
      </li>
      <li>
        <a href="/codingin/user/my_courses.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-purple-700">
          <i data-lucide="book-open" class="mr-2"></i> <span class="sidebar-label">My Courses</span>
        </a>
      </li>
      <li>
        <a href="/codingin/user/my_event.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-purple-700">
          <i data-lucide="calendar" class="mr-2"></i> <span class="sidebar-label">My Event</span>
        </a>
      </li>
      <li>
        <a href="../user/my_challenge.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-purple-700"">
          <i data-lucide="target" class="mr-2"></i> <span class="sidebar-label">My Challenge</span>
        </a>
      </li>
      <li>
        <a href="/codingin/user/my_certificate.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-purple-700">
          <i data-lucide="award" class="mr-2"></i> <span class="sidebar-label">My Certificate</span>
        </a>
      </li>
      <li>
        <a href="/codingin/user/event_list.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-purple-700">
          <i data-lucide="list" class="mr-2"></i> <span class="sidebar-label">Lihat Semua Event</span>
        </a>
      </li>
      <li>
        <a href="/codingin/user/course_list.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-purple-700">
          <i data-lucide="book" class="mr-2"></i> <span class="sidebar-label">Lihat Semua Course</span>
        </a>
      </li>
      <li>
        <a href="/codingin/user/challenge_list.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-purple-700">
          <i data-lucide="flag" class="mr-2"></i> <span class="sidebar-label">Challenge</span>
        </a>
      </li>
      <li>
        <a href="/codingin/user/transactions.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-purple-700">
          <i data-lucide="credit-card" class="mr-2"></i> <span class="sidebar-label">History Transaksi</span>
        </a>
      </li>
      <li>
        <a href="/codingin/user/edit_profil.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-purple-700">
          <i data-lucide="settings" class="mr-2"></i> <span class="sidebar-label">Edit Profil</span>
        </a>
      </li>
      <li>
        <a href="/codingin/public/logout.php" class="flex items-center px-3 py-2 rounded-lg hover:bg-purple-700 text-white font-medium">
          <i data-lucide="power" class="mr-2"></i> <span class="sidebar-label">Logout</span>
        </a>
      </li>
    </ul>
  </nav>
</aside>

<!-- STYLING UNTUK COLLAPSE MODE -->
<style>
  .collapsed {
    width: 64px !important;
    padding-left: 0.5rem;
    padding-right: 0.5rem;
  }

  .collapsed .sidebar-label {
    display: none;
  }
</style>

<!-- TOGGLE LOGIC -->
<script>
  window.addEventListener('DOMContentLoaded', () => {
    lucide.createIcons();

    const sidebar = document.getElementById('userSidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const burgerIcon = document.getElementById('burgerIcon');
    const mainContent = document.getElementById('mainContent');

    let expanded = true;

    function updateSidebar() {
      if (expanded) {
        sidebar.classList.remove('collapsed');
        mainContent?.classList.remove('ml-16');
        mainContent?.classList.add('ml-64');
      } else {
        sidebar.classList.add('collapsed');
        mainContent?.classList.remove('ml-64');
        mainContent?.classList.add('ml-16');
      }

      lucide.createIcons(); // Tetap render ulang icon biar aman
    }

    toggleBtn.addEventListener('click', () => {
      expanded = !expanded;
      updateSidebar();
    });

    updateSidebar(); // Init
  });
</script>