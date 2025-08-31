<header class="app-header">
    <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item d-block d-xl-none">
                <a class="nav-link sidebartoggler nav-icon-hover" id="headerCollapse" href="javascript:void(0)">
                    <i class="ti ti-menu-2"></i>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link nav-icon-hover" href="javascript:void(0)">
                    <i class="ti ti-bell-ringing"></i>
                    <div class="notification bg-primary rounded-circle"></div>
                </a>
            </li>
        </ul>
        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
            <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end gap-3">

                <li class="nav-item d-none d-md-flex align-items-center me-3">
                    <div id="jam-tanggal" style="font-size: 14px; font-weight: bold;"></div>

                    <script>
                        function updateJamTanggal() {
                            const now = new Date();
                            let jam = now.getHours().toString().padStart(2, '0');
                            let menit = now.getMinutes().toString().padStart(2, '0');
                            let detik = now.getSeconds().toString().padStart(2, '0');
                            let tanggal = now.toLocaleDateString('id-ID', {
                                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
                            });
                            document.getElementById('jam-tanggal').innerText = `${tanggal} | ${jam}:${menit}:${detik}`;
                        }

                        setInterval(updateJamTanggal, 1000);
                        updateJamTanggal();
                    </script>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link d-flex align-items-center bg-light-subtle p-2 rounded-2" href="javascript:void(0)" id="drop2"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ asset('modernize/src/assets/images/profile/user-1.jpg') }}" alt="User Profile"
                            width="35" height="35" class="rounded-circle me-2">
                        <span class="text-gray-800 small d-none d-sm-inline">
                            Hai, {{ Auth::user()->name }}
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                        <div class="message-body">
                            <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                <i class="ti ti-user fs-6"></i>
                                <p class="mb-0 fs-3">My Profile</p>
                            </a>
                            <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                <i class="ti ti-mail fs-6"></i>
                                <p class="mb-0 fs-3">My Account</p>
                            </a>
                            <a href="javascript:void(0)" class="d-flex align-items-center gap-2 dropdown-item">
                                <i class="ti ti-list-check fs-6"></i>
                                <p class="mb-0 fs-3">My Task</p>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="mx-3 mt-2 d-block">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</header>
