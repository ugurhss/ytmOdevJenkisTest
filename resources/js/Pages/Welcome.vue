<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

// --- STATE ---
const isMenuOpen = ref(false);
const activeTab = ref('privacy');
const roleSelection = ref('leader');

// --- REFS ---
const cursorDot = ref(null);
const cursorOutline = ref(null);

// --- LOGIC ---
const handleMouseMove = (e) => {
    if (!cursorDot.value || !cursorOutline.value) return;
    const { clientX, clientY } = e;

    cursorDot.value.style.left = `${clientX}px`;
    cursorDot.value.style.top = `${clientY}px`;

    cursorOutline.value.animate({
        left: `${clientX}px`,
        top: `${clientY}px`
    }, { duration: 500, fill: "forwards" });
};

onMounted(() => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));

    if (window.matchMedia("(min-width: 1024px)").matches) {
        document.body.classList.add('custom-cursor-active');
        window.addEventListener('mousemove', handleMouseMove);

        document.addEventListener('mouseover', (e) => {
            const target = e.target;
            if (target.tagName === 'A' || target.tagName === 'BUTTON' || target.closest('.hover-trigger')) {
                document.body.classList.add('cursor-hover');
            } else {
                document.body.classList.remove('cursor-hover');
            }
        });
    }
});

onUnmounted(() => {
    window.removeEventListener('mousemove', handleMouseMove);
    document.body.classList.remove('custom-cursor-active');
});

const toggleMenu = () => isMenuOpen.value = !isMenuOpen.value;
</script>

<template>
    <Head title="COMMUN | İletişimi Özgürleştir" />

    <div class="app-wrapper">
        <div class="bg-noise"></div>
        <div class="bg-glow glow-purple"></div>
        <div class="bg-glow glow-emerald"></div>

        <div class="cursor-dot" ref="cursorDot"></div>
        <div class="cursor-outline" ref="cursorOutline"></div>

        <nav class="glass-nav">
            <div class="container nav-inner">
                <div class="logo">
                    <span class="logo-icon"><i class="fas fa-bolt"></i></span>
                    COMMUN
                </div>

                <div class="hamburger hover-trigger" @click="toggleMenu">
                    <i class="fas fa-bars"></i>
                </div>

                <div class="nav-menu" :class="{ 'is-open': isMenuOpen }">
                    <a href="#features" class="nav-link hover-trigger">Özellikler</a>
                    <a href="#community" class="nav-link hover-trigger">Topluluklar</a>
                    <a href="#roles" class="nav-link hover-trigger">Kullanım</a>

                    <div v-if="canLogin" class="auth-actions">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="btn-primary hover-trigger">
                            Panele Git
                        </Link>
                        <template v-else>
                            <Link :href="route('login')" class="nav-link hover-trigger">Giriş</Link>
                            <Link v-if="canRegister" :href="route('register')" class="btn-primary hover-trigger">
                                Ücretsiz Başla
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <section class="hero-section">
            <div class="container hero-content animate-on-scroll">
                <div class="hero-badge">
                    <i class="fas fa-shield-alt"></i> %100 Gizlilik Odaklı & Ücretsiz
                </div>
                <h1 class="hero-title">
                    Numaranı Paylaşma,<br>
                    <span class="gradient-text">Topluluğu Yakala.</span>
                </h1>
                <p class="hero-desc">
                    Üniversite kulüpleri, apartmanlar ve organizasyonlar için en güvenli duyuru platformu.
                    WhatsApp gruplarındaki numara ifşasına ve gereksiz sohbete son verin.
                </p>
                <div class="hero-cta">
                    <Link :href="route('register')" class="btn-xl hover-trigger">
                        Topluluk Oluştur <i class="fas fa-arrow-right"></i>
                    </Link>
                    <a href="#features" class="btn-text hover-trigger">
                        <i class="fas fa-play-circle"></i> Nasıl Çalışır?
                    </a>
                </div>
            </div>
        </section>

        <div class="sponsors-wrapper" id="community">
            <div class="sponsors-tilt">
                <div class="marquee-track">
                    <div class="logo-box">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/f/ff/Inonu_University_Logo.jpg" alt="İnönü Üniversitesi">
                    </div>
                    <div class="logo-box">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/7e/Antalya_Bilim_Üniversitesi_logo.svg/1024px-Antalya_Bilim_Üniversitesi_logo.svg.png" alt="Antalya Bilim Üniversitesi">
                    </div>
                    <div class="logo-box circle-box">
                        <img src="https://pbs.twimg.com/profile_images/1820692112934912000/OYoH6C6e_400x400.jpg" alt="Topluluk">
                    </div>
                    <div class="logo-box">
                        <img src="https://upload.wikimedia.org/wikipedia/tr/archive/0/02/20210216161421%21Gebze_Teknik_%C3%9Cniversitesi_logo.png" alt="GTÜ">
                    </div>

                    <div class="logo-box">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/f/ff/Inonu_University_Logo.jpg" alt="İnönü Üniversitesi">
                    </div>
                    <div class="logo-box">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/7e/Antalya_Bilim_Üniversitesi_logo.svg/1024px-Antalya_Bilim_Üniversitesi_logo.svg.png" alt="Antalya Bilim Üniversitesi">
                    </div>
                    <div class="logo-box circle-box">
                        <img src="https://pbs.twimg.com/profile_images/1820692112934912000/OYoH6C6e_400x400.jpg" alt="Topluluk">
                    </div>
                    <div class="logo-box">
                        <img src="https://upload.wikimedia.org/wikipedia/tr/archive/0/02/20210216161421%21Gebze_Teknik_%C3%9Cniversitesi_logo.png" alt="GTÜ">
                    </div>
                </div>
            </div>
        </div>

        <section id="features" class="features-section container animate-on-scroll">
            <div class="section-head">
                <h2>Sistemin Beyni</h2>
                <p>Geleneksel mesajlaşma uygulamalarının karmaşasından kurtulun.</p>
            </div>

            <div class="feature-tabs">
                <div class="tabs-list">
                    <button class="tab-btn hover-trigger" :class="{ active: activeTab === 'privacy' }" @click="activeTab = 'privacy'">
                        <div class="t-icon"><i class="fas fa-user-secret"></i></div>
                        <div class="t-info">
                            <strong>Gizlilik Kalkanı</strong>
                            <span>Telefon numaranız asla görünmez.</span>
                        </div>
                    </button>
                    <button class="tab-btn hover-trigger" :class="{ active: activeTab === 'notify' }" @click="activeTab = 'notify'">
                        <div class="t-icon"><i class="fas fa-bell"></i></div>
                        <div class="t-info">
                            <strong>Akıllı Bildirim</strong>
                            <span>Sadece önemli duyuruları alın.</span>
                        </div>
                    </button>
                    <button class="tab-btn hover-trigger" :class="{ active: activeTab === 'easy' }" @click="activeTab = 'easy'">
                        <div class="t-icon"><i class="fas fa-qrcode"></i></div>
                        <div class="t-info">
                            <strong>Hızlı Erişim</strong>
                            <span>QR ile saniyeler içinde katılın.</span>
                        </div>
                    </button>
                </div>

                <div class="tab-display glass-card glow-border">
                    <div v-if="activeTab === 'privacy'" class="tab-content fade-in">
                        <div class="content-graphic color-primary">
                            <i class="fas fa-shield-virus"></i>
                        </div>
                        <h3>Kimliğiniz Güvende</h3>
                        <p>Bir WhatsApp grubuna girdiğinizde numaranız 200 kişiye görünür. COMMUN'da ise sadece sisteme kayıtlı bir "Hayalet Kullanıcı"sınız. Yöneticiler bile numaranızı göremez, sadece size bildirim iletebilir.</p>
                    </div>

                    <div v-if="activeTab === 'notify'" class="tab-content fade-in">
                        <div class="content-graphic color-accent">
                            <i class="fas fa-paper-plane"></i>
                        </div>
                        <h3>Sohbet Yok, Duyuru Var</h3>
                        <p>Telefonunuzda sürekli öten, "Günaydın" mesajlarıyla dolu gruplardan sıkıldınız mı? Burası tek yönlü bir iletişim kanalıdır. Sadece etkinlik, duyuru ve acil durum bildirimleri gelir.</p>
                    </div>

                    <div v-if="activeTab === 'easy'" class="tab-content fade-in">
                        <div class="content-graphic color-tertiary">
                            <i class="fas fa-magic"></i>
                        </div>
                        <h3>Uygulama İndirmek Zorunda Değilsin</h3>
                        <p>İster mobil uygulamayı kullan, ister web üzerinden bildirimleri takip et. Topluluk yöneticisinin paylaştığı linke tıkla veya duvardaki QR kodu okut. İşte bu kadar!</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="comparison-section container animate-on-scroll">
            <div class="glass-card table-card glow-border">
                <h3>Neden Tercih Edilmeli?</h3>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Özellik</th>
                                <th class="text-dim">Diğer Uygulamalar</th>
                                <th class="text-highlight">COMMUN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Telefon No Gizliliği</td>
                                <td class="negative"><i class="fas fa-times"></i> Herkes Görür</td>
                                <td class="positive"><i class="fas fa-check"></i> <strong>Tamamen Gizli</strong></td>
                            </tr>
                            <tr>
                                <td>Gereksiz Mesajlar</td>
                                <td class="negative"><i class="fas fa-times"></i> Sohbet Kirliliği</td>
                                <td class="positive"><i class="fas fa-check"></i> <strong>Sıfır (Sadece Duyuru)</strong></td>
                            </tr>
                            <tr>
                                <td>Ücret</td>
                                <td class="text-dim">Değişken</td>
                                <td class="positive"><i class="fas fa-check"></i> <strong>%100 Ücretsiz</strong></td>
                            </tr>
                            <tr>
                                <td>Kullanım Kolaylığı</td>
                                <td class="text-dim">Kurulum Gerekir</td>
                                <td class="positive"><i class="fas fa-check"></i> <strong>QR ile Anında</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section id="roles" class="roles-section container animate-on-scroll">
            <div class="section-head">
                <h2>Sen Hangisisin?</h2>
                <div class="role-switcher glass-switcher">
                    <button :class="{ active: roleSelection === 'member' }" @click="roleSelection = 'member'" class="hover-trigger">Topluluk Üyesi</button>
                    <button :class="{ active: roleSelection === 'leader' }" @click="roleSelection = 'leader'" class="hover-trigger">Topluluk Lideri</button>
                </div>
            </div>

            <div class="role-cards-wrapper">
                <div class="role-card glass-card" :class="{ 'card-active': roleSelection === 'member' }">
                    <div class="card-visual color-primary"><i class="fas fa-user-astronaut"></i></div>
                    <h3>Öğrenci / Üye</h3>
                    <p>Duyuruları takip etmek, etkinliklerden haberdar olmak isteyenler için.</p>
                    <div class="price">ÜCRETSİZ</div>
                    <ul class="features-list">
                        <li><i class="fas fa-check"></i> Numaranız Gizli Kalır</li>
                        <li><i class="fas fa-check"></i> Sınırsız Topluluğa Katılım</li>
                        <li><i class="fas fa-check"></i> Reklamsız Deneyim</li>
                    </ul>
                    <Link :href="route('register')" class="btn-card hover-trigger">Hemen Katıl</Link>
                </div>

                <div class="role-card featured glass-card" :class="{ 'card-active': roleSelection === 'leader' }">
                    <div class="badge">POPÜLER</div>
                    <div class="card-visual color-accent"><i class="fas fa-crown"></i></div>
                    <h3>Yönetici / Lider</h3>
                    <p>Kulüp başkanları, apartman yöneticileri ve temsilciler için.</p>
                    <div class="price">ÜCRETSİZ</div>
                    <ul class="features-list">
                        <li><i class="fas fa-check"></i> Kendi Topluluğunu Kur</li>
                        <li><i class="fas fa-check"></i> QR Kod ile Üye Topla</li>
                        <li><i class="fas fa-check"></i> Anlık Bildirim Gönder</li>
                        <li><i class="fas fa-check"></i> Okundu İstatistikleri</li>
                    </ul>
                    <Link :href="route('register')" class="btn-primary btn-full hover-trigger">Topluluk Oluştur</Link>
                </div>
            </div>
        </section>

        <footer class="main-footer">

            <div class="footer-tilt-wrapper">
                <div class="marquee-track-reverse">
                    <span class="user-item"><i class="fas fa-check-circle"></i> Ahmet Y. (ODTÜ IEEE Bşk.)</span>
                    <span class="dot">•</span>
                    <span class="user-item"><i class="fas fa-check-circle"></i> Zeynep K. (Vadi Sitesi Yöneticisi)</span>
                    <span class="dot">•</span>
                    <span class="user-item"><i class="fas fa-check-circle"></i> E-Spor Kulübü</span>
                    <span class="dot">•</span>
                    <span class="user-item"><i class="fas fa-check-circle"></i> Mustafa S. (Apartman Temsilcisi)</span>
                    <span class="dot">•</span>
                    <span class="user-item"><i class="fas fa-check-circle"></i> İTÜ Yazılım</span>
                    <span class="dot">•</span>
                    <span class="user-item"><i class="fas fa-check-circle"></i> Ahmet Y. (ODTÜ IEEE Bşk.)</span>
                    <span class="dot">•</span>
                    <span class="user-item"><i class="fas fa-check-circle"></i> Zeynep K. (Vadi Sitesi Yöneticisi)</span>
                    <span class="dot">•</span>
                    <span class="user-item"><i class="fas fa-check-circle"></i> E-Spor Kulübü</span>
                    <span class="dot">•</span>
                    <span class="user-item"><i class="fas fa-check-circle"></i> Mustafa S. (Apartman Temsilcisi)</span>
                    <span class="dot">•</span>
                    <span class="user-item"><i class="fas fa-check-circle"></i> İTÜ Yazılım</span>
                </div>
            </div>

            <div class="container footer-inner">
                <div class="footer-brand">
                    <div class="logo mb-4">
                        <span class="logo-icon"><i class="fas fa-bolt"></i></span>
                        COMMUN.
                    </div>
                    <p>İletişimi özgürleştiren, gizliliği koruyan modern platform.</p>
                </div>
                <div class="footer-links">
                    <a href="#" class="hover-trigger"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="hover-trigger"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="hover-trigger"><i class="fab fa-github"></i></a>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; 2025 COMMUN. Laravel v{{ laravelVersion }} (PHP v{{ phpVersion }})
            </div>
        </footer>
    </div>
</template>

<style>
/* --- FONT & RESET --- */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

:root {
    /* Aurora Borealis Renk Paleti */
    --bg-dark: #0B0F19;
    --surface: rgba(255, 255, 255, 0.04);
    --border: rgba(255, 255, 255, 0.1);

    --primary: #8B5CF6;
    --primary-light: #A78BFA;
    --accent: #34D399;
    --accent-glow: rgba(52, 211, 153, 0.4);
    --tertiary: #F472B6;

    --text-main: #ffffff;
    --text-dim: #9CA3AF;

    --success: #34D399;
    --danger: #F87171;
    --ease-elastic: cubic-bezier(0.34, 1.56, 0.64, 1);
    --nav-height: 80px;
}

* { box-sizing: border-box; margin: 0; padding: 0; outline: none; -webkit-tap-highlight-color: transparent; }
html { scroll-behavior: smooth; }
body {
    background-color: var(--bg-dark); color: var(--text-main);
    font-family: 'Plus Jakarta Sans', sans-serif;
    overflow-x: hidden;
    line-height: 1.5;
}

/* --- BACKGROUND FX --- */
.bg-noise {
    position: fixed; inset: 0; z-index: -1; opacity: 0.03; pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
}
.bg-glow {
    position: fixed; width: 800px; height: 800px; border-radius: 50%;
    filter: blur(120px); opacity: 0.12; z-index: -1; animation: float 15s infinite alternate ease-in-out;
}
.glow-purple { background: var(--primary); top: -20%; left: -10%; }
.glow-emerald { background: var(--accent); bottom: -20%; right: -10%; animation-delay: -7s; }

@keyframes float { from { transform: translate(0,0) rotate(0deg); } to { transform: translate(50px, 50px) rotate(5deg); } }

/* --- CUSTOM CURSOR --- */
.cursor-dot {
    position: fixed; width: 10px; height: 10px; background: var(--accent); border-radius: 50%;
    pointer-events: none; z-index: 9999; transform: translate(-50%, -50%); display: none; box-shadow: 0 0 10px var(--accent);
}
.cursor-outline {
    position: fixed; width: 40px; height: 40px; border: 2px solid rgba(255,255,255,0.4); border-radius: 50%;
    pointer-events: none; z-index: 9999; transform: translate(-50%, -50%); display: none;
    transition: width 0.3s var(--ease-elastic), height 0.3s var(--ease-elastic), background 0.3s, border-color 0.3s;
}
body.custom-cursor-active .cursor-dot, body.custom-cursor-active .cursor-outline { display: block; }
body.custom-cursor-active { cursor: none; }
body.cursor-hover .cursor-outline {
    width: 70px; height: 70px;
    background: rgba(139, 92, 246, 0.15);
    border-color: var(--primary-light);
}

/* --- UTILS --- */
.container { max-width: 1200px; margin: 0 auto; padding: 0 24px; }
.gradient-text {
    background: linear-gradient(135deg, #fff 10%, var(--primary-light) 50%, var(--accent) 90%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    display: inline-block;
}

.glass-card {
    background: rgba(255, 255, 255, 0.03);
    backdrop-filter: blur(16px) saturate(120%);
    -webkit-backdrop-filter: blur(16px) saturate(120%);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 24px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.glow-border { position: relative; }
.glow-border::before {
    content: ''; position: absolute; inset: 0; border-radius: 24px; padding: 1px;
    background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
    -webkit-mask-composite: xor; mask-composite: exclude; pointer-events: none;
}

.btn-primary {
    background: linear-gradient(90deg, var(--primary), var(--accent));
    color: #fff; padding: 14px 32px; border-radius: 50px; text-decoration: none;
    font-weight: 700; display: inline-flex; align-items: center; gap: 10px;
    transition: all 0.3s var(--ease-elastic);
    box-shadow: 0 4px 20px rgba(139, 92, 246, 0.3), 0 0 0 0 rgba(52, 211, 153, 0.7);
}
.btn-primary:hover {
    transform: translateY(-2px) scale(1.03);
    box-shadow: 0 10px 30px rgba(139, 92, 246, 0.4), 0 0 0 4px rgba(52, 211, 153, 0.2);
}
.btn-full { width: 100%; justify-content: center; }

/* --- NAVBAR --- */
.glass-nav {
    position: fixed; top: 0; left: 0; width: 100%; z-index: 100;
    height: var(--nav-height);
    background: rgba(11, 15, 25, 0.7);
    backdrop-filter: blur(20px) saturate(120%);
    border-bottom: 1px solid rgba(255,255,255,0.05);
}
.nav-inner { display: flex; justify-content: space-between; align-items: center; height: 100%; }
.logo { font-size: 1.6rem; font-weight: 800; display: flex; align-items: center; gap: 12px; letter-spacing: -0.5px; color: #fff; }
.logo-icon { color: var(--accent); filter: drop-shadow(0 0 10px var(--accent-glow)); }
.nav-menu { display: flex; align-items: center; gap: 36px; }
.nav-link { color: var(--text-dim); text-decoration: none; font-weight: 600; transition: 0.3s; font-size: 0.95rem; position: relative; }
.nav-link:hover { color: #fff; }
.nav-link::after {
    content: ''; position: absolute; bottom: -4px; left: 0; width: 0; height: 2px;
    background: var(--accent); transition: 0.3s var(--ease-elastic);
}
.nav-link:hover::after { width: 100%; }
.hamburger { display: none; font-size: 1.6rem; cursor: pointer; color: var(--text-main); }

/* --- HERO --- */
.hero-section { padding: 180px 0 120px; text-align: center; }
.hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(139, 92, 246, 0.1); color: var(--primary-light);
    padding: 10px 20px; border-radius: 50px; font-size: 0.9rem; font-weight: 600; margin-bottom: 36px;
    border: 1px solid rgba(139, 92, 246, 0.25); box-shadow: 0 0 20px rgba(139, 92, 246, 0.1);
}
.hero-title { font-size: clamp(2.8rem, 7vw, 5.5rem); line-height: 1.1; margin-bottom: 30px; font-weight: 800; letter-spacing: -2px; }
.hero-desc { font-size: 1.25rem; color: var(--text-dim); max-width: 680px; margin: 0 auto 48px; line-height: 1.7; font-weight: 400; }
.hero-cta { display: flex; justify-content: center; align-items: center; gap: 24px; flex-wrap: wrap; }
.btn-xl {
    background: #fff; color: var(--bg-dark); padding: 20px 48px; border-radius: 16px;
    font-weight: 800; text-decoration: none; font-size: 1.15rem; transition: 0.3s var(--ease-elastic);
    display: inline-flex; align-items: center; gap: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}
.btn-xl:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(255,255,255,0.15); }
.btn-text { color: var(--text-main); text-decoration: none; font-weight: 700; display: flex; align-items: center; gap: 10px; transition: 0.3s; }
.btn-text:hover { color: var(--accent); }
.btn-text i { font-size: 1.4rem; color: var(--primary-light); }

/* --- MARQUEE (SPONSORS) - ( / ) YÖNÜNDE EĞİM --- */
.sponsors-wrapper {
    position: relative; padding: 100px 0; overflow: hidden; margin: 40px 0 120px;
}
.sponsors-tilt {
    transform: rotate(-3deg) scale(1.0); /* Eksi değer: Sol aşağıdan Sağ yukarıya / */
    background: linear-gradient(90deg, rgba(139, 92, 246, 0.08), rgba(52, 211, 153, 0.08));
    border-top: 1px solid rgba(255,255,255,0.05);
    border-bottom: 1px solid rgba(255,255,255,0.05);
    padding: 50px 0;
    width: 105%; margin-left: -2.5%;
    backdrop-filter: blur(10px);
}
.marquee-track {
    display: flex; gap: 100px; white-space: nowrap;
    animation: scroll 35s linear infinite; /* SOLA DOĞRU AKAR */
    align-items: center;
}

/* Sponsor Logoları */
.logo-box {
    background: rgba(255,255,255,0.03);
    padding: 20px 30px;
    border-radius: 20px;
    border: 1px solid rgba(255,255,255,0.05);
    display: flex; align-items: center; justify-content: center;
    transition: all 0.4s var(--ease-elastic);
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}
.circle-box { border-radius: 50%; padding: 10px; }

.logo-box img {
    height: 90px;
    width: auto;
    filter: grayscale(100%) brightness(0.8);
    opacity: 0.7;
    transition: all 0.4s ease;
    display: block;
}
.circle-box img { border-radius: 50%; height: 110px; }

.logo-box:hover {
    background: rgba(255,255,255,0.08);
    border-color: rgba(255,255,255,0.2);
    transform: scale(1.1);
    box-shadow: 0 20px 50px rgba(0,0,0,0.4);
}
.logo-box:hover img {
    filter: grayscale(0%) brightness(1);
    opacity: 1;
}
@keyframes scroll { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }

/* --- FEATURES (TABS) --- */
.features-section { padding: 60px 24px; }
.section-head { text-align: center; margin-bottom: 70px; }
.section-head h2 { font-size: 3.2rem; margin-bottom: 20px; font-weight: 800; letter-spacing: -1px; }
.section-head p { color: var(--text-dim); font-size: 1.2rem; max-width: 600px; margin: 0 auto; }

.feature-tabs { display: grid; grid-template-columns: 400px 1fr; gap: 50px; align-items: start; }
.tabs-list { display: flex; flex-direction: column; gap: 20px; }
.tab-btn {
    display: flex; align-items: center; gap: 24px; padding: 24px 30px;
    background: transparent; border: 1px solid transparent; border-radius: 20px;
    cursor: pointer; text-align: left; transition: all 0.3s var(--ease-elastic);
    position: relative; overflow: hidden;
}
.tab-btn::before {
    content: ''; position: absolute; inset: 0; background: linear-gradient(90deg, var(--primary), var(--accent));
    opacity: 0; transition: 0.3s; z-index: -1;
}
.tab-btn:hover { background: rgba(255,255,255,0.03); transform: translateX(10px); }
.tab-btn.active { background: rgba(255,255,255,0.06); border-color: rgba(255,255,255,0.1); box-shadow: 0 10px 30px rgba(0,0,0,0.2); }

.t-icon {
    width: 56px; height: 56px; background: rgba(255,255,255,0.05); border-radius: 16px;
    display: flex; align-items: center; justify-content: center; font-size: 1.4rem; color: var(--text-dim);
    transition: 0.3s; flex-shrink: 0;
}
.tab-btn.active .t-icon {
    background: linear-gradient(135deg, var(--primary), var(--accent));
    color: #fff; box-shadow: 0 0 25px var(--primary);
    transform: scale(1.1) rotate(-5deg);
}
.t-info strong { display: block; font-size: 1.15rem; color: #fff; margin-bottom: 6px; font-weight: 700; }
.t-info span { color: var(--text-dim); font-size: 0.95rem; font-weight: 500; }

.tab-display { min-height: 450px; padding: 60px; display: flex; align-items: center; justify-content: center; text-align: center; }
.tab-content { max-width: 420px; animation: fadeInUp 0.6s var(--ease-elastic) forwards; }
.content-graphic { font-size: 5.5rem; margin-bottom: 35px; filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3)); transition: 0.3s; }
.color-primary { color: var(--primary); text-shadow: 0 0 30px rgba(139, 92, 246, 0.4); }
.color-accent { color: var(--accent); text-shadow: 0 0 30px rgba(52, 211, 153, 0.4); }
.color-tertiary { color: var(--tertiary); text-shadow: 0 0 30px rgba(244, 114, 182, 0.4); }

.tab-content h3 { font-size: 2rem; margin-bottom: 20px; font-weight: 800; }
.tab-content p { color: var(--text-dim); line-height: 1.7; font-size: 1.05rem; }

/* --- COMPARISON --- */
.comparison-section { padding: 120px 24px; }
.table-card { padding: 50px; background: rgba(11, 15, 25, 0.6); }
.table-card h3 { text-align: center; font-size: 2.2rem; margin-bottom: 50px; font-weight: 800; }
.table-responsive { overflow-x: auto; border-radius: 16px; }
table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 700px; }
th, td { padding: 24px 30px; text-align: left; border-bottom: 1px solid var(--border); }
th { font-size: 1.15rem; font-weight: 700; color: #fff; background: rgba(255,255,255,0.02); }
td { font-weight: 600; color: var(--text-main); }
tr:last-child td { border-bottom: none; }
tr:hover td { background: rgba(255,255,255,0.02); }

.text-highlight { color: var(--accent); font-size: 1.3rem; text-shadow: 0 0 15px var(--accent-glow); }
.positive { color: var(--success); }
.negative { color: var(--danger); opacity: 0.8; }
td i { margin-right: 8px; font-size: 1.1rem; }

/* --- ROLES --- */
.roles-section { padding: 60px 24px 120px; }
.glass-switcher {
    display: flex; gap: 5px; justify-content: center;
    background: rgba(0,0,0,0.3); border: 1px solid var(--border);
    padding: 6px; border-radius: 100px; width: fit-content; margin: 0 auto 60px;
    backdrop-filter: blur(10px);
}
.glass-switcher button {
    background: transparent; border: none; padding: 12px 32px; color: var(--text-dim);
    border-radius: 50px; cursor: pointer; font-weight: 700; transition: all 0.3s var(--ease-elastic); font-size: 1rem;
}
.glass-switcher button.active {
    background: #fff; color: var(--bg-dark);
    box-shadow: 0 5px 15px rgba(255,255,255,0.2);
}

.role-cards-wrapper { display: flex; justify-content: center; gap: 40px; flex-wrap: wrap; align-items: stretch; }
.role-card {
    width: 380px; padding: 50px 40px; text-align: center; transition: all 0.4s var(--ease-elastic); position: relative;
    opacity: 0.6; transform: scale(0.96); border: 2px solid transparent;
    display: flex; flex-direction: column;
}
.role-card.card-active {
    opacity: 1; transform: scale(1);
    border-color: var(--primary);
    box-shadow: 0 20px 60px rgba(0,0,0,0.4), 0 0 30px rgba(139, 92, 246, 0.2);
}
.role-card.featured.card-active {
    border-color: var(--accent);
    box-shadow: 0 20px 60px rgba(0,0,0,0.4), 0 0 30px rgba(52, 211, 153, 0.2);
}

.card-visual { font-size: 3.5rem; margin-bottom: 25px; }
.role-card h3 { font-size: 1.6rem; margin-bottom: 15px; font-weight: 800; }
.role-card p { color: var(--text-dim); margin-bottom: 25px; flex-grow: 1; }
.price { font-size: 2.5rem; font-weight: 900; margin: 30px 0; letter-spacing: -1px; color: #fff; }
.features-list { list-style: none; margin-bottom: 40px; text-align: left; display: inline-block; }
.features-list li { margin-bottom: 14px; color: var(--text-dim); display: flex; align-items: center; font-weight: 600; }
.features-list i { color: var(--accent); margin-right: 12px; font-size: 1.1rem; filter: drop-shadow(0 0 5px var(--accent-glow)); }

.badge {
    position: absolute; top: -12px; right: 20px;
    background: linear-gradient(90deg, var(--accent), var(--primary)); color: #fff;
    padding: 8px 16px; border-radius: 50px; font-weight: 800; font-size: 0.75rem;
    box-shadow: 0 5px 15px rgba(52, 211, 153, 0.4); letter-spacing: 0.5px;
}
.btn-card {
    display: block; width: 100%; padding: 18px; border: 2px solid rgba(255,255,255,0.1);
    border-radius: 16px; color: #fff; text-decoration: none; transition: 0.3s; font-weight: 700;
    margin-top: auto;
}
.btn-card:hover { background: #fff; color: var(--bg-dark); border-color: #fff; }

/* --- FOOTER & TERS MARQUEE ( \ YÖNÜNDE EĞİM ) --- */
.main-footer {
    border-top: 1px solid var(--border); margin-top: 150px; /* Daha fazla boşluk */
    background: linear-gradient(to bottom, var(--bg-dark), #05070c);
    position: relative;
}
.footer-tilt-wrapper {
    /* Sponsorların tam tersi eğim: +3 derece */
    transform: rotate(3deg) scale(1.05);
    background: rgba(139, 92, 246, 0.05);
    border-top: 1px solid rgba(255,255,255,0.05);
    border-bottom: 1px solid rgba(255,255,255,0.05);
    padding: 20px 0;
    width: 105%; margin-left: -2.5%; /* Genişlik taşması */
    margin-top: -60px; /* Footer'ın üstüne binmesi için negatif margin */
    margin-bottom: 80px;
    backdrop-filter: blur(5px);
    position: relative;
    z-index: 1;
}
.marquee-track-reverse {
    display: flex; gap: 60px; white-space: nowrap;
    animation: scroll-reverse 40s linear infinite; /* SAĞA DOĞRU AKAR */
    align-items: center;
}
/* SAĞA AKIŞ ANİMASYONU */
@keyframes scroll-reverse {
    0% { transform: translateX(-50%); }
    100% { transform: translateX(0); }
}

.user-item {
    font-size: 1.1rem; font-weight: 600; color: #fff; display: flex; align-items: center; gap: 10px;
}
.user-item i { color: var(--accent); }
.dot { color: var(--text-dim); opacity: 0.3; }

.footer-inner { display: flex; justify-content: space-between; align-items: start; margin-bottom: 60px; position: relative; z-index: 2; }
.footer-brand p { color: var(--text-dim); max-width: 300px; font-size: 1.1rem; }
.footer-links { display: flex; gap: 25px; font-size: 1.8rem; }
.footer-links a { color: var(--text-dim); transition: 0.3s var(--ease-elastic); }
.footer-links a:hover { color: var(--accent); transform: translateY(-5px) scale(1.1); filter: drop-shadow(0 0 10px var(--accent)); }
.footer-bottom { text-align: center; color: var(--text-dim); font-size: 0.9rem; border-top: 1px solid var(--border); padding: 40px 0; opacity: 0.7; }

/* --- ANIMATIONS --- */
.animate-on-scroll { opacity: 0; transform: translateY(40px) scale(0.98); transition: 1s var(--ease-elastic); }
.animate-on-scroll.in-view { opacity: 1; transform: translateY(0) scale(1); }
@keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
.fade-in { animation: fadeEffect 0.5s ease-in-out; }
@keyframes fadeEffect { from { opacity: 0; } to { opacity: 1; } }

/* --- RESPONSIVE --- */
@media (max-width: 992px) {
    .feature-tabs { grid-template-columns: 1fr; gap: 40px; }
    .tabs-list { flex-direction: row; overflow-x: auto; padding-bottom: 20px; }
    .tab-btn { flex-shrink: 0; padding: 20px; }
    .tab-display { min-height: auto; padding: 40px 30px; }

    .nav-menu {
        position: fixed; top: 0; right: -100%; width: 85%; height: 100vh;
        background: rgba(11, 15, 25, 0.95); backdrop-filter: blur(20px);
        flex-direction: column; justify-content: center; transition: 0.5s var(--ease-elastic); padding: 40px;
        font-size: 1.2rem; gap: 40px;
    }
    .nav-menu.is-open { right: 0; box-shadow: -10px 0 30px rgba(0,0,0,0.5); }
    .hamburger { display: block; z-index: 101; }
    .hero-title { font-size: 3.5rem; }
    .sponsors-tilt { transform: rotate(-3deg) scale(1.1); width: 115%; margin-left: -7.5%; }
    /* Mobilde de footer tilt ayarı */
    .footer-tilt-wrapper { transform: rotate(3deg) scale(1.1); width: 115%; margin-left: -7.5%; margin-top: -40px; }
    .role-cards-wrapper { gap: 60px; }
    .footer-inner { flex-direction: column; gap: 40px; align-items: center; text-align: center; }
}
@media (max-width: 768px) {
    .hero-section { padding: 140px 0 80px; }
    .btn-xl { width: 100%; justify-content: center; }
    .hero-cta { flex-direction: column; }
    .comparison-section { padding: 60px 24px; }
    .table-card { padding: 30px 20px; }
    th, td { padding: 15px; }
    .role-card { width: 100%; max-width: 400px; }
}
</style>
