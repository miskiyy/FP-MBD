<html lang="en">
 <head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1" name="viewport"/>
  <title>
   Ulearnix
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;display=swap" rel="stylesheet"/>
  <style>
   body {
      font-family: "Inter", sans-serif;
    }
  </style>
 </head>
 <body class="bg-white text-gray-900 relative overflow-x-hidden">
  <!-- Header -->
  <header class="flex items-center justify-between px-6 sm:px-10 md:px-16 py-6 border-b border-gray-200">
    <div class="flex items-center space-x-2">
      <div class="w-8 h-8 rounded-full bg-purple-700 text-white flex items-center justify-center font-semibold text-lg">
        C
      </div>
      <span class="font-normal text-lg select-none">
        CodingIn
      </span>
    </div>
    <nav class="hidden md:flex space-x-8 font-semibold text-gray-800 text-sm">
      <a class="hover:text-purple-700" href="#">
        Courses
      </a>
      <a class="hover:text-purple-700" href="#">
        Event
      </a>
      <a class="hover:text-purple-700" href="#">
        Challenge
      </a>
      <a class="hover:text-purple-700" href="#">
        About
      </a>
    </nav>
    <div class="hidden md:flex items-center space-x-4 text-sm text-gray-700">
      <button onclick="window.location.href='register.php'" class="bg-purple-700 text-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-purple-800 transition">
        Sign up
      </button>
      <button onclick="window.location.href='login.php'" class="border border-purple-700 text-purple-700 px-4 py-2 rounded-full text-sm font-semibold hover:bg-purple-50 transition">
        Login
      </button>
    </div>
  </header>
  <!-- Hero Section -->
  <section class="relative px-6 sm:px-10 md:px-16 pt-10 pb-6 bg-white">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center md:items-start justify-between gap-8 lg:gap-12 xl:gap-16">
      <div class="max-w-xl md:max-w-lg space-y-3">
        <h1 class="text-3xl md:text-4xl font-semibold text-gray-900 leading-tight">
          <span class="font-normal">
            Belajar Coding
          </span>
          <span class="text-purple-700">
            Mudah & Terjangkau
          </span>
        </h1>
        <p class="text-gray-700 text-base leading-relaxed">
          CodingIn adalah platform online course coding dengan harga terjangkau, lengkap dengan <b>event</b> seru dan <b>challenge</b> menantang untuk mengasah skill kamu.  
          Ikuti kelas, event, dan challenge, dapatkan sertifikat & reward!
        </p>
        <button class="inline-flex items-center space-x-2 bg-purple-700 text-white px-5 py-2 rounded-full text-sm font-semibold hover:bg-purple-800 transition">
          <span>
            Mulai Belajar Gratis
          </span>
          <i class="fas fa-arrow-right"></i>
        </button>
      </div>
        <img 
          alt="Belajar coding online"
          class="w-72 sm:w-80 md:w-96 lg:w-[500px] xl:w-[600px] rounded-lg"
          src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=400&q=80"
        />
      </div>
    <!-- Info bar below hero -->
    <div class="mt-8 bg-purple-100 rounded-3xl py-3 px-4 flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-10 max-w-7xl mx-auto">
      <div class="flex items-center bg-white rounded-full px-4 py-2 shadow-md space-x-3">
        <div class="text-xs text-gray-600 font-semibold">
          <span class="text-purple-700 font-bold">1000+</span> Member CodingIn
        </div>
      </div>
      <div class="flex items-center bg-white rounded-full px-4 py-2 shadow-md space-x-3">
        <div class="text-xs text-gray-600 font-semibold">
          <span class="text-purple-700 font-bold">50+</span> Event & Challenge
        </div>
      </div>
      <div class="flex items-center bg-white rounded-full px-4 py-2 shadow-md space-x-3">
        <div class="text-xs text-gray-600 font-semibold">
          <span class="text-purple-700 font-bold">Harga Mulai 20rb</span>
          <span class="font-normal">per course</span>
        </div>
      </div>
    </div>
  </section>
  <!-- Features Section -->
  <section class="relative px-6 sm:px-10 md:px-16 py-10 max-w-7xl mx-auto flex flex-col md:flex-row items-center md:items-start justify-between gap-8 md:gap-20">
    <div class="max-w-xl space-y-4">
      <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900 leading-tight">
        Kenapa Pilih CodingIn?
      </h2>
      <ul class="list-disc pl-5 text-gray-700 text-base space-y-2">
        <li>Kursus coding murah & berkualitas</li>
        <li>Event coding rutin dengan hadiah menarik</li>
        <li>Challenge mingguan untuk mengasah skill</li>
        <li>Sertifikat resmi setelah lulus</li>
        <li>Mentor berpengalaman & komunitas aktif</li>
      </ul>
      <button class="inline-flex items-center space-x-2 bg-purple-700 text-white px-5 py-2 rounded-full text-sm font-semibold hover:bg-purple-800 transition">
        <span>
          Lihat Semua Course
        </span>
        <i class="fas fa-arrow-right"></i>
      </button>
    </div>
    <img alt="Belajar coding bersama mentor" class="rounded-lg w-80 md:w-96" src="https://images.unsplash.com/photo-1513258496099-48168024aec0?auto=format&fit=crop&w=400&q=80"/>
  </section>
  <!-- Event & Challenge Section -->
  <section class="bg-purple-100 py-10">
    <div class="max-w-7xl mx-auto px-6 sm:px-10 md:px-16">
      <h2 class="text-2xl font-bold text-purple-700 mb-6">Event & Challenge Coding</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white rounded-2xl shadow p-6">
          <h3 class="font-semibold text-lg mb-2">Event Coding Bulanan</h3>
          <p class="text-gray-700 text-sm mb-2">Ikuti event coding seru setiap bulan, dapatkan pengalaman & networking bersama coder lain.</p>
          <span class="inline-block bg-purple-200 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">Gratis / Berbayar</span>
        </div>
        <div class="bg-white rounded-2xl shadow p-6">
          <h3 class="font-semibold text-lg mb-2">Challenge Mingguan</h3>
          <p class="text-gray-700 text-sm mb-2">Tantang dirimu dengan challenge coding mingguan, kumpulkan poin & menangkan hadiah!</p>
          <span class="inline-block bg-purple-200 text-purple-700 px-3 py-1 rounded-full text-xs font-semibold">Hadiah Menarik</span>
        </div>
      </div>
    </div>
  </section>
  <!-- Testimonial Section -->
  <section class="max-w-7xl mx-auto px-6 sm:px-10 md:px-16 py-10">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Apa Kata Member CodingIn?</h2>
    <div class="flex flex-col md:flex-row items-center md:items-start justify-between gap-10">
      <div class="border-4 border-purple-100 rounded-3xl p-8 relative text-gray-700 text-base max-w-3xl bg-purple-50">
        <p class="mb-6 leading-relaxed">
          “Belajar di CodingIn itu seru banget! Materinya mudah dipahami, event dan challenge-nya bikin tambah semangat belajar. Recommended buat pemula!”
        </p>
        <div class="flex items-center space-x-3">
          <img alt="Testimoni member" class="w-10 h-10 rounded-full" src="https://randomuser.me/api/portraits/men/32.jpg"/>
          <div>
            <p class="font-semibold text-gray-900 text-sm">Rizky Maulana</p>
            <p class="text-xs text-gray-500">Mahasiswa Informatika</p>
          </div>
        </div>
      </div>
      <div class="border-4 border-purple-100 rounded-3xl p-8 relative text-gray-700 text-base max-w-3xl bg-purple-50">
        <p class="mb-6 leading-relaxed">
          “Challenge mingguan CodingIn bikin aku makin jago ngoding. Sertifikatnya juga bermanfaat buat portofolio!”
        </p>
        <div class="flex items-center space-x-3">
          <img alt="Testimoni member" class="w-10 h-10 rounded-full" src="https://randomuser.me/api/portraits/women/44.jpg"/>
          <div>
            <p class="font-semibold text-gray-900 text-sm">Dewi Lestari</p>
            <p class="text-xs text-gray-500">Fresh Graduate</p>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- Call to Action -->
  <section class="max-w-7xl mx-auto px-6 sm:px-10 md:px-16 py-10 flex flex-col md:flex-row items-center md:items-start justify-between gap-8 md:gap-20 bg-white relative">
    <div class="max-w-lg space-y-4">
      <h2 class="text-2xl font-extrabold text-gray-900 leading-tight">
        Gabung CodingIn Sekarang!
        <span aria-hidden="true" class="inline-block relative -top-1 ml-1">🚀</span>
      </h2>
      <p class="text-gray-700 text-sm sm:text-base leading-relaxed max-w-md">
        Daftar sekarang dan mulai perjalanan coding-mu bersama komunitas CodingIn.  
        Dapatkan akses ke courses murah, event, challenge, dan sertifikat resmi!
      </p>
      <button class="inline-flex items-center space-x-2 bg-purple-700 text-white px-5 py-2 rounded-full text-sm font-semibold hover:bg-purple-800 transition">
        <span>
          Daftar Sekarang
        </span>
        <i class="fas fa-user-plus"></i>
      </button>
    </div>
    <img alt="Gabung komunitas CodingIn" class="rounded-lg w-72 sm:w-80 md:w-96" src="https://images.unsplash.com/photo-1461749280684-dccba630e2f6?auto=format&fit=crop&w=400&q=80"/>
  </section>
  <!-- Footer -->
  <footer class="bg-purple-900 text-white px-6 sm:px-10 md:px-16 py-10">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-5 gap-10">
      <div class="space-y-3">
        <div class="flex items-center space-x-2 font-semibold text-lg select-none">
          <div class="w-8 h-8 rounded-full bg-purple-700 text-white flex items-center justify-center font-semibold text-lg">
            C
          </div>
          <span>
            CodingIn
          </span>
        </div>
        <p class="text-xs leading-relaxed max-w-[220px]">
          Platform online course coding murah, lengkap dengan event & challenge seru.  
          Belajar, praktek, dapat sertifikat & reward!
        </p>
      </div>
      <div>
        <h3 class="text-xs font-semibold mb-4">FITUR</h3>
        <ul class="text-xs space-y-2">
          <li><a class="hover:underline" href="#">Courses</a></li>
          <li><a class="hover:underline" href="#">Event</a></li>
          <li><a class="hover:underline" href="#">Challenge</a></li>
        </ul>
      </div>
      <div>
        <h3 class="text-xs font-semibold mb-4">TENTANG</h3>
        <ul class="text-xs space-y-2">
          <li><a class="hover:underline" href="#">Tentang CodingIn</a></li>
          <li><a class="hover:underline" href="#">Kontak</a></li>
        </ul>
      </div>
      <div>
        <h3 class="text-xs font-semibold mb-4">DUKUNGAN</h3>
        <ul class="text-xs space-y-2">
          <li><a class="hover:underline" href="#">FAQ</a></li>
          <li><a class="hover:underline" href="#">Bantuan</a></li>
        </ul>
      </div>
      <div class="flex flex-col space-y-3">
        <a class="flex items-center space-x-2 bg-blue-700 hover:bg-blue-800 transition rounded px-3 py-1 text-xs" href="#">
          <i class="fab fa-facebook-f"></i>
          <span>Facebook</span>
        </a>
        <a class="flex items-center space-x-2 bg-blue-500 hover:bg-blue-600 transition rounded px-3 py-1 text-xs" href="#">
          <i class="fab fa-linkedin-in"></i>
          <span>Linkedin</span>
        </a>
        <a class="flex items-center space-x-2 bg-blue-400 hover:bg-blue-500 transition rounded px-3 py-1 text-xs" href="#">
          <i class="fab fa-twitter"></i>
          <span>Twitter</span>
        </a>
        <a class="flex items-center space-x-2 bg-gradient-to-r from-yellow-400 via-pink-500 to-purple-600 hover:from-yellow-500 hover:via-pink-600 hover:to-purple-700 transition rounded px-3 py-1 text-xs" href="#">
          <i class="fab fa-instagram"></i>
          <span>Instagram</span>
        </a>
      </div>
    </div>
    <hr class="border-purple-700 my-6"/>
    <p class="text-center text-xs text-purple-300 select-none">
      © 2025 CodingIn - Belajar Coding Mudah & Terjangkau
    </p>
  </footer>
</body>
</html>
