<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

const isMenuOpen = ref(false);
const activeTab = ref('privacy');
const roleSelection = ref('leader');

const cursorDot = ref(null);
const cursorOutline = ref(null);

const handleMouseMove = (e) => {
    if (!cursorDot.value || !cursorOutline.value) return;
    const { clientX, clientY } = e;

    cursorDot.value.style.left = `${clientX}px`;
    cursorDot.value.style.top = `${clientY}px`;

    cursorOutline.value.animate(
        { left: `${clientX}px`, top: `${clientY}px` },
        { duration: 350, fill: 'forwards' }
    );
};

onMounted(() => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('section-in-view');
                }
            });
        },
        { threshold: 0.16 }
    );

    document.querySelectorAll('.section-animate').forEach((el) => observer.observe(el));

    if (window.matchMedia('(min-width: 1024px)').matches) {
        document.body.classList.add('custom-cursor-active');
        window.addEventListener('mousemove', handleMouseMove);

        document.addEventListener('mouseover', (e) => {
            const target = e.target;
            if (
                target.tagName === 'A' ||
                target.tagName === 'BUTTON' ||
                target.closest('.hover-trigger')
            ) {
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
    document.body.classList.remove('cursor-hover');
});

const toggleMenu = () => (isMenuOpen.value = !isMenuOpen.value);
</script>

<template>
    <Head title="COMMUN | İletişimi Özgürleştir" />

    <div class="page-root">
        <!-- background -->
        <div class="bg-pattern"></div>
        <div class="bg-noise"></div>

        <!-- custom cursor -->
        <div ref="cursorDot" class="cursor-dot"></div>
        <div ref="cursorOutline" class="cursor-outline"></div>

        <!-- NAVBAR -->
        <nav class="nav-fixed">
            <div class="nav-inner">
                <div class="nav-left">
                    <div class="logo-mark">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div class="logo-text">
                        <span class="logo-main">COMMUN</span>
                        <span class="logo-sub">privacy-first notices</span>
                    </div>
                </div>

                <div class="nav-center">
                    <a href="#features" class="nav-link hover-trigger">Özellikler</a>
                    <a href="#community" class="nav-link hover-trigger">Topluluklar</a>
                    <a href="#roles" class="nav-link hover-trigger">Kullanım</a>
                </div>

                <div class="nav-right">
                    <div v-if="canLogin" class="nav-auth">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="btn-outline hover-trigger"
                        >
                            Panele Git
                        </Link>
                        <template v-else>
                            <Link :href="route('login')" class="nav-login hover-trigger">
                                Giriş
                            </Link>
                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="btn-primary hover-trigger"
                            >
                                Ücretsiz Başla
                            </Link>
                        </template>
                    </div>

                    <button
                        type="button"
                        class="nav-toggle hover-trigger"
                        @click="toggleMenu"
                    >
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <!-- mobile -->
            <div v-if="isMenuOpen" class="nav-mobile">
                <a href="#features" @click="isMenuOpen = false" class="nav-mobile-link">
                    Özellikler
                </a>
                <a href="#community" @click="isMenuOpen = false" class="nav-mobile-link">
                    Topluluklar
                </a>
                <a href="#roles" @click="isMenuOpen = false" class="nav-mobile-link">
                    Kullanım
                </a>

                <div v-if="canLogin" class="nav-mobile-auth">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="btn-primary w-full hover-trigger"
                        @click="isMenuOpen = false"
                    >
                        Panele Git
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="btn-outline w-full hover-trigger"
                            @click="isMenuOpen = false"
                        >
                            Giriş
                        </Link>
                        <Link
                            v-if="canRegister"
                            :href="route('register')"
                            class="btn-primary w-full hover-trigger"
                            @click="isMenuOpen = false"
                        >
                            Ücretsiz Başla
                        </Link>
                    </template>
                </div>
            </div>
        </nav>

        <!-- MAIN -->
        <main class="main-wrapper">
            <!-- HERO -->
            <section class="section-animate hero">
                <div class="hero-grid">
                    <!-- left -->
                    <div class="hero-left">
                        <div class="hero-chip">
                            <span class="chip-dot"></span>
                            %100 Gizlilik · Tek Yönlü Duyuru · Ücretsiz
                        </div>

                        <h1 class="hero-title">
                            Numaran görünmesin,
                            <span class="hero-gradient">topluluk elinin altında olsun.</span>
                        </h1>

                        <p class="hero-desc">
                            Üniversite kulüpleri, apartmanlar ve organizasyonlar için tasarlanmış, telefon
                            numarası ifşası olmadan duyuru yapabileceğin sade bir iletişim katmanı.
                        </p>

                        <div class="hero-cta">
                            <Link
                                :href="route('register')"
                                class="btn-primary btn-lg hover-trigger"
                            >
                                Topluluk Oluştur
                                <i class="fas fa-arrow-right"></i>
                            </Link>
                            <a href="#features" class="hero-secondary hover-trigger">
                                <span class="hero-secondary-icon">
                                    <i class="fas fa-play"></i>
                                </span>
                                Nasıl çalışıyor?
                            </a>
                        </div>

                        <div class="hero-metrics">
                            <div class="metric">
                                <span class="metric-value">10K+</span>
                                <span class="metric-label">gizli üye</span>
                            </div>
                            <span class="metric-divider"></span>
                            <div class="metric">
                                <span class="metric-value">250+</span>
                                <span class="metric-label">topluluk</span>
                            </div>
                            <span class="metric-divider hide-sm"></span>
                            <div class="metric hide-sm">
                                <span class="metric-value">0</span>
                                <span class="metric-label">gereksiz sohbet</span>
                            </div>
                        </div>
                    </div>

                    <!-- right: device mock -->
                    <div class="hero-right">
                        <div class="device-frame">
                            <div class="device-notch"></div>
                            <div class="device-inner">
                                <div class="device-header">
                                    <span class="badge badge-soft">
                                        <span class="badge-dot"></span>
                                        Duyuru Kanalı
                                    </span>
                                    <span class="badge badge-outline">Gizli Mod</span>
                                </div>

                                <div class="device-list">
                                    <div class="notif-card primary">
                                        <div class="notif-icon">
                                            <i class="fas fa-university"></i>
                                        </div>
                                        <div class="notif-content">
                                            <div class="notif-title">
                                                Bilgisayar Müh. Topluluğu
                                            </div>
                                            <div class="notif-text">
                                                Yarın 19:00 kariyer buluşması. Numaran görünmeden katıl.
                                            </div>
                                            <div class="notif-meta">
                                                <span>2 dk önce</span>
                                                <span class="pill pill-soft">Etkinlik</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="notif-card">
                                        <div class="notif-icon">
                                            <i class="fas fa-building"></i>
                                        </div>
                                        <div class="notif-content">
                                            <div class="notif-title">Vadi Sitesi Duyuru</div>
                                            <div class="notif-text">
                                                Asansör bakımı 13:00–15:00 arası. Sadece bildirim, sohbet yok.
                                            </div>
                                            <div class="notif-meta">
                                                <span>1 saat önce</span>
                                                <span class="pill pill-outline">Acil</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="notif-card">
                                        <div class="notif-icon">
                                            <i class="fas fa-gamepad"></i>
                                        </div>
                                        <div class="notif-content">
                                            <div class="notif-title">E-Spor Kulübü</div>
                                            <div class="notif-text">
                                                Turnuva kayıtlarını QR kodu tara, tek tıkla katıl.
                                            </div>
                                            <div class="notif-meta">
                                                <span>Bugün</span>
                                                <span class="pill pill-soft">
                                                    <i class="fas fa-qrcode"></i> QR Katılım
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="device-footer">
                                    <span>
                                        <i class="fas fa-user-secret"></i> Numaran görünmez
                                    </span>
                                    <span>
                                        <i class="fas fa-bell"></i> Sadece duyuru
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SPONSORS -->
            <section id="community" class="section-animate sponsors">
                <div class="sponsors-head">
                    <div>
                        <p class="eyebrow">erken kullanan topluluklar</p>
                        <h2>Türkiye genelinde topluluklar COMMUN'a geçiyor.</h2>
                    </div>
                    <p class="sponsors-note">
                        Klasik WhatsApp gruplarını bırakıp sadece duyuru kullanan yüzlerce topluluk.
                        Bu site tanıtım amaclıdır ve aşagıda bulunan kurumlarla ve topluluklarla bir alakası yoktur
                    </p>
                </div>

                <div class="sponsors-marquee-wrap">
                    <div class="sponsors-strip">
                        <!-- BURADAKİ IMG SRC'LERİ KENDİ LOGOLARINLA DEĞİŞTİREBİLİRSİN -->
                        <div class="sponsor-item">
                            <div class="sponsor-logo">
                                <img src="https://yt3.googleusercontent.com/ytc/AIdro_nnpxTpH03bCbzY669bkxx1TZwASriiRHijPaL9NaM3kSI=s900-c-k-c0x00ffffff-no-rj" alt="İnönü Üniversitesi" />
                            </div>
                            <div class="sponsor-label">İnönü Üniversitesi</div>
                        </div>

                        <div class="sponsor-item">
                            <div class="sponsor-logo">
                                <img src="https://yt3.googleusercontent.com/ytc/AIdro_nnpxTpH03bCbzY669bkxx1TZwASriiRHijPaL9NaM3kSI=s900-c-k-c0x00ffffff-no-rj" alt="Antalya Bilim Üniversitesi" />
                            </div>
                            <div class="sponsor-label">Antalya Bilim Üniversitesi</div>
                        </div>
  <div class="sponsor-item">
                            <div class="sponsor-logo">
                                <img src="https://yt3.googleusercontent.com/ytc/AIdro_nnpxTpH03bCbzY669bkxx1TZwASriiRHijPaL9NaM3kSI=s900-c-k-c0x00ffffff-no-rj" alt="Antalya Bilim Üniversitesi" />
                            </div>
                            <div class="sponsor-label">Antalya Belek Üniversitesi</div>
                        </div>

                        <div class="sponsor-item">
                            <div class="sponsor-logo">
                                <img src="https://www.adalet.gen.tr/wp-content/uploads/2016/07/adalet-bakanligi-logo.jpg" alt="Gebze Teknik Üniversitesi" />
                            </div>
                            <div class="sponsor-label">Gebze Teknik Üniversitesi</div>
                        </div>

                        <div class="sponsor-item">
                            <div class="sponsor-logo">
                                <img src="https://www.adalet.gen.tr/wp-content/uploads/2016/07/adalet-bakanligi-logo.jpg" alt="Vadi Sitesi" />
                            </div>
                            <div class="sponsor-label">Vadi Sitesi Yönetimi</div>
                        </div>

                        <div class="sponsor-item">
                            <div class="sponsor-logo">
                                <img src="https://www.adalet.gen.tr/wp-content/uploads/2016/07/adalet-bakanligi-logo.jpg" alt="E-Spor Kulübü" />
                            </div>
                            <div class="sponsor-label">E-Spor Kulübü</div>
                        </div>

                        <div class="sponsor-item">
                            <div class="sponsor-logo">
                                <img src="https://www.adalet.gen.tr/wp-content/uploads/2016/07/adalet-bakanligi-logo.jpg" alt="İTÜ Yazılım" />
                            </div>
                            <div class="sponsor-label">İTÜ Yazılım Topluluğu</div>
                        </div>

                        <!-- tekrarlar (sonsuz akış için) -->
                        <div class="sponsor-item">
                            <div class="sponsor-logo">
                                <img src="https://www.adalet.gen.tr/wp-content/uploads/2016/07/adalet-bakanligi-logo.jpg" alt="İnönü Üniversitesi" />
                            </div>
                            <div class="sponsor-label">İnönü Üniversitesi</div>
                        </div>

                        <div class="sponsor-item">
                            <div class="sponsor-logo">
                                <img src="https://www.adalet.gen.tr/wp-content/uploads/2016/07/adalet-bakanligi-logo.jpg" alt="Antalya Bilim Üniversitesi" />
                            </div>
                            <div class="sponsor-label">Antalya Bilim Üniversitesi</div>
                        </div>

                        <div class="sponsor-item">
                            <div class="sponsor-logo">
                                <img src="https://www.adalet.gen.tr/wp-content/uploads/2016/07/adalet-bakanligi-logo.jpg" alt="Vadi Sitesi" />
                            </div>
                            <div class="sponsor-label">Vadi Sitesi Yönetimi</div>
                        </div>

                        <div class="sponsor-item">
                            <div class="sponsor-logo">
                                <img src="https://www.adalet.gen.tr/wp-content/uploads/2016/07/adalet-bakanligi-logo.jpg" alt="E-Spor Kulübü" />
                            </div>
                            <div class="sponsor-label">E-Spor Kulübü</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- FEATURES -->
            <section id="features" class="section-animate features">
                <div class="section-title">
                    <h2>Sistemin kalbi.</h2>
                    <p>Gürültüsüz, sade ve güvenli bir bildirim katmanı.</p>
                </div>

                <div class="features-grid">
                    <!-- tabs -->
                    <div class="features-tabs">
                        <button
                            class="tab-btn hover-trigger"
                            :class="{ active: activeTab === 'privacy' }"
                            @click="activeTab = 'privacy'"
                        >
                            <div class="tab-icon">
                                <i class="fas fa-user-secret"></i>
                            </div>
                            <div class="tab-text">
                                <span class="tab-kicker">Gizlilik</span>
                                <span class="tab-title">Gizlilik Kalkanı</span>
                                <span class="tab-desc">
                                    Telefon numaran hiçbir yerde görünmez, lider bile sadece ID görür.
                                </span>
                            </div>
                        </button>

                        <button
                            class="tab-btn hover-trigger"
                            :class="{ active: activeTab === 'notify' }"
                            @click="activeTab = 'notify'"
                        >
                            <div class="tab-icon">
                                <i class="fas fa-bell-slash"></i>
                            </div>
                            <div class="tab-text">
                                <span class="tab-kicker">Gürültü yok</span>
                                <span class="tab-title">Sadece duyuru</span>
                                <span class="tab-desc">
                                    Sohbet, sticker, spam yok. Sadece etkinlik, duyuru, acil durum.
                                </span>
                            </div>
                        </button>

                        <button
                            class="tab-btn hover-trigger"
                            :class="{ active: activeTab === 'easy' }"
                            @click="activeTab = 'easy'"
                        >
                            <div class="tab-icon">
                                <i class="fas fa-qrcode"></i>
                            </div>
                            <div class="tab-text">
                                <span class="tab-kicker">Kolay katılım</span>
                                <span class="tab-title">QR ile anında</span>
                                <span class="tab-desc">
                                    Link veya QR kod ile saniyeler içinde katılım, uygulama zorunlu değil.
                                </span>
                            </div>
                        </button>
                    </div>

                    <!-- panel -->
                    <div class="features-panel">
                        <div v-if="activeTab === 'privacy'" class="panel-content">
                            <h3>Kimliğin gerçekten gizli.</h3>
                            <p>
                                WhatsApp grubuna girdiğinde numaran anında onlarca kişiye görünür. COMMUN'da
                                ise sadece sistemde kayıtlı anonim bir profilsin. Tüm yönetim işlemleri
                                anonim ID üzerinden ilerler.
                            </p>
                            <ul>
                                <li>
                                    <i class="fas fa-check"></i>
                                    Numaran hiçbir listede, exportta veya panelde tutulmaz.
                                </li>
                                <li>
                                    <i class="fas fa-check"></i>
                                    Liderler sadece üye sayısı ve okunma oranı görür.
                                </li>
                            </ul>
                        </div>

                        <div v-else-if="activeTab === 'notify'" class="panel-content">
                            <h3>Gürültü değil, bilgi gelir.</h3>
                            <p>
                                “Günaydın” mesajları, sticker yağmuru ve alakasız sohbetler gitgide yorucu.
                                COMMUN ise sadece duyuru ve bildirim için tasarlandı.
                            </p>
                            <ul>
                                <li>
                                    <i class="fas fa-check"></i>
                                    Topluluk bazlı sessize alma ve öncelikli bildirim ayarları.
                                </li>
                                <li>
                                    <i class="fas fa-check"></i>
                                    Etkinlik, duyuru ve acil türleri ile anlamlı klasifikasyon.
                                </li>
                            </ul>
                        </div>

                        <div v-else class="panel-content">
                            <h3>Uygulama zorunlu değil.</h3>
                            <p>
                                Topluluk lideri link veya QR kodu paylaşır, üyeler tek tıkla bildirim
                                kanalına katılır. Dileyen sadece web üzerinden takip eder.
                            </p>
                            <ul>
                                <li>
                                    <i class="fas fa-check"></i>
                                    Dakikalar içinde topluluk oluştur, linki paylaş, iş bitti.
                                </li>
                                <li>
                                    <i class="fas fa-check"></i>
                                    QR kodu panoya as, isteyen okutup anında kanala dahil olsun.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            <!-- ROLES -->
            <section id="roles" class="section-animate roles">
                <div class="section-title">
                    <h2>Sen hangi taraftasın?</h2>
                    <p>Topluluğa katılan üye de olabilirsin, yüzlerce kişiye duyuru gönderen lider de.</p>
                </div>

                <div class="role-toggle">
                    <button
                        class="hover-trigger"
                        :class="{ active: roleSelection === 'member' }"
                        @click="roleSelection = 'member'"
                    >
                        Topluluk Üyesi
                    </button>
                    <button
                        class="hover-trigger"
                        :class="{ active: roleSelection === 'leader' }"
                        @click="roleSelection = 'leader'"
                    >
                        Topluluk Lideri
                    </button>
                </div>

                <div class="roles-grid">
                    <div
                        class="role-card"
                        :class="{ active: roleSelection === 'member' }"
                    >
                        <div class="role-icon">
                            <i class="fas fa-user-astronaut"></i>
                        </div>
                        <h3>Öğrenci / Üye</h3>
                        <p>Duyuruları sessiz ama eksiksiz takip etmek isteyenler için.</p>
                        <div class="role-price">ÜCRETSİZ</div>
                        <ul>
                            <li><i class="fas fa-check"></i> Numaran gizli kalır, kimse göremez.</li>
                            <li><i class="fas fa-check"></i> Sınırsız sayıda topluluğa katılım.</li>
                            <li><i class="fas fa-check"></i> Reklamsız, sade deneyim.</li>
                        </ul>
                        <Link :href="route('register')" class="btn-outline hover-trigger">
                            Hemen Katıl
                        </Link>
                    </div>

                    <div
                        class="role-card featured"
                        :class="{ active: roleSelection === 'leader' }"
                    >
                        <div class="role-badge">POPÜLER</div>
                        <div class="role-icon">
                            <i class="fas fa-crown"></i>
                        </div>
                        <h3>Yönetici / Lider</h3>
                        <p>Kulüp başkanları, apartman yöneticileri ve temsilciler için.</p>
                        <div class="role-price">ÜCRETSİZ</div>
                        <ul>
                            <li><i class="fas fa-check"></i> Dakikalar içinde topluluk oluştur.</li>
                            <li><i class="fas fa-check"></i> QR kod ile hızlı üye toplama.</li>
                            <li><i class="fas fa-check"></i> Anlık bildirim ve okunma istatistikleri.</li>
                        </ul>
                        <Link :href="route('register')" class="btn-primary hover-trigger">
                            Topluluk Oluştur
                        </Link>
                    </div>
                </div>
            </section>

            <!-- TESTIMONIALS MARQUEE (footer üstü) -->
            <section id="testimonials" class="section-animate testimonials">
                <div class="testimonials-head">
                    <div>
                        <p class="eyebrow">kullananlar ne diyor?</p>
                        <h2>Gerçek topluluklardan kısa yorumlar.</h2>
                    </div>
                    <p class="testimonials-note">
                        Kulüp başkanları, site yöneticileri ve topluluk liderleri COMMUN deneyimini anlatıyor.
                    </p>
                </div>

                <div class="testimonials-marquee-wrap">
                    <div class="testimonials-strip">
                        <div class="testimonial-chip">
                            <div class="chip-avatar">AY</div>
                            <div class="chip-body">
                                <div class="chip-name">Ahmet Y.</div>
                                <div class="chip-role">ODTÜ IEEE Kulüp Başkanı</div>
                                <div class="chip-quote">
                                    “Numara paylaşmadan etkinlik duyurmak bizim için büyük rahatlık.”
                                </div>
                            </div>
                        </div>

                        <div class="testimonial-chip">
                            <div class="chip-avatar">ZK</div>
                            <div class="chip-body">
                                <div class="chip-name">Zeynep K.</div>
                                <div class="chip-role">Vadi Sitesi Yöneticisi</div>
                                <div class="chip-quote">
                                    “Apartmanda sadece önemli duyurular geliyor, grup kirliliği yok.”
                                </div>
                            </div>
                        </div>

                        <div class="testimonial-chip">
                            <div class="chip-avatar">MS</div>
                            <div class="chip-body">
                                <div class="chip-name">Mustafa S.</div>
                                <div class="chip-role">E-Spor Kulübü Koordinatörü</div>
                                <div class="chip-quote">
                                    “Turnuva kayıtlarını COMMUN üzerinden toplamak çok daha net.”
                                </div>
                            </div>
                        </div>

                        <div class="testimonial-chip">
                            <div class="chip-avatar">İY</div>
                            <div class="chip-body">
                                <div class="chip-name">İTÜ Yazılım</div>
                                <div class="chip-role">Topluluk Ekibi</div>
                                <div class="chip-quote">
                                    “Sadece duyuru kanalı olması, üyeleri yormadan bilgi vermeyi sağlıyor.”
                                </div>
                            </div>
                        </div>

                        <!-- tekrarlar -->
                        <div class="testimonial-chip">
                            <div class="chip-avatar">AY</div>
                            <div class="chip-body">
                                <div class="chip-name">Ahmet Y.</div>
                                <div class="chip-role">ODTÜ IEEE Kulüp Başkanı</div>
                                <div class="chip-quote">
                                    “WhatsApp yerine COMMUN kullanmak üyelerin işine geldi.”
                                </div>
                            </div>
                        </div>

                        <div class="testimonial-chip">
                            <div class="chip-avatar">ZK</div>
                            <div class="chip-body">
                                <div class="chip-name">Zeynep K.</div>
                                <div class="chip-role">Vadi Sitesi Yöneticisi</div>
                                <div class="chip-quote">
                                    “Acil durumlarda herkese tek tıkla bildirim gönderebiliyorum.”
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- FOOTER -->
        <footer class="footer">
            <div class="footer-inner">
                <!-- brand -->
                <div class="footer-col left">
                    <div class="footer-logo">
                        <span class="logo-mark small">
                            <i class="fas fa-bolt"></i>
                        </span>
                        <span class="logo-main">COMMUN</span>
                    </div>
                    <p>
                        İletişimi özgürleştiren, gizliliği koruyan modern topluluk platformu. Numara ifşası
                        olmadan duyuru ve bildirim katmanı.
                    </p>
                    <div class="footer-social">
                        <a href="#" class="hover-trigger">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="hover-trigger">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="hover-trigger">
                            <i class="fab fa-github"></i>
                        </a>
                    </div>
                </div>

                <!-- app + qr -->
                <div class="footer-col right">
                    <div class="footer-top-row">
                        <div class="footer-app">
                            <h3>Mobil uygulama ile de yönet.</h3>
                            <p>
                                Topluluklarını hem web üzerinden hem de mobil uygulama ile kolayca
                                yönetebileceğin bir yapı için tasarlandı.
                            </p>

                            <div class="footer-app-body">
                                <div class="phone-figure">
                                    <!-- BURAYA UYGULAMA LOGONU KOYABİLİRSİN -->
                                    <img
                                        src="/images/app-logo.png"
                                        alt="COMMUN App"
                                        class="app-logo-img"
                                    />
                                </div>

                                <div class="store-badges">
                                    <div class="store-badge">
                                        <i class="fab fa-apple"></i>
                                        <span>App Store</span>
                                    </div>
                                    <div class="store-badge">
                                        <i class="fab fa-google-play"></i>
                                        <span>Google Play</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="footer-qr-block">
                            <h3>QR ile hızlı giriş.</h3>
                            <p>
                                Topluluğunun linkini ve QR kodunu paylaş, üyelerin saniyeler içinde güvenli
                                kanala katılsın.
                            </p>
                            <div class="footer-qr">
                                <div class="qr-box">
                                    <!-- BURAYA KENDİ QR GÖRSELİNİ KOY -->
                                    <img
                                        src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg"
                                        alt="COMMUN QR Kodu"
                                        class="qr-img"
                                    />
                                </div>
                                <ul class="qr-info">
                                    <li><i class="fas fa-check"></i> Fiziksel afişlere QR ekleyebilirsin.</li>
                                    <li><i class="fas fa-check"></i> Numara görünmeden katılım sağlanır.</li>
                                    <li><i class="fas fa-check"></i> %100 gizlilik odaklı altyapı.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <span>&copy; 2025 COMMUN</span>
                <span>Laravel v{{ laravelVersion }}</span>
                <span>PHP v{{ phpVersion }}</span>
            </div>
        </footer>
    </div>
</template>

<style>
:root {
    --clr-bg: #31473a;
    --clr-bg-soft: #3a5645;
    --clr-accent: #7c8363;
    --clr-accent-soft: #9aa27a;
    --clr-surface: #edf4f2;
    --clr-surface-soft: #e1ebe6;
    --clr-text-main: #f8faf9;
    --clr-text-muted: #d0ddd7;
}

/* ROOT */
.page-root {
    min-height: 100vh;
    background: radial-gradient(circle at top, #3b5a48 0%, #31473a 40%, #233128 100%);
    color: var(--clr-text-main);
    font-family: system-ui, -apple-system, BlinkMacSystemFont, 'SF Pro Text', sans-serif;
    position: relative;
    overflow-x: hidden;
    font-size: 15px;
}

/* background */
.bg-pattern {
    position: fixed;
    inset: 0;
    z-index: -2;
    opacity: 0.3;
    background-image: radial-gradient(circle at 1px 1px, rgba(237, 244, 242, 0.16) 1px, transparent 0);
    background-size: 26px 26px;
}
.bg-noise {
    position: fixed;
    inset: 0;
    z-index: -1;
    opacity: 0.16;
    pointer-events: none;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.8'/%3E%3C/svg%3E");
}

/* custom cursor */
.cursor-dot {
    position: fixed;
    width: 7px;
    height: 7px;
    background: var(--clr-surface);
    border-radius: 999px;
    pointer-events: none;
    z-index: 9999;
    transform: translate(-50%, -50%);
    display: none;
    box-shadow: 0 0 20px rgba(237, 244, 242, 0.9);
}
.cursor-outline {
    position: fixed;
    width: 32px;
    height: 32px;
    border: 1px solid rgba(237, 244, 242, 0.7);
    border-radius: 999px;
    pointer-events: none;
    z-index: 9998;
    transform: translate(-50%, -50%);
    display: none;
    transition:
        width 0.22s cubic-bezier(0.34, 1.56, 0.64, 1),
        height 0.22s cubic-bezier(0.34, 1.56, 0.64, 1),
        background 0.2s,
        border-color 0.2s;
}
body.custom-cursor-active .cursor-dot,
body.custom-cursor-active .cursor-outline {
    display: block;
}
body.custom-cursor-active {
    cursor: none;
}
body.cursor-hover .cursor-outline {
    width: 52px;
    height: 52px;
    background: rgba(0, 0, 0, 0.18);
    border-color: var(--clr-surface);
}

/* NAVBAR */
.nav-fixed {
    position: fixed;
    inset: 0 0 auto;
    z-index: 40;
    border-bottom: 1px solid rgba(237, 244, 242, 0.12);
    background: linear-gradient(to bottom, rgba(49, 71, 58, 0.96), rgba(49, 71, 58, 0.9));
    backdrop-filter: blur(18px);
}
.nav-inner {
    max-width: 1120px;
    margin: 0 auto;
    padding: 11px 16px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.nav-left {
    display: flex;
    align-items: center;
    gap: 10px;
}
.logo-mark {
    width: 36px;
    height: 36px;
    border-radius: 14px;
    background: radial-gradient(circle at 30% 20%, var(--clr-surface-soft), var(--clr-accent));
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--clr-bg);
    box-shadow:
        0 0 20px rgba(237, 244, 242, 0.5),
        0 0 0 1px rgba(237, 244, 242, 0.2);
}
.logo-mark.small {
    width: 30px;
    height: 30px;
    border-radius: 12px;
}
.logo-mark i {
    font-size: 0.95rem;
}
.logo-text {
    display: flex;
    flex-direction: column;
    line-height: 1.1;
}
.logo-main {
    font-weight: 800;
    letter-spacing: -0.04em;
    font-size: 1.2rem;
}
.logo-sub {
    font-size: 0.68rem;
    letter-spacing: 0.17em;
    text-transform: uppercase;
    color: rgba(237, 244, 242, 0.7);
}
.nav-center {
    display: flex;
    align-items: center;
    gap: 26px;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.16em;
}
.nav-link {
    position: relative;
    color: rgba(237, 244, 242, 0.75);
    text-decoration: none;
    font-weight: 600;
}
.nav-link::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: -6px;
    width: 0;
    height: 2px;
    border-radius: 999px;
    background: var(--clr-surface);
    transition: width 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.nav-link:hover {
    color: var(--clr-surface);
}
.nav-link:hover::after {
    width: 100%;
}
.nav-right {
    display: flex;
    align-items: center;
    gap: 12px;
}
.nav-auth {
    display: flex;
    align-items: center;
    gap: 8px;
}
.nav-login {
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--clr-surface);
    text-decoration: none;
}
.nav-toggle {
    display: none;
    border: none;
    background: transparent;
    color: var(--clr-surface);
    width: 34px;
    height: 34px;
    border-radius: 999px;
    border: 1px solid rgba(237, 244, 242, 0.25);
}
.nav-toggle i {
    font-size: 1rem;
}

/* mobile nav */
.nav-mobile {
    max-width: 1120px;
    margin: 0 auto;
    padding: 8px 16px 12px;
    display: none;
    flex-direction: column;
    gap: 6px;
    font-size: 0.9rem;
    background: rgba(49, 71, 58, 0.96);
}
.nav-mobile-link {
    padding: 9px 4px;
    border-radius: 8px;
    color: var(--clr-surface);
    text-decoration: none;
}
.nav-mobile-link:hover {
    background: rgba(237, 244, 242, 0.06);
}
.nav-mobile-auth {
    margin-top: 6px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

/* BUTTONS */
.btn-primary,
.btn-outline {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 8px 18px;
    border-radius: 999px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition:
        transform 0.18s cubic-bezier(0.34, 1.56, 0.64, 1),
        box-shadow 0.18s,
        background 0.18s,
        color 0.18s;
}
.btn-primary {
    background: var(--clr-accent);
    color: var(--clr-bg);
    border: 1px solid rgba(237, 244, 242, 0.4);
    box-shadow:
        0 8px 20px rgba(0, 0, 0, 0.35),
        0 0 0 1px rgba(237, 244, 242, 0.18);
}
.btn-primary:hover {
    transform: translateY(-1px);
    background: var(--clr-accent-soft);
    box-shadow:
        0 12px 26px rgba(0, 0, 0, 0.4),
        0 0 0 1px rgba(237, 244, 242, 0.3);
}
.btn-outline {
    background: rgba(0, 0, 0, 0.2);
    color: var(--clr-surface);
    border: 1px solid rgba(237, 244, 242, 0.35);
}
.btn-outline:hover {
    transform: translateY(-1px);
    background: rgba(0, 0, 0, 0.35);
}
.btn-lg {
    padding: 10px 26px;
    font-size: 1rem;
}

/* MAIN WRAPPER */
.main-wrapper {
    max-width: 1120px;
    margin: 0 auto;
    padding: 110px 16px 40px; /* nav altında boşluk */
}

/* HERO */
.hero {
    margin-top: 12px;
}
.hero-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.05fr) minmax(0, 0.95fr);
    gap: 34px;
    align-items: center;
}
.hero-chip {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 14px;
    border-radius: 999px;
    border: 1px solid rgba(237, 244, 242, 0.28);
    background: rgba(0, 0, 0, 0.22);
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.16em;
}
.chip-dot {
    width: 7px;
    height: 7px;
    border-radius: 999px;
    background: var(--clr-surface);
    box-shadow: 0 0 9px rgba(237, 244, 242, 0.9);
}
.hero-title {
    margin-top: 20px;
    font-size: clamp(2.2rem, 3.4vw, 2.7rem);
    font-weight: 800;
    letter-spacing: -0.04em;
    line-height: 1.08;
}
.hero-gradient {
    display: block;
    margin-top: 6px;
    background: linear-gradient(90deg, var(--clr-surface), var(--clr-surface-soft), #ffffff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.hero-desc {
    margin-top: 16px;
    max-width: 540px;
    font-size: 1rem;
    color: var(--clr-text-muted);
}
.hero-cta {
    margin-top: 20px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 14px;
}
.hero-secondary {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--clr-surface);
    text-decoration: none;
}
.hero-secondary-icon {
    width: 34px;
    height: 34px;
    border-radius: 999px;
    border: 1px solid rgba(237, 244, 242, 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
}
.hero-secondary-icon i {
    font-size: 0.8rem;
}
.hero-metrics {
    margin-top: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    font-size: 0.82rem;
    color: var(--clr-text-muted);
}
.metric {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.metric-value {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--clr-surface);
}
.metric-label {
    text-transform: uppercase;
    letter-spacing: 0.12em;
    font-size: 0.7rem;
}
.metric-divider {
    width: 1px;
    height: 28px;
    background: linear-gradient(to bottom, transparent, rgba(237, 244, 242, 0.6), transparent);
}

/* DEVICE MOCK */
.hero-right {
    display: flex;
    justify-content: flex-end;
}
.device-frame {
    position: relative;
    width: 100%;
    max-width: 320px;
    border-radius: 26px;
    border: 1px solid rgba(237, 244, 242, 0.25);
    background: radial-gradient(circle at top, #3b5b49, #25372c);
    box-shadow: 0 22px 50px rgba(0, 0, 0, 0.45);
    padding: 10px;
}
.device-notch {
    position: absolute;
    inset: 8px 74px auto;
    height: 14px;
    border-radius: 999px;
    background: rgba(0, 0, 0, 0.6);
}
.device-inner {
    margin-top: 18px;
    padding: 10px;
    border-radius: 20px;
    background: rgba(0, 0, 0, 0.36);
}
.device-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 0.72rem;
    margin-bottom: 8px;
}
.badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border-radius: 999px;
    padding: 3px 8px;
}
.badge-soft {
    background: rgba(237, 244, 242, 0.08);
    color: var(--clr-surface);
}
.badge-dot {
    width: 6px;
    height: 6px;
    border-radius: 999px;
    background: var(--clr-surface-soft);
}
.badge-outline {
    border: 1px solid rgba(237, 244, 242, 0.38);
    color: var(--clr-text-muted);
}
.device-list {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-top: 6px;
}
.notif-card {
    display: flex;
    gap: 7px;
    border-radius: 14px;
    padding: 7px;
    border: 1px solid rgba(237, 244, 242, 0.2);
    background: rgba(0, 0, 0, 0.4);
    font-size: 0.75rem;
}
.notif-card.primary {
    background: linear-gradient(135deg, rgba(237, 244, 242, 0.16), rgba(0, 0, 0, 0.5));
}
.notif-icon {
    width: 24px;
    height: 24px;
    border-radius: 999px;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.notif-icon i {
    font-size: 0.8rem;
}
.notif-content {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.notif-title {
    font-weight: 600;
    color: var(--clr-surface);
}
.notif-text {
    color: var(--clr-text-muted);
}
.notif-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.68rem;
    color: rgba(237, 244, 242, 0.7);
    margin-top: 2px;
}
.pill {
    border-radius: 999px;
    padding: 2px 6px;
}
.pill-soft {
    background: rgba(237, 244, 242, 0.12);
    color: var(--clr-surface);
}
.pill-outline {
    border: 1px solid rgba(237, 244, 242, 0.5);
}
.pill i {
    margin-right: 3px;
    font-size: 0.7rem;
}
.device-footer {
    margin-top: 8px;
    padding: 6px 7px;
    border-radius: 12px;
    background: rgba(0, 0, 0, 0.5);
    font-size: 0.7rem;
    display: flex;
    justify-content: space-between;
    color: var(--clr-text-muted);
}
.device-footer i {
    margin-right: 4px;
}

/* SPONSORS */
.sponsors {
    margin-top: 44px;
}
.sponsors-head {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    justify-content: space-between;
    gap: 14px;
}
.eyebrow {
    font-size: 0.76rem;
    text-transform: uppercase;
    letter-spacing: 0.18em;
    color: rgba(237, 244, 242, 0.7);
}
.sponsors h2 {
    margin-top: 4px;
    font-size: 1.4rem;
    font-weight: 650;
}
.sponsors-note {
    max-width: 360px;
    font-size: 0.9rem;
    color: var(--clr-text-muted);
}
.sponsors-marquee-wrap {
    margin-top: 18px;
    border-radius: 18px;
    border: 1px solid rgba(237, 244, 242, 0.22);
    background: linear-gradient(
        90deg,
        rgba(237, 244, 242, 0.08),
        rgba(49, 71, 58, 0.9),
        rgba(237, 244, 242, 0.08)
    );
    padding: 10px 12px;
    overflow: hidden;
}
.sponsors-strip {
    display: flex;
    align-items: center;
    gap: 18px;
    white-space: nowrap;
    animation: sponsors-scroll 18s linear infinite;
    will-change: transform;
}
.sponsor-item {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 14px;
    background: rgba(0, 0, 0, 0.35);
    color: var(--clr-text-main);
    font-size: 0.9rem;
    border: 1px solid rgba(237, 244, 242, 0.25);
}
.sponsor-logo {
    width: 72px;
    height: 40px;
    border-radius: 10px;
    overflow: hidden;
    background: rgba(0, 0, 0, 0.5);
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.sponsor-logo img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}
.sponsor-label {
    white-space: nowrap;
}
@keyframes sponsors-scroll {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

/* FEATURES */
.features {
    margin-top: 56px;
}
.section-title {
    text-align: center;
    margin-bottom: 20px;
}
.section-title h2 {
    font-size: 1.6rem;
    font-weight: 700;
}
.section-title p {
    margin-top: 5px;
    font-size: 0.97rem;
    color: var(--clr-text-muted);
}
.features-grid {
    display: grid;
    grid-template-columns: minmax(0, 0.9fr) minmax(0, 1.1fr);
    gap: 24px;
    align-items: flex-start;
}
.features-tabs {
    display: flex;
    flex-direction: column;
    gap: 11px;
}
.tab-btn {
    border-radius: 16px;
    border: 1px solid rgba(237, 244, 242, 0.25);
    padding: 9px 11px;
    display: flex;
    gap: 11px;
    background: rgba(0, 0, 0, 0.25);
    cursor: pointer;
    text-align: left;
}
.tab-btn.active {
    background: rgba(237, 244, 242, 0.08);
    border-color: rgba(237, 244, 242, 0.5);
    box-shadow: 0 18px 34px rgba(0, 0, 0, 0.45);
}
.tab-icon {
    width: 34px;
    height: 34px;
    border-radius: 12px;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}
.tab-icon i {
    font-size: 0.9rem;
}
.tab-text {
    display: flex;
    flex-direction: column;
    gap: 2px;
    font-size: 0.9rem;
}
.tab-kicker {
    font-size: 0.78rem;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    color: rgba(237, 244, 242, 0.7);
}
.tab-title {
    font-weight: 600;
}
.tab-desc {
    font-size: 0.8rem;
    color: var(--clr-text-muted);
}
.features-panel {
    border-radius: 20px;
    border: 1px solid rgba(237, 244, 242, 0.35);
    background: rgba(0, 0, 0, 0.35);
    padding: 15px 18px;
    min-height: 180px;
}
.panel-content h3 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 8px;
}
.panel-content p {
    font-size: 0.9rem;
    color: var(--clr-text-muted);
    margin-bottom: 9px;
}
.panel-content ul {
    list-style: none;
    padding: 0;
    margin: 0;
    font-size: 0.86rem;
}
.panel-content li {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 4px;
}
.panel-content li i {
    font-size: 0.75rem;
}

/* ROLES */
.roles {
    margin-top: 56px;
}
.role-toggle {
    margin-top: 12px;
    display: inline-flex;
    border-radius: 999px;
    border: 1px solid rgba(237, 244, 242, 0.25);
    padding: 2px;
}
.role-toggle button {
    padding: 7px 18px;
    border-radius: 999px;
    border: none;
    background: transparent;
    color: var(--clr-text-muted);
    font-size: 0.9rem;
    font-weight: 600;
    cursor: pointer;
}
.role-toggle button.active {
    background: var(--clr-surface);
    color: var(--clr-bg);
}
.roles-grid {
    margin-top: 20px;
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 18px;
}
.role-card {
    position: relative;
    border-radius: 20px;
    border: 1px solid rgba(237, 244, 242, 0.25);
    background: rgba(0, 0, 0, 0.35);
    padding: 16px 18px;
    font-size: 0.9rem;
    display: flex;
    flex-direction: column;
    gap: 7px;
    opacity: 0.78;
    transform: translateY(4px) scale(0.99);
    transition:
        opacity 0.25s,
        transform 0.25s,
        box-shadow 0.25s,
        border-color 0.25s;
}
.role-card.active {
    opacity: 1;
    transform: translateY(0) scale(1);
    box-shadow: 0 18px 40px rgba(0, 0, 0, 0.5);
    border-color: rgba(237, 244, 242, 0.6);
}
.role-card.featured.active {
    border-color: var(--clr-surface);
}
.role-icon {
    width: 38px;
    height: 38px;
    border-radius: 14px;
    background: rgba(237, 244, 242, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
}
.role-icon i {
    font-size: 1rem;
}
.role-card h3 {
    margin-top: 4px;
    font-size: 1.05rem;
    font-weight: 600;
}
.role-card p {
    color: var(--clr-text-muted);
}
.role-price {
    margin-top: 5px;
    font-size: 1.5rem;
    font-weight: 800;
}
.role-card ul {
    list-style: none;
    padding: 0;
    margin: 6px 0 10px;
}
.role-card li {
    display: flex;
    gap: 6px;
    align-items: center;
    margin-bottom: 4px;
}
.role-card li i {
    font-size: 0.8rem;
}
.role-badge {
    position: absolute;
    top: -11px;
    right: 18px;
    padding: 4px 10px;
    border-radius: 999px;
    background: var(--clr-surface);
    color: var(--clr-bg);
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.16em;
    text-transform: uppercase;
}

/* TESTIMONIALS MARQUEE */
.testimonials {
    margin-top: 56px;
}
.testimonials-head {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    justify-content: space-between;
    gap: 14px;
}
.testimonials-head h2 {
    margin-top: 4px;
    font-size: 1.4rem;
    font-weight: 650;
}
.testimonials-note {
    max-width: 380px;
    font-size: 0.9rem;
    color: var(--clr-text-muted);
}
.testimonials-marquee-wrap {
    margin-top: 18px;
    border-radius: 18px;
    border: 1px solid rgba(237, 244, 242, 0.22);
    background: rgba(0, 0, 0, 0.28);
    padding: 12px 10px;
    overflow: hidden;
}
.testimonials-strip {
    display: flex;
    align-items: stretch;
    gap: 14px;
    white-space: nowrap;
    animation: testimonials-scroll 22s linear infinite;
    will-change: transform;
}
.testimonial-chip {
    display: inline-flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 16px;
    background: rgba(0, 0, 0, 0.45);
    border: 1px solid rgba(237, 244, 242, 0.32);
    min-width: 260px;
    max-width: 320px;
    line-height: 1.35;
}
.chip-avatar {
    width: 30px;
    height: 30px;
    border-radius: 999px;
    background: var(--clr-surface-soft);
    color: var(--clr-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 0.9rem;
    flex-shrink: 0;
}
.chip-body {
    display: flex;
    flex-direction: column;
    gap: 1px;
    font-size: 0.86rem;
    white-space: normal;
}
.chip-name {
    font-weight: 600;
}
.chip-role {
    font-size: 0.75rem;
    color: var(--clr-text-muted);
}
.chip-quote {
    margin-top: 3px;
    font-size: 0.8rem;
    color: var(--clr-text-muted);
}
@keyframes testimonials-scroll {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

/* FOOTER */
.footer {
    border-top: 1px solid rgba(237, 244, 242, 0.18);
    background: #27362d;
    margin-top: 40px;
}
.footer-inner {
    max-width: 1120px;
    margin: 0 auto;
    padding: 18px 16px 16px;
    display: grid;
    grid-template-columns: minmax(0, 1.1fr) minmax(0, 1.4fr);
    gap: 20px;
    font-size: 0.92rem;
}
.footer-col.left p {
    margin-top: 6px;
    color: var(--clr-text-muted);
}
.footer-logo {
    display: flex;
    align-items: center;
    gap: 9px;
}
.footer-logo .logo-main {
    font-size: 1.1rem;
}
.footer-social {
    margin-top: 10px;
    display: flex;
    gap: 10px;
}
.footer-social a {
    color: var(--clr-text-muted);
    text-decoration: none;
    font-size: 1.05rem;
}
.footer-social a:hover {
    color: var(--clr-surface);
}

/* footer app + qr */
.footer-top-row {
    display: grid;
    grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
    gap: 18px;
}
.footer-app h3,
.footer-qr-block h3 {
    font-size: 1.1rem;
    font-weight: 650;
}
.footer-app p,
.footer-qr-block p {
    margin-top: 6px;
    color: var(--clr-text-muted);
    font-size: 0.9rem;
}
.footer-app-body {
    margin-top: 8px;
    display: flex;
    gap: 12px;
    align-items: center;
}
.phone-figure {
    width: 76px;
    height: 76px;
    border-radius: 22px;
    border: 1px solid rgba(237, 244, 242, 0.35);
    background: radial-gradient(circle at top, #3b5a48, #1b251f);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.5);
    overflow: hidden;
}
.app-logo-img {
    width: 60px;
    height: 60px;
    object-fit: contain;
}
.store-badges {
    display: flex;
    flex-direction: column;
    gap: 5px;
}
.store-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border-radius: 999px;
    border: 1px solid rgba(237, 244, 242, 0.28);
    padding: 4px 9px;
    font-size: 0.78rem;
    background: rgba(0, 0, 0, 0.25);
}
.store-badge i {
    font-size: 0.9rem;
}

/* footer qr block */
.footer-qr {
    margin-top: 8px;
    display: flex;
    gap: 10px;
    align-items: center;
}
.qr-box {
    width: 88px;
    height: 88px;
    border-radius: 12px;
    border: 1px solid rgba(237, 244, 242, 0.6);
    background: #edf4f2;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}
.qr-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}
.qr-info {
    list-style: none;
    padding: 0;
    margin: 0;
    font-size: 0.84rem;
    color: var(--clr-text-muted);
}
.qr-info li {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 3px;
}
.qr-info li i {
    font-size: 0.78rem;
}

/* footer bottom */
.footer-bottom {
    max-width: 1120px;
    margin: 0 auto;
    padding: 8px 16px 16px;
    font-size: 0.8rem;
    color: var(--clr-text-muted);
    display: flex;
    flex-wrap: wrap;
    gap: 9px;
}
.footer-bottom span::after {
    content: '·';
    margin-left: 8px;
}
.footer-bottom span:last-child::after {
    content: '';
    margin: 0;
}

/* SECTION ANIMATIONS */
.section-animate {
    opacity: 0;
    transform: translateY(24px) scale(0.98);
    transition:
        opacity 0.7s cubic-bezier(0.34, 1.56, 0.64, 1),
        transform 0.7s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.section-in-view {
    opacity: 1 !important;
    transform: translateY(0) scale(1) !important;
}

/* RESPONSIVE */
.hide-sm {
    display: inline-flex;
}

@media (max-width: 960px) {
    .nav-center,
    .nav-auth {
        display: none;
    }
    .nav-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .nav-mobile {
        display: flex;
    }
    .hero-grid {
        grid-template-columns: minmax(0, 1fr);
        gap: 28px;
    }
    .hero-right {
        order: -1;
        justify-content: center;
    }
    .features-grid {
        grid-template-columns: minmax(0, 1fr);
    }
    .roles-grid {
        grid-template-columns: minmax(0, 1fr);
    }
    .footer-top-row {
        grid-template-columns: minmax(0, 1fr);
    }
}

@media (max-width: 720px) {
    .footer-inner {
        grid-template-columns: minmax(0, 1fr);
    }
}

@media (max-width: 640px) {
    .main-wrapper {
        padding-top: 100px;
    }
    .hero-title {
        font-size: 2rem;
    }
    .hero-cta {
        flex-direction: column;
        align-items: stretch;
    }
    .btn-lg {
        width: 100%;
    }
    .hero-secondary {
        justify-content: center;
    }
    .hero-metrics {
        justify-content: center;
    }
    .sponsors-head,
    .testimonials-head {
        flex-direction: column;
        align-items: flex-start;
    }
    .hide-sm {
        display: none;
    }
}
</style>
