import './App.css';

function App() {
  return (
    <div className="portfolio-container">
      {/* Header */}
      <header className="portfolio-header">
        <h1>Fardioz</h1>
        <p>Web Developer | Front-End Enthusiast | UI/UX Designer | Game Designer</p>
      </header>

      {/* Section: Perkenalan Diri */}
      <section className="portfolio-section">
        <h2>Perkenalan Diri</h2>
        <p>
          Halo! Saya Fardioz, seorang pengembang web yang berfokus pada pengembangan front-end dan back-end dengan keahlian dalam React, HTML, CSS, Javascript dan php. Saya suka membangun antarmuka yang responsif, Usability, dan inovatif. Dengan passion di bidang teknologi,Impian saya adalah membuat game dan novel, saya selalu berusaha untuk belajar hal baru dan menerapkan ide-ide kreatif dalam proyek-proyek saya.
        </p>
      </section>

      {/* Section: Proyek yang Sedang Dibuat */}
      <section className="portfolio-section">
        <h2>Proyek yang Sedang Dibuat</h2>
        <div className="projects-grid">
          <div className="project-card">
            <img src="/vite.svg" alt="Proyek 1" className="project-image" />
            <h3>Resepsionis Website</h3>
            <p>Aplikasi Resepsionis dengan fitur daftar secara realtime menggunakan database.</p>
            <span className="project-status">Tuntas</span>
          </div>
          <div className="project-card">
            <img src="./assets/react.svg" alt="Proyek 2" className="project-image" />
            <h3>Portfolio Website</h3>
            <p>Website portofolio pribadi dengan desain modern dan interaktif.</p>
            <span className="project-status">Sedang Dikerjakan</span>
          </div>
        </div>
      </section>

      {/* Section: Perencanaan Proyek */}
      <section className="portfolio-section">
        <h2>Perencanaan Proyek</h2>
        <div className="projects-grid">
          <div className="project-card">
            <img src="/vite.svg" alt="Proyek 3" className="project-image" />
            <h3>Website Game</h3>
            <p>Game Website .</p>
            <span className="project-status">Direncanakan</span>
          </div>
          <div className="project-card">
            <img src="./assets/react.svg" alt="Proyek 4" className="project-image" />
            <h3>Mobile application </h3>
            <p>Handphone application.</p>
            <span className="project-status">Direncanakan</span>
          </div>
        </div>
      </section>

      {/* Section: Bahasa Pemrograman yang Dikuasai */}
      <section className="portfolio-section">
        <h2>Bahasa Pemrograman yang Dikuasai</h2>
        <div className="skills-grid">
          <div className="skill-item">Python</div>
          <div className="skill-item">JavaScript</div>
          <div className="skill-item">React.js</div>
          <div className="skill-item">PHP</div>
          <div className="skill-item">Node.js</div>
          <div className="skill-item">Git & GitHub</div>
        </div>
      </section>

      {/* Section: Kontak */}
      <section className="portfolio-section">
        <h2>Kontak</h2>
        <p>Email: akunzeus190@email.com</p>
        <p>Instagram: <a href="https://www.instagram.com/fardiox?igsh=MW9vcTlreWU3cGE0eQ==" target="_blank">Instagram.com/fardioz</a></p>
        <p>GitHub: <a href="https://github.com/fardiioz" target="_blank">github.com/fardioz</a></p>
      </section>

      {/* Footer */}
      <footer className="portfolio-footer">
        <p>© 2025 Fardiioz. Dibuat dengan React.</p>
      </footer>
    </div>
  );
}

export default App;

