const { useState, useEffect, useRef } = React;

/* ── TRANSLATIONS ──────────────────────────────────────────────── */
const T = {
  en: {
    'nav.home':'Home','nav.dances':'Explore Dances','nav.attires':'Explore Attires',
    'eyebrow':'Ifugao Cultural Archive',
    'hero.sub':'A living digital archive of Ifugao traditional dances and woven attires — preserved for the generations to come.',
    'hero.cta1':'Explore Dances','hero.cta2':'Explore Attires','hero.scroll':'Scroll to discover',
    'stat.dances':'Sacred Dances','stat.mun':'Municipalities',
    'feat.label':'Featured Dance',
    'dances.cta.title':'Sacred Dances','dances.cta.sub':'Six ritual performances, one living tradition',
    'attires.cta.title':'Woven Attires','attires.cta.sub':'Textile identity across five municipalities',
    'quote':'The patterns our ancestors wove into cloth were not decoration — they were a language, a history, a name.',
    'quote.attr':'Ifugao Oral Tradition',
    'mun.label':'Municipalities',
    'filter.all':'All','filter.label':'Filter by category',
    'dances.title':'Ifugao Dances','dances.sub':'Sacred movements and stories woven into every step of Ifugao tradition',
    'attires.title':'Ifugao Attires','attires.sub':'Each municipality preserves its own distinct textile language — passed down through generations of weavers',
    'women.heading':"Women's Attire —",'men.heading':"Men's Attire —",
    'paired.heading':'Traditional Paired Dress — Interactive Guide',
    'back':'← Back','watch':'Watch Performance',
    'b.pagaddut':'Pagaddut','b.hinggatut':'Hinggatut','b.dinuya':'Dinuy-a',
    'empty.w':'No women\'s attires documented for this municipality yet.',
    'empty.m':'No men\'s attires documented for this municipality yet.',
    'source':'Source','hover.hint':'Hover each number to explore',
    'tap.hint':'Tap a number to learn more','view':'View Details',
    'explore.attire':'Explore Attire',
  },
  fil: {
    'nav.home':'Tahanan','nav.dances':'Tuklasin ang Sayaw','nav.attires':'Tuklasin ang Damit',
    'eyebrow':'Arkibo ng Kulturang Ifugao',
    'hero.sub':'Isang buhay na digital na arkibo ng tradisyonal na mga sayaw at inabel ng Ifugao.',
    'hero.cta1':'Tuklasin ang Sayaw','hero.cta2':'Tuklasin ang Damit','hero.scroll':'Mag-scroll para tuklasin',
    'stat.dances':'Sagradong Sayaw','stat.mun':'Munisipalidad',
    'feat.label':'Tampok na Sayaw',
    'dances.cta.title':'Mga Sayaw','dances.cta.sub':'Anim na sagradong sayaw, isang buhay na tradisyon',
    'attires.cta.title':'Mga Damit','attires.cta.sub':'Tinabas na pagkakakilanlan sa limang munisipalidad',
    'quote':'Ang mga patern na itinahi ng aming mga ninuno sa tela ay hindi dekorasyon — ito ay isang wika, isang kasaysayan, isang pangalan.',
    'quote.attr':'Tradisyon ng Ifugao',
    'mun.label':'Munisipalidad',
    'filter.all':'Lahat','filter.label':'I-filter ayon sa kategorya',
    'dances.title':'Mga Sayaw ng Ifugao','dances.sub':'Mga sagradong kilos at kuwentong nakatahi sa bawat hakbang ng tradisyong Ifugao',
    'attires.title':'Kasuotan ng Ifugao','attires.sub':'Bawat munisipalidad ay nag-iingat ng sariling wika ng tela',
    'women.heading':'Kasuotan ng Babae —','men.heading':'Kasuotan ng Lalaki —',
    'paired.heading':'Tradisyonal na Magkaparehong Damit — Gabay',
    'back':'← Bumalik','watch':'Panoorin ang Pagtatanghal',
    'b.pagaddut':'Pagaddut','b.hinggatut':'Hinggatut','b.dinuya':'Dinuy-a',
    'empty.w':'Wala pang naitalang kasuotan ng babae para sa munisipalidad na ito.',
    'empty.m':'Wala pang naitalang kasuotan ng lalaki para sa munisipalidad na ito.',
    'source':'Pinagkukunan','hover.hint':'Mag-hover upang tuklasin',
    'tap.hint':'I-tap ang numero para matuto','view':'Tingnan',
    'explore.attire':'Tuklasin',
  }
};
const tx = (lang, k) => (T[lang]||{})[k] || k;

/* ── PATTERN SVG OVERLAY ────────────────────────────────────────── */
function PatternSVG({ seed = 1, opacity = .09 }) {
  const pid = `pt${seed}`;
  return (
    <svg style={{ position:'absolute', inset:0, width:'100%', height:'100%', pointerEvents:'none' }} viewBox="0 0 80 80" preserveAspectRatio="xMidYMid slice">
      <defs>
        <pattern id={pid} x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
          <polygon points="10,1 19,10 10,19 1,10" fill="none" stroke="white" strokeWidth="1.3" opacity={opacity} />
          <circle cx="10" cy="10" r="1.8" fill="white" opacity={opacity * .7} />
          <line x1="0" y1="10" x2="20" y2="10" stroke="white" strokeWidth=".35" opacity={opacity * .4} />
          <line x1="10" y1="0" x2="10" y2="20" stroke="white" strokeWidth=".35" opacity={opacity * .4} />
        </pattern>
      </defs>
      <rect width="80" height="80" fill={`url(#${pid})`} />
    </svg>
  );
}

/* ── GRADIENT SWATCH ────────────────────────────────────────────── */
function GradSwatch({ seed = 1, style = {}, children }) {
  const pals = [
    ['#7B3A10','#C4854A'],['#5C1F1F','#C85A17'],['#1A3A10','#3A7A24'],
    ['#3A2A10','#A0824D'],['#1A2A4A','#3A6A95'],['#4A1A2A','#A84060'],
    ['#2A3A10','#7A9A3A'],['#3A1A10','#B07040'],
  ];
  const [d, l] = pals[(seed - 1) % pals.length];
  return (
    <div style={{ position:'relative', overflow:'hidden', background:`linear-gradient(148deg,${d} 0%,${l} 100%)`, ...style }}>
      <PatternSVG seed={seed} />
      {children}
    </div>
  );
}

/* ── DIAMOND STRIP ──────────────────────────────────────────────── */
function DiamondStrip() {
  const n = 80;
  const cols = ['#D4A574','#C85A17','#6B4423','#2D5016','#D4A574','#B8925D'];
  return (
    <div style={{ overflow:'hidden', height:10, flexShrink:0 }}>
      <svg viewBox={`0 0 ${n*14} 10`} style={{ width:'100%', height:'100%' }} preserveAspectRatio="none">
        {Array.from({length:n},(_,i) => (
          <polygon key={i} points={`${i*14+7},0 ${i*14+13},5 ${i*14+7},10 ${i*14+1},5`} fill={cols[i%cols.length]} />
        ))}
      </svg>
    </div>
  );
}

/* ── BADGE ──────────────────────────────────────────────────────── */
function Badge({ cat, lang }) {
  const map = { Pagaddut:['b-pagaddut','b.pagaddut'], Hinggatut:['b-hinggatut','b.hinggatut'], 'Dinuy-a':['b-dinuya','b.dinuya'] };
  const [cls, key] = map[cat] || ['b-pagaddut','b.pagaddut'];
  return <span className={`badge ${cls}`}>{tx(lang, key)}</span>;
}

/* ── HEADER ─────────────────────────────────────────────────────── */
function Header({ page, setPage, lang, setLang }) {
  const [scrolled, setScrolled] = useState(false);
  useEffect(() => {
    const fn = () => setScrolled(window.scrollY > 10);
    window.addEventListener('scroll', fn);
    return () => window.removeEventListener('scroll', fn);
  }, []);
  return (
    <header className={`hdr${scrolled?' scrolled':''}`}>
      <button className="hdr-logo" onClick={() => setPage('home')}>
        Heri<span className="gem">◆</span>THREADS
      </button>
      <nav className="hdr-nav">
        {['home','dances','attires'].map(p => (
          <button key={p} className={`nav-btn${page===p?' active':''}`} onClick={() => setPage(p)}>
            {tx(lang, `nav.${p}`)}
          </button>
        ))}
      </nav>
      <a href="Admin.html" target="_blank"
        style={{ fontSize:'.72rem', fontWeight:700, color:'rgba(212,165,116,.5)', border:'1px solid rgba(212,165,116,.2)', padding:'.3rem .7rem', borderRadius:'.375rem', textDecoration:'none', whiteSpace:'nowrap', transition:'all 150ms', fontFamily:'var(--font-sans)' }}
        onMouseEnter={e=>{e.target.style.color='var(--gold)';e.target.style.borderColor='rgba(212,165,116,.5)'}}
        onMouseLeave={e=>{e.target.style.color='rgba(212,165,116,.5)';e.target.style.borderColor='rgba(212,165,116,.2)'}}>
        Admin ↗
      </a>
      <div className="lang-sw">
        <button className={`lang-btn${lang==='en'?' active':''}`} onClick={()=>setLang('en')}>EN</button>
        <button className={`lang-btn${lang==='fil'?' active':''}`} onClick={()=>setLang('fil')}>FIL</button>
      </div>
    </header>
  );
}

/* ── LANDING PAGE ───────────────────────────────────────────────── */
function LandingPage({ setPage, lang }) {
  return (
    <>
      {/* ── HERO ── */}
      <section className="landing-hero">
        <div className="lh-bg-pattern" />
        <div className="lh-glow" />
        {/* giant watermark */}
        <div aria-hidden="true" style={{ position:'absolute', top:'48%', left:'50%', transform:'translate(-50%,-50%)', fontFamily:'var(--font-display)', fontSize:'clamp(8rem,22vw,20rem)', fontWeight:700, fontStyle:'italic', color:'rgba(212,165,116,.025)', lineHeight:1, userSelect:'none', whiteSpace:'nowrap', pointerEvents:'none', letterSpacing:'-.04em' }}>
          Ifugao
        </div>
        <p className="lh-eyebrow">{tx(lang,'eyebrow')}</p>
        <h1 className="lh-title" style={{ position:'relative', zIndex:1 }}>
          Heri<span className="gem">◆</span>THREADS
          <span className="sub-line">A Digital Preservation</span>
        </h1>
        <p className="lh-sub">{tx(lang,'hero.sub')}</p>
        <div className="lh-actions">
          <button className="lh-btn lh-btn-primary" onClick={() => setPage('dances')}>{tx(lang,'hero.cta1')} →</button>
          <button className="lh-btn lh-btn-ghost"   onClick={() => setPage('attires')}>{tx(lang,'hero.cta2')}</button>
        </div>
        <div className="lh-scroll">
          <span>{tx(lang,'hero.scroll')}</span>
          <span className="lh-scroll-arrow">↓</span>
        </div>
      </section>

      <DiamondStrip />

      {/* ── BENTO FEATURES ── */}
      <div className="bento-wrap">
        <div className="bento-grid">

          {/* Row 1, col 1 — Dances CTA */}
          <div className="b-cell b-dances" role="button" tabIndex={0} onClick={()=>setPage('dances')} onKeyDown={e=>e.key==='Enter'&&setPage('dances')} style={{animationDelay:'0ms'}}>
            <div>
              <div className="b-cell-ico" style={{color:'var(--gold)'}}>♪</div>
              <div className="b-cell-title">{tx(lang,'dances.cta.title')}</div>
              <div className="b-cell-sub">{tx(lang,'dances.cta.sub')}</div>
            </div>
            <span className="b-arrow">↗</span>
          </div>

          {/* Row 1, col 2 — Featured dance */}
          <div className="b-cell b-feat" role="button" tabIndex={0} onClick={()=>setPage('dances')} onKeyDown={e=>e.key==='Enter'&&setPage('dances')} style={{animationDelay:'50ms'}}>
            <GradSwatch seed={3} style={{ position:'absolute', inset:0 }} />
            <div style={{ position:'absolute', inset:0, background:'linear-gradient(to top, rgba(10,7,4,.95) 0%, rgba(10,7,4,.25) 60%, transparent 100%)' }} />
            <div style={{ position:'relative', zIndex:2 }}>
              <p className="b-feat-label">✦ {tx(lang,'feat.label')}</p>
              <p className="b-feat-name">Uyauy</p>
              <p className="b-feat-cat">{tx(lang,'b.hinggatut')}</p>
            </div>
          </div>

          {/* Row 1, col 3 — Stat: dances */}
          <div className="b-cell b-s1" style={{animationDelay:'80ms'}}>
            <div className="b-stat-num">6</div>
            <div className="b-stat-label">{tx(lang,'stat.dances')}</div>
          </div>

          {/* Row 1, col 4 — Stat: municipalities (gold) */}
          <div className="b-cell b-s2" style={{animationDelay:'110ms'}}>
            <div className="b-stat-num">5</div>
            <div className="b-stat-label">{tx(lang,'stat.mun')}</div>
          </div>

          {/* Row 2, cols 1-2 — Quote */}
          <div className="b-cell b-quote" style={{animationDelay:'140ms'}}>
            <div className="b-quote-mark">"</div>
            <p className="b-quote-text">{tx(lang,'quote')}</p>
            <p className="b-quote-attr">— {tx(lang,'quote.attr')}</p>
          </div>

          {/* Row 2, col 3 — Attires CTA */}
          <div className="b-cell b-attires" role="button" tabIndex={0} onClick={()=>setPage('attires')} onKeyDown={e=>e.key==='Enter'&&setPage('attires')} style={{animationDelay:'170ms'}}>
            <div>
              <div className="b-cell-ico" style={{color:'var(--orange)'}}>◈</div>
              <div className="b-cell-title">{tx(lang,'attires.cta.title')}</div>
              <div className="b-cell-sub">{tx(lang,'attires.cta.sub')}</div>
            </div>
            <span className="b-arrow" style={{color:'var(--orange)'}}>↗</span>
          </div>

          {/* Row 2, col 4 — Municipality list */}
          <div className="b-cell b-mun" style={{animationDelay:'200ms'}}>
            <p className="b-stat-label" style={{color:'rgba(212,165,116,.45)', marginBottom:'.625rem'}}>{tx(lang,'mun.label')}</p>
            <div className="b-mun-list">
              {MUNICIPALITIES.map(m => (
                <div key={m.name} className="b-mun-item" onClick={()=>setPage('attires')} role="button" tabIndex={0} onKeyDown={e=>e.key==='Enter'&&setPage('attires')}>
                  {m.name}
                </div>
              ))}
            </div>
          </div>

        </div>
      </div>
    </>
  );
}

/* ── DANCE MODAL ────────────────────────────────────────────────── */
function DanceModal({ dance, onClose, lang }) {
  useEffect(() => {
    const fn = e => { if (e.key==='Escape') onClose(); };
    window.addEventListener('keydown', fn);
    document.body.style.overflow = 'hidden';
    return () => { window.removeEventListener('keydown', fn); document.body.style.overflow = ''; };
  }, [onClose]);
  return (
    <div className="modal-ov" onClick={e => e.target===e.currentTarget && onClose()}>
      <div className="modal-box">
        <button className="close-btn" onClick={onClose} aria-label="Close">✕</button>
        <div className="modal-img">
          <GradSwatch seed={dance.seed+10} style={{ height:'100%', width:'100%' }}>
            <div style={{ position:'absolute', inset:0, display:'flex', alignItems:'center', justifyContent:'center' }}>
              <span style={{ fontFamily:'var(--font-display)', fontSize:'clamp(3rem,8vw,5rem)', fontStyle:'italic', fontWeight:700, color:'rgba(255,255,255,.09)', userSelect:'none' }}>
                {dance.name}
              </span>
            </div>
            <div style={{ position:'absolute', inset:0, background:'linear-gradient(to top,rgba(10,7,4,.5) 0%,transparent 50%)' }} />
          </GradSwatch>
        </div>
        <div className="modal-body">
          <Badge cat={dance.cat} lang={lang} />
          <h2 className="modal-name">{dance.name}</h2>
          <p className="modal-desc">{dance.full}</p>
          <div style={{ borderTop:'1px solid var(--tan)', paddingTop:'1.5rem' }}>
            <p className="modal-vid-label">◆ {tx(lang,'watch')}</p>
            <div className="vid-wrap">
              <iframe src={dance.yt} title={dance.name} allow="accelerometer; autoplay; clipboard-write; encrypted-media; picture-in-picture" allowFullScreen />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

/* ── DANCE CARD V2 ──────────────────────────────────────────────── */
function DanceCardV2({ dance, onClick, lang, index }) {
  return (
    <article
      className="dance-card-v2 anim-fade-up"
      style={{ animationDelay:`${index * 70}ms` }}
      onClick={() => onClick(dance)}
      role="button" tabIndex={0}
      onKeyDown={e => e.key==='Enter' && onClick(dance)}
      aria-label={`Explore ${dance.name}`}
    >
      <GradSwatch seed={dance.seed} style={{ position:'absolute', inset:0 }} />
      {/* Vignette */}
      <div style={{ position:'absolute', inset:0, background:'linear-gradient(to top, rgba(10,7,4,.6) 0%, transparent 55%)' }} />
      {/* Watermark */}
      <div className="dc2-wm">{dance.name}</div>
      {/* Badge */}
      <div className="dc2-badge"><Badge cat={dance.cat} lang={lang} /></div>
      {/* Hover overlay */}
      <div className="dc2-overlay">
        <h2 className="dc2-title">{dance.name}</h2>
        <p className="dc2-snip">{dance.snippet}</p>
        <span className="dc2-link">{tx(lang,'view')} →</span>
      </div>
    </article>
  );
}

/* ── DANCES PAGE ────────────────────────────────────────────────── */
function DancesPage({ lang }) {
  const [active, setActive] = useState(null);
  const [cat, setCat] = useState('All');
  const cats = ['All', ...CATS];
  const filtered = cat === 'All' ? DANCES : DANCES.filter(d => d.cat === cat);
  return (
    <main>
      <DiamondStrip />
      <div style={{ background:'#0C0907' }}>
        {/* Page header */}
        <div style={{ maxWidth:1200, margin:'0 auto', padding:'3.5rem 2rem 2rem' }}>
          <p style={{ fontFamily:'var(--font-sans)', fontSize:'.625rem', fontWeight:700, letterSpacing:'.22em', textTransform:'uppercase', color:'var(--gold)', opacity:.7, marginBottom:'1rem' }}>
            {tx(lang,'eyebrow')}
          </p>
          <h1 style={{ fontFamily:'var(--font-display)', fontSize:'clamp(2.5rem,6vw,4.5rem)', fontWeight:700, color:'rgba(255,255,255,.92)', lineHeight:.95, letterSpacing:'-.025em', marginBottom:'.75rem' }}>
            {tx(lang,'dances.title')}
          </h1>
          <p style={{ fontFamily:'var(--font-body)', fontStyle:'italic', fontSize:'clamp(.875rem,1.3vw,1rem)', color:'rgba(255,255,255,.38)', maxWidth:520, lineHeight:1.8 }}>
            {tx(lang,'dances.sub')}
          </p>
        </div>
        {/* Filter bar */}
        <div className="cat-filter" role="group" aria-label={tx(lang,'filter.label')}>
          {cats.map(c => (
            <button key={c} className={`cat-pill${cat===c?' active':''}`} onClick={() => setCat(c)}>
              {c === 'All' ? tx(lang,'filter.all') : tx(lang, `b.${c.toLowerCase().replace('-','')}`)}
            </button>
          ))}
        </div>
      </div>
      <div style={{ background:'#0C0907', paddingTop:'.25rem' }}>
        <section className="dance-grid-v2">
          {filtered.map((d, i) => <DanceCardV2 key={d.id} dance={d} onClick={setActive} lang={lang} index={i} />)}
        </section>
      </div>
      {active && <DanceModal dance={active} onClose={() => setActive(null)} lang={lang} />}
    </main>
  );
}

/* ── MUNICIPALITY CARD V2 ───────────────────────────────────────── */
function MunCardV2({ mun, onClick, lang, index }) {
  return (
    <article
      className="mun-card-v2 anim-fade-up"
      style={{ animationDelay:`${index * 80}ms` }}
      onClick={() => onClick(mun.name)}
      role="button" tabIndex={0}
      onKeyDown={e => e.key==='Enter' && onClick(mun.name)}
    >
      <GradSwatch seed={mun.seed} style={{ position:'absolute', inset:0 }} />
      <div className="mun2-content">
        <h2 className="mun2-name">{mun.name}</h2>
        <p className="mun2-tag">{mun.tagline}</p>
        <span className="mun2-cta">{tx(lang,'explore.attire')} →</span>
      </div>
    </article>
  );
}

/* ── ATTIRE CARD ────────────────────────────────────────────────── */
function AttireCard({ item, lang, index }) {
  return (
    <article className="attire-card" style={{ animationDelay:`${index*90}ms` }}>
      <div className="att-img">
        <GradSwatch seed={item.seed} style={{ height:'100%', width:'100%' }}>
          <div style={{ position:'absolute', inset:0, display:'flex', alignItems:'center', justifyContent:'center' }}>
            <span style={{ fontFamily:'var(--font-display)', fontSize:'1.625rem', fontStyle:'italic', fontWeight:700, color:'rgba(255,255,255,.1)', userSelect:'none', textAlign:'center', padding:'0 .75rem' }}>{item.dia}</span>
          </div>
        </GradSwatch>
      </div>
      <div className="att-body">
        <h3 className="att-title">{item.gen}</h3>
        <p className="att-dialect">{item.dia}</p>
        <p className="att-desc">{item.desc}</p>
        <p className="att-src"><em>{tx(lang,'source')}:</em> {item.src}</p>
      </div>
    </article>
  );
}

/* ── PAIRED HOTSPOTS ────────────────────────────────────────────── */
function PairedSection({ lang }) {
  const [active, setActive] = useState(null);
  const [isMobile, setIsMobile] = useState(window.innerWidth < 768);
  const wrapRef = useRef(null);
  useEffect(() => {
    const fn = () => setIsMobile(window.innerWidth < 768);
    window.addEventListener('resize', fn); return () => window.removeEventListener('resize', fn);
  }, []);
  useEffect(() => {
    if (!isMobile || !active) return;
    const fn = e => { if (wrapRef.current && !wrapRef.current.contains(e.target)) setActive(null); };
    document.addEventListener('click', fn, true); return () => document.removeEventListener('click', fn, true);
  }, [isMobile, active]);
  const ttStyle = (spot) => ({
    position:'absolute', zIndex:20,
    left:  spot.x > 65 ? 'auto' : 'calc(100% + 10px)',
    right: spot.x > 65 ? 'calc(100% + 10px)' : 'auto',
    top:   spot.y > 65 ? 'auto' : '50%',
    bottom:spot.y > 65 ? 'calc(100% + 10px)' : 'auto',
    transform: spot.y <= 65 ? 'translateY(-50%)' : 'none',
  });
  return (
    <section className="attire-section">
      <h2 className="sec-head">{tx(lang,'paired.heading')}</h2>
      <div className="sec-line" />
      <p style={{ fontSize:'.8rem', color:'var(--gray)', marginBottom:'.875rem', fontFamily:'var(--font-body)', fontStyle:'italic' }}>
        {isMobile ? tx(lang,'tap.hint') : tx(lang,'hover.hint')}
      </p>
      <div className="paired-wrap" ref={wrapRef}>
        <GradSwatch seed={99} style={{ width:'100%', aspectRatio:'16/9' }}>
          <div style={{ position:'absolute', inset:0, display:'flex', alignItems:'flex-end', justifyContent:'center', gap:'8%', paddingBottom:'4%', pointerEvents:'none' }}>
            {[{w:16,h:68},{w:18,h:74}].map((f,i)=>(
              <div key={i} style={{ display:'flex', flexDirection:'column', alignItems:'center', gap:3, opacity:.15 }}>
                <div style={{ width:f.w*.55+'px', height:f.w*.55+'px', borderRadius:'50%', background:'white' }} />
                <div style={{ width:f.w+'px', height:f.h+'px', background:'white', borderRadius:'10px 10px 5px 5px' }} />
              </div>
            ))}
          </div>
          {HOTSPOTS.map(spot => {
            const isActive = active?.id === spot.id;
            return (
              <div key={spot.id} className={`hs-dot${isActive?' active':''}`} style={{ left:`${spot.x}%`, top:`${spot.y}%` }}
                onMouseEnter={()=> !isMobile && setActive(spot)}
                onMouseLeave={()=> !isMobile && setActive(null)}
                onClick={()=> isMobile && setActive(isActive ? null : spot)}
                role="button" tabIndex={0}
                aria-label={`${spot.label}: ${spot.dia}`}
                onKeyDown={e=>e.key==='Enter'&&setActive(isActive?null:spot)}>
                <div className="hs-ring" />
                <div className="hs-inner">{spot.id}</div>
                {isActive && !isMobile && (
                  <div className="hs-tooltip" style={ttStyle(spot)}>
                    <div className="hs-tt-name">{spot.label} — <em>{spot.dia}</em></div>
                    <div>{spot.desc}</div>
                  </div>
                )}
              </div>
            );
          })}
        </GradSwatch>
        {isMobile && active && (
          <div style={{ marginTop:'.75rem', background:'#1A1208', border:'1px solid rgba(212,165,116,.15)', borderRadius:'.875rem', padding:'1.125rem 1.25rem' }}>
            <div style={{ color:'var(--gold-light)', fontWeight:700, marginBottom:'.375rem', fontSize:'.875rem', fontFamily:'var(--font-display)' }}>{active.label} <em style={{fontFamily:'var(--font-body)', fontWeight:400}}>— {active.dia}</em></div>
            <p style={{ fontSize:'.84375rem', lineHeight:1.65, color:'rgba(255,255,255,.65)', fontFamily:'var(--font-body)' }}>{active.desc}</p>
            <button onClick={()=>setActive(null)} style={{ marginTop:'.75rem', color:'rgba(255,255,255,.3)', fontSize:'.72rem', background:'none', border:'none', cursor:'pointer', padding:0 }}>Tap to close ✕</button>
          </div>
        )}
      </div>
      <div className="hs-legend">
        {HOTSPOTS.map(s => <div key={s.id} className="hs-legend-item"><span className="hs-legend-dot">{s.id}</span>{s.label}</div>)}
      </div>
    </section>
  );
}

/* ── ATTIRE DETAIL ──────────────────────────────────────────────── */
function AttireSections({ municipality, onBack, lang }) {
  const data = ATTIRES[municipality] || { women:[], men:[] };
  useEffect(() => { window.scrollTo({ top:0 }); }, [municipality]);
  const mun = MUNICIPALITIES.find(m => m.name === municipality) || {};
  return (
    <main>
      <DiamondStrip />
      <div className="attires-wrap">
        <button className="back-btn" onClick={onBack}>{tx(lang,'back')}</button>
        <div style={{ marginBottom:'2.5rem', paddingBottom:'1.5rem', borderBottom:'1px solid var(--tan)' }}>
          <p style={{ fontFamily:'var(--font-sans)', fontSize:'.625rem', fontWeight:700, letterSpacing:'.2em', textTransform:'uppercase', color:'var(--gold)', opacity:.7, marginBottom:'.5rem' }}>{tx(lang,'eyebrow')}</p>
          <h1 style={{ fontFamily:'var(--font-display)', fontSize:'clamp(2rem,5vw,3rem)', fontWeight:700, color:'var(--charcoal)', lineHeight:1.0, letterSpacing:'-.02em' }}>{municipality}</h1>
          <p style={{ fontFamily:'var(--font-body)', fontStyle:'italic', fontSize:'.9375rem', color:'var(--gray)', marginTop:'.375rem' }}>{mun.tagline}</p>
        </div>
        <section className="attire-section">
          <h2 className="sec-head">{tx(lang,'women.heading')} {municipality}</h2>
          <div className="sec-line" />
          {data.women.length ? data.women.map((a,i) => <AttireCard key={a.id} item={a} lang={lang} index={i} />) : <div className="empty-state">{tx(lang,'empty.w')}</div>}
        </section>
        <section className="attire-section">
          <h2 className="sec-head">{tx(lang,'men.heading')} {municipality}</h2>
          <div className="sec-line" />
          {data.men.length ? data.men.map((a,i) => <AttireCard key={a.id} item={a} lang={lang} index={i} />) : <div className="empty-state">{tx(lang,'empty.m')}</div>}
        </section>
        <PairedSection lang={lang} />
      </div>
    </main>
  );
}

/* ── ATTIRES PAGE ───────────────────────────────────────────────── */
function AttiresPage({ lang }) {
  const [selected, setSelected] = useState(null);
  if (selected) return <AttireSections municipality={selected} onBack={() => setSelected(null)} lang={lang} />;
  return (
    <main>
      <DiamondStrip />
      <div style={{ background:'#0C0907' }}>
        <div style={{ maxWidth:1200, margin:'0 auto', padding:'3.5rem 2rem 2.25rem' }}>
          <p style={{ fontFamily:'var(--font-sans)', fontSize:'.625rem', fontWeight:700, letterSpacing:'.22em', textTransform:'uppercase', color:'var(--gold)', opacity:.7, marginBottom:'1rem' }}>
            {tx(lang,'eyebrow')}
          </p>
          <h1 style={{ fontFamily:'var(--font-display)', fontSize:'clamp(2.5rem,6vw,4.5rem)', fontWeight:700, color:'rgba(255,255,255,.92)', lineHeight:.95, letterSpacing:'-.025em', marginBottom:'.75rem' }}>
            {tx(lang,'attires.title')}
          </h1>
          <p style={{ fontFamily:'var(--font-body)', fontStyle:'italic', fontSize:'clamp(.875rem,1.3vw,1rem)', color:'rgba(255,255,255,.38)', maxWidth:520, lineHeight:1.8, marginBottom:'2.5rem' }}>
            {tx(lang,'attires.sub')}
          </p>
        </div>
        <section className="mun-grid-v2" style={{ paddingBottom:'4rem' }} aria-label="Municipality selection">
          {MUNICIPALITIES.map((m,i) => <MunCardV2 key={m.name} mun={m} onClick={setSelected} lang={lang} index={i} />)}
        </section>
      </div>
    </main>
  );
}

/* ── TWEAKS PANEL ───────────────────────────────────────────────── */
function TweaksPanel({ lang, setLang }) {
  const [visible, setVisible] = useState(false);
  const [dark, setDark] = useState(TWEAK_DEFAULTS.darkMode);
  const [compact, setCompact] = useState(TWEAK_DEFAULTS.compactCards || false);

  useEffect(() => {
    const h = e => {
      if (e.data?.type==='__activate_edit_mode')   setVisible(true);
      if (e.data?.type==='__deactivate_edit_mode') setVisible(false);
    };
    window.addEventListener('message', h);
    window.parent?.postMessage({ type:'__edit_mode_available' }, '*');
    return () => window.removeEventListener('message', h);
  }, []);

  useEffect(() => {
    document.body.classList.toggle('dark', dark);
    window.parent?.postMessage({ type:'__edit_mode_set_keys', edits:{ darkMode:dark } }, '*');
  }, [dark]);

  const dismiss = () => { setVisible(false); window.parent?.postMessage({ type:'__edit_mode_dismissed' }, '*'); };
  if (!visible) return null;
  return (
    <div className="tw-panel">
      <div className="tw-hd"><span className="tw-title">Tweaks</span><button className="tw-close" onClick={dismiss}>✕</button></div>
      <div className="tw-row"><span>Dark Mode</span><button className={`tw-toggle${dark?' on':''}`} onClick={()=>setDark(d=>!d)} /></div>
      <div className="tw-row">
        <span>Language</span>
        <div className="tw-seg">
          {['en','fil'].map(l => <button key={l} className={lang===l?'on':''} onClick={()=>{setLang(l);window.parent?.postMessage({type:'__edit_mode_set_keys',edits:{language:l}},'*');}}>{l.toUpperCase()}</button>)}
        </div>
      </div>
    </div>
  );
}

/* ── APP ROOT ───────────────────────────────────────────────────── */
function App() {
  const [page, setPage] = useState('home');
  const [lang, setLang] = useState(TWEAK_DEFAULTS.language || 'en');

  useEffect(() => { document.body.classList.toggle('dark', TWEAK_DEFAULTS.darkMode); }, []);

  const go = (p) => { setPage(p); window.scrollTo({ top:0 }); };

  return (
    <>
      <Header page={page} setPage={go} lang={lang} setLang={setLang} />
      {page === 'home'    && <LandingPage setPage={go} lang={lang} />}
      {page === 'dances'  && <DancesPage lang={lang} />}
      {page === 'attires' && <AttiresPage lang={lang} />}
      {page !== 'home' && (
        <footer className="footer">
          <strong>Heri◆THREADS</strong> — A Digital Preservation of Ifugao Traditional Dances and Attires
          <br /><span style={{ fontSize:'.72rem', marginTop:'.25rem', display:'block', opacity:.45 }}>All cultural content presented for educational and preservation purposes.</span>
        </footer>
      )}
      <TweaksPanel lang={lang} setLang={setLang} />
    </>
  );
}

ReactDOM.createRoot(document.getElementById('root')).render(<App />);
