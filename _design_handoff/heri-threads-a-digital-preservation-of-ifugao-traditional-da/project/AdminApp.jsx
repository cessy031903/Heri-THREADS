const { useState, useEffect, useRef } = React;

/* ── TINY PATTERN SWATCH ─────────────────────────────────────────── */
function Swatch({ seed = 1, size = 42 }) {
  const pals = [['#7B3A10','#D4A574'],['#5C1F1F','#C85A17'],['#1A3A10','#4A8A2C'],['#3A2A10','#B8925D'],['#1A2A4A','#4A7AB5'],['#4A1A2A','#C86090'],['#2A3A10','#8AB54A'],['#3A1A10','#C89060']];
  const [d,l] = pals[(seed-1)%pals.length];
  return <div style={{ width:size, height:size, borderRadius:6, background:`linear-gradient(135deg,${d},${l})`, flexShrink:0 }} />;
}

/* ── TOAST ───────────────────────────────────────────────────────── */
function Toast({ msg, type, onDone }) {
  useEffect(() => { const t = setTimeout(onDone, 2800); return () => clearTimeout(t); }, [onDone]);
  const icon = type === 'success' ? '✓' : '✕';
  return (
    <div className={`toast toast-${type}`}>
      <span style={{ fontWeight:700, fontSize:'1rem' }}>{icon}</span>
      <span>{msg}</span>
    </div>
  );
}

/* ── CONFIRM DELETE ──────────────────────────────────────────────── */
function DeleteConfirm({ name, onConfirm, onCancel }) {
  useEffect(() => {
    const fn = e => { if (e.key === 'Escape') onCancel(); };
    window.addEventListener('keydown', fn);
    return () => window.removeEventListener('keydown', fn);
  }, [onCancel]);
  return (
    <div className="modal-ov" onClick={e => e.target===e.currentTarget && onCancel()}>
      <div className="del-box">
        <div className="del-icon">🗑️</div>
        <h3 className="del-title">Delete Record</h3>
        <p className="del-sub">Are you sure you want to delete <strong>"{name}"</strong>? This action cannot be undone.</p>
        <div className="del-actions">
          <button className="btn btn-outline" onClick={onCancel}>Cancel</button>
          <button className="btn btn-danger" onClick={onConfirm}>Delete</button>
        </div>
      </div>
    </div>
  );
}

/* ── DANCE MODAL ─────────────────────────────────────────────────── */
function DanceModal({ item, onSave, onClose }) {
  const empty = { name:'', cat:'Pagaddut', desc:'', video:'' };
  const [form, setForm] = useState(item || empty);
  const [errs, setErrs] = useState({});
  const isEdit = !!item;

  useEffect(() => {
    const fn = e => { if (e.key === 'Escape') onClose(); };
    window.addEventListener('keydown', fn); document.body.style.overflow='hidden';
    return () => { window.removeEventListener('keydown', fn); document.body.style.overflow=''; };
  }, [onClose]);

  const set = (k, v) => { setForm(f => ({...f, [k]:v})); setErrs(e => ({...e, [k]:''})); };

  const validate = () => {
    const e = {};
    if (!form.name.trim()) e.name = 'Dance name is required';
    if (!form.desc.trim()) e.desc = 'Description is required';
    return e;
  };

  const submit = () => {
    const e = validate();
    if (Object.keys(e).length) { setErrs(e); return; }
    onSave({ ...form, id: item?.id || Date.now(), seed: item?.seed || Math.ceil(Math.random()*8) });
  };

  return (
    <div className="modal-ov" onClick={e => e.target===e.currentTarget && onClose()}>
      <div className="modal-box">
        <div className="modal-hd">
          <h2 className="modal-title">{isEdit ? 'Edit Dance' : 'Add New Dance'}</h2>
          <button className="modal-close" onClick={onClose} aria-label="Close">✕</button>
        </div>
        <div className="modal-body">
          <div className="form-group">
            <label className="form-label">Dance Name *</label>
            <input className={`form-input${errs.name?' error':''}`} value={form.name} onChange={e=>set('name',e.target.value)} placeholder="e.g. Punnuk" />
            {errs.name && <p className="form-error">{errs.name}</p>}
          </div>
          <div className="form-group">
            <label className="form-label">Category *</label>
            <select className="form-input form-select" value={form.cat} onChange={e=>set('cat',e.target.value)}>
              {CATS.map(c=><option key={c}>{c}</option>)}
            </select>
          </div>
          <div className="form-group">
            <label className="form-label">Description *</label>
            <textarea className={`form-input${errs.desc?' error':''}`} value={form.desc} onChange={e=>set('desc',e.target.value)} placeholder="Describe the dance, its cultural significance..." />
            {errs.desc && <p className="form-error">{errs.desc}</p>}
          </div>
          <div className="form-group">
            <label className="form-label">YouTube Embed URL</label>
            <input className="form-input" value={form.video} onChange={e=>set('video',e.target.value)} placeholder="https://www.youtube.com/embed/..." />
          </div>
          <div className="form-group">
            <label className="form-label">Image Path / URL</label>
            <input className="form-input" value={form.image||''} onChange={e=>set('image',e.target.value)} placeholder="/storage/dances/punnuk.jpg" />
          </div>
        </div>
        <div className="modal-foot">
          <button className="btn btn-outline" onClick={onClose}>Cancel</button>
          <button className="btn btn-primary" onClick={submit}>{isEdit ? 'Save Changes' : 'Add Dance'}</button>
        </div>
      </div>
    </div>
  );
}

/* ── ATTIRE MODAL ────────────────────────────────────────────────── */
function AttireModal({ item, onSave, onClose }) {
  const empty = { gen:'', dia:'', mun:'Banaue', gender:'Female', desc:'', src:'' };
  const [form, setForm] = useState(item || empty);
  const [errs, setErrs] = useState({});
  const isEdit = !!item;

  useEffect(() => {
    const fn = e => { if (e.key === 'Escape') onClose(); };
    window.addEventListener('keydown', fn); document.body.style.overflow='hidden';
    return () => { window.removeEventListener('keydown', fn); document.body.style.overflow=''; };
  }, [onClose]);

  const set = (k,v) => { setForm(f=>({...f,[k]:v})); setErrs(e=>({...e,[k]:''})); };

  const validate = () => {
    const e = {};
    if (!form.gen.trim()) e.gen = 'General name is required';
    if (!form.dia.trim()) e.dia = 'Dialect name is required';
    if (!form.desc.trim()) e.desc = 'Description is required';
    return e;
  };

  const submit = () => {
    const e = validate();
    if (Object.keys(e).length) { setErrs(e); return; }
    onSave({ ...form, id: item?.id || Date.now(), seed: item?.seed || Math.ceil(Math.random()*8+10) });
  };

  return (
    <div className="modal-ov" onClick={e => e.target===e.currentTarget && onClose()}>
      <div className="modal-box">
        <div className="modal-hd">
          <h2 className="modal-title">{isEdit ? 'Edit Attire' : 'Add New Attire'}</h2>
          <button className="modal-close" onClick={onClose} aria-label="Close">✕</button>
        </div>
        <div className="modal-body">
          <div className="form-row">
            <div className="form-group">
              <label className="form-label">General Name *</label>
              <input className={`form-input${errs.gen?' error':''}`} value={form.gen} onChange={e=>set('gen',e.target.value)} placeholder="e.g. Wraparound Skirt" />
              {errs.gen && <p className="form-error">{errs.gen}</p>}
            </div>
            <div className="form-group">
              <label className="form-label">Dialect Name *</label>
              <input className={`form-input${errs.dia?' error':''}`} value={form.dia} onChange={e=>set('dia',e.target.value)} placeholder="e.g. Tapis" />
              {errs.dia && <p className="form-error">{errs.dia}</p>}
            </div>
          </div>
          <div className="form-row">
            <div className="form-group">
              <label className="form-label">Municipality</label>
              <select className="form-input form-select" value={form.mun} onChange={e=>set('mun',e.target.value)}>
                {MUNS.map(m=><option key={m}>{m}</option>)}
              </select>
            </div>
            <div className="form-group">
              <label className="form-label">Gender</label>
              <select className="form-input form-select" value={form.gender} onChange={e=>set('gender',e.target.value)}>
                <option>Female</option><option>Male</option>
              </select>
            </div>
          </div>
          <div className="form-group">
            <label className="form-label">Description *</label>
            <textarea className={`form-input${errs.desc?' error':''}`} value={form.desc} onChange={e=>set('desc',e.target.value)} placeholder="Describe the attire, materials, significance..." />
            {errs.desc && <p className="form-error">{errs.desc}</p>}
          </div>
          <div className="form-group">
            <label className="form-label">Source / Reference</label>
            <input className="form-input" value={form.src} onChange={e=>set('src',e.target.value)} placeholder="e.g. National Museum of the Philippines" />
          </div>
          <div className="form-group">
            <label className="form-label">Image Path / URL</label>
            <input className="form-input" value={form.image||''} onChange={e=>set('image',e.target.value)} placeholder="/storage/attires/tapis.jpg" />
          </div>
        </div>
        <div className="modal-foot">
          <button className="btn btn-outline" onClick={onClose}>Cancel</button>
          <button className="btn btn-primary" onClick={submit}>{isEdit ? 'Save Changes' : 'Add Attire'}</button>
        </div>
      </div>
    </div>
  );
}

/* ── SIDEBAR ─────────────────────────────────────────────────────── */
function Sidebar({ page, setPage, onLogout }) {
  const nav = [
    { key:'dashboard', ico:'⊞', label:'Dashboard' },
    { key:'dances',    ico:'♪', label:'Manage Dances' },
    { key:'attires',   ico:'◈', label:'Manage Attires' },
  ];
  return (
    <aside className="sidebar" role="navigation" aria-label="Admin navigation">
      <div className="sb-logo">
        <div className="sb-logo-text">Heri<span className="gem">◆</span>THREADS</div>
        <div className="sb-logo-sub">Admin Panel</div>
      </div>
      <div className="sb-nav">
        <div className="sb-section">Main</div>
        {nav.map(n => (
          <button key={n.key} className={`sb-item${page===n.key?' active':''}`} onClick={()=>setPage(n.key)} aria-current={page===n.key?'page':undefined}>
            <span className="ico">{n.ico}</span>{n.label}
          </button>
        ))}
        <div className="sb-section">Site</div>
        <a href="Heri-THREADS.html" target="_blank" className="sb-item" style={{ textDecoration:'none' }}>
          <span className="ico">↗</span>View Public Site
        </a>
      </div>
      <div className="sb-footer">
        <div className="sb-user">
          <div className="sb-avatar">A</div>
          <div className="sb-user-info">
            <div className="sb-user-name">Administrator</div>
            <div className="sb-user-role">Super Admin</div>
          </div>
          <button className="sb-logout" onClick={onLogout} title="Logout">⏻</button>
        </div>
      </div>
    </aside>
  );
}

/* ── DASHBOARD ───────────────────────────────────────────────────── */
function Dashboard({ dances, attires, setPage }) {
  const stats = [
    { num:dances.length,  label:'Total Dances',       ico:'♪', color:'var(--gold)' },
    { num:attires.length, label:'Total Attires',       ico:'◈', color:'var(--green)' },
    { num:MUNS.length,    label:'Municipalities',      ico:'⊙', color:'var(--orange)' },
    { num:dances.length+attires.length, label:'Archive Entries', ico:'⊞', color:'#8B5CF6' },
  ];
  return (
    <div>
      <div className="stat-grid">
        {stats.map((s,i)=>(
          <div className="stat-card afu" key={i} style={{ animationDelay:`${i*60}ms` }}>
            <div className="stat-icon" style={{ color:s.color }}>{s.ico}</div>
            <div className="stat-num">{s.num}</div>
            <div className="stat-label">{s.label}</div>
          </div>
        ))}
      </div>
      <div className="dash-grid">
        <div className="dash-card afu" style={{ animationDelay:'240ms' }}>
          <p className="dash-section-title">Quick Actions</p>
          <div className="quick-links">
            {[
              {ico:'♪',label:'Add Dance',sub:'New dance entry',fn:()=>setPage('dances')},
              {ico:'◈',label:'Add Attire',sub:'New attire entry',fn:()=>setPage('attires')},
              {ico:'♪',label:'Manage Dances',sub:`${dances.length} entries`,fn:()=>setPage('dances')},
              {ico:'◈',label:'Manage Attires',sub:`${attires.length} entries`,fn:()=>setPage('attires')},
            ].map((q,i)=>(
              <button key={i} className="quick-link" onClick={q.fn}>
                <span className="ql-ico">{q.ico}</span>
                <span><div className="ql-label">{q.label}</div><div className="ql-sub">{q.sub}</div></span>
              </button>
            ))}
          </div>
        </div>
        <div className="dash-card afu" style={{ animationDelay:'300ms' }}>
          <p className="dash-section-title">Recent Activity</p>
          <div className="activity-list">
            {ACTIVITY.map((a,i)=>(
              <div key={i} className="activity-item">
                <div className="act-dot" style={{ background:a.color }} />
                <div><div className="act-text">{a.text}</div><div className="act-time">{a.time}</div></div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
}

/* ── DANCES TABLE ────────────────────────────────────────────────── */
function DancesTable({ dances, setDances, showToast }) {
  const [search, setSearch] = useState('');
  const [catFilter, setCatFilter] = useState('All');
  const [modal, setModal] = useState(null); // null | 'add' | {item}
  const [delTarget, setDelTarget] = useState(null);

  const filtered = dances.filter(d => {
    const q = search.toLowerCase();
    const matchQ = !q || d.name.toLowerCase().includes(q) || d.desc.toLowerCase().includes(q);
    const matchC = catFilter === 'All' || d.cat === catFilter;
    return matchQ && matchC;
  });

  const handleSave = (data) => {
    if (data.id && dances.find(d=>d.id===data.id)) {
      setDances(prev => prev.map(d => d.id===data.id ? data : d));
      showToast(`"${data.name}" updated successfully`, 'success');
    } else {
      setDances(prev => [...prev, data]);
      showToast(`"${data.name}" added to archive`, 'success');
    }
    setModal(null);
  };

  const handleDelete = () => {
    setDances(prev => prev.filter(d => d.id !== delTarget.id));
    showToast(`"${delTarget.name}" deleted`, 'error');
    setDelTarget(null);
  };

  const badgeCls = { Pagaddut:'bp', Hinggatut:'bh', 'Dinuy-a':'bd' };

  return (
    <div>
      <div className="tbl-card">
        <div className="tbl-toolbar">
          <div className="tbl-search">
            <span className="tbl-search-ico">⌕</span>
            <input value={search} onChange={e=>setSearch(e.target.value)} placeholder="Search dances..." aria-label="Search dances" />
          </div>
          <div className="tbl-filter">
            <select value={catFilter} onChange={e=>setCatFilter(e.target.value)} aria-label="Filter by category">
              <option value="All">All Categories</option>
              {CATS.map(c=><option key={c}>{c}</option>)}
            </select>
          </div>
          <span className="tbl-count">{filtered.length} of {dances.length}</span>
          <button className="btn btn-primary btn-sm" onClick={()=>setModal('add')}>+ Add Dance</button>
        </div>
        <div style={{ overflowX:'auto' }}>
          <table aria-label="Dances table">
            <thead>
              <tr>
                <th style={{width:50}}></th>
                <th>Dance Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Video</th>
                <th style={{width:110}}>Actions</th>
              </tr>
            </thead>
            <tbody>
              {filtered.length === 0
                ? <tr className="empty-row"><td colSpan={6}>No dances found matching your search.</td></tr>
                : filtered.map(d => (
                    <tr key={d.id}>
                      <td><Swatch seed={d.seed} /></td>
                      <td><div className="td-name">{d.name}</div><div className="td-sub">ID: {d.id}</div></td>
                      <td><span className={`badge ${badgeCls[d.cat]||'bp'}`}>{d.cat}</span></td>
                      <td style={{ maxWidth:280 }}><div style={{ fontSize:'.8125rem', color:'var(--gray)', lineHeight:1.5, display:'-webkit-box', WebkitLineClamp:2, WebkitBoxOrient:'vertical', overflow:'hidden' }}>{d.desc}</div></td>
                      <td><span style={{ fontSize:'.75rem', color: d.video ? 'var(--green)' : 'var(--gray-lt)' }}>{d.video ? '✓ Set' : '— None'}</span></td>
                      <td>
                        <div className="td-actions">
                          <button className="btn btn-outline btn-sm" onClick={()=>setModal(d)} aria-label={`Edit ${d.name}`}>Edit</button>
                          <button className="btn btn-danger btn-sm" onClick={()=>setDelTarget(d)} aria-label={`Delete ${d.name}`}>Del</button>
                        </div>
                      </td>
                    </tr>
                  ))
              }
            </tbody>
          </table>
        </div>
      </div>
      {modal && <DanceModal item={modal==='add'?null:modal} onSave={handleSave} onClose={()=>setModal(null)} />}
      {delTarget && <DeleteConfirm name={delTarget.name} onConfirm={handleDelete} onCancel={()=>setDelTarget(null)} />}
    </div>
  );
}

/* ── ATTIRES TABLE ───────────────────────────────────────────────── */
function AttiresTable({ attires, setAttires, showToast }) {
  const [search, setSearch] = useState('');
  const [munFilter, setMunFilter] = useState('All');
  const [genFilter, setGenFilter] = useState('All');
  const [modal, setModal] = useState(null);
  const [delTarget, setDelTarget] = useState(null);

  const filtered = attires.filter(a => {
    const q = search.toLowerCase();
    const matchQ = !q || a.gen.toLowerCase().includes(q) || a.dia.toLowerCase().includes(q) || a.desc.toLowerCase().includes(q);
    const matchM = munFilter === 'All' || a.mun === munFilter;
    const matchG = genFilter === 'All' || a.gender === genFilter;
    return matchQ && matchM && matchG;
  });

  const handleSave = (data) => {
    if (data.id && attires.find(a=>a.id===data.id)) {
      setAttires(prev => prev.map(a => a.id===data.id ? data : a));
      showToast(`"${data.gen}" updated successfully`, 'success');
    } else {
      setAttires(prev => [...prev, data]);
      showToast(`"${data.gen}" added to archive`, 'success');
    }
    setModal(null);
  };

  const handleDelete = () => {
    setAttires(prev => prev.filter(a => a.id !== delTarget.id));
    showToast(`"${delTarget.gen}" deleted`, 'error');
    setDelTarget(null);
  };

  return (
    <div>
      <div className="tbl-card">
        <div className="tbl-toolbar">
          <div className="tbl-search">
            <span className="tbl-search-ico">⌕</span>
            <input value={search} onChange={e=>setSearch(e.target.value)} placeholder="Search attires..." aria-label="Search attires" />
          </div>
          <div className="tbl-filter">
            <select value={munFilter} onChange={e=>setMunFilter(e.target.value)} aria-label="Filter by municipality">
              <option value="All">All Municipalities</option>
              {MUNS.map(m=><option key={m}>{m}</option>)}
            </select>
          </div>
          <div className="tbl-filter">
            <select value={genFilter} onChange={e=>setGenFilter(e.target.value)} aria-label="Filter by gender">
              <option value="All">All Genders</option>
              <option>Female</option><option>Male</option>
            </select>
          </div>
          <span className="tbl-count">{filtered.length} of {attires.length}</span>
          <button className="btn btn-primary btn-sm" onClick={()=>setModal('add')}>+ Add Attire</button>
        </div>
        <div style={{ overflowX:'auto' }}>
          <table aria-label="Attires table">
            <thead>
              <tr>
                <th style={{width:50}}></th>
                <th>General Name</th>
                <th>Dialect Name</th>
                <th>Municipality</th>
                <th>Gender</th>
                <th>Source</th>
                <th style={{width:110}}>Actions</th>
              </tr>
            </thead>
            <tbody>
              {filtered.length === 0
                ? <tr className="empty-row"><td colSpan={7}>No attires found matching your search.</td></tr>
                : filtered.map(a => (
                    <tr key={a.id}>
                      <td><Swatch seed={a.seed} /></td>
                      <td><div className="td-name">{a.gen}</div><div className="td-sub">ID: {a.id}</div></td>
                      <td><em style={{ fontSize:'.84375rem', color:'var(--gold)' }}>{a.dia}</em></td>
                      <td><span style={{ fontSize:'.8rem', fontWeight:600 }}>{a.mun}</span></td>
                      <td><span className={`badge ${a.gender==='Female'?'bf':'bm'}`}>{a.gender}</span></td>
                      <td style={{ maxWidth:160 }}><div style={{ fontSize:'.75rem', color:'var(--gray)', overflow:'hidden', textOverflow:'ellipsis', whiteSpace:'nowrap' }}>{a.src}</div></td>
                      <td>
                        <div className="td-actions">
                          <button className="btn btn-outline btn-sm" onClick={()=>setModal(a)} aria-label={`Edit ${a.gen}`}>Edit</button>
                          <button className="btn btn-danger btn-sm" onClick={()=>setDelTarget(a)} aria-label={`Delete ${a.gen}`}>Del</button>
                        </div>
                      </td>
                    </tr>
                  ))
              }
            </tbody>
          </table>
        </div>
      </div>
      {modal && <AttireModal item={modal==='add'?null:modal} onSave={handleSave} onClose={()=>setModal(null)} />}
      {delTarget && <DeleteConfirm name={delTarget.gen} onConfirm={handleDelete} onCancel={()=>setDelTarget(null)} />}
    </div>
  );
}

/* ── AUTH PAGES ──────────────────────────────────────────────────── */
function LoginPage({ onLogin, setView }) {
  const [email, setEmail] = useState('');
  const [pass, setPass] = useState('');
  const [errs, setErrs] = useState({});
  const [loading, setLoading] = useState(false);

  const submit = (e) => {
    e.preventDefault();
    const err = {};
    if (!email.trim()) err.email = 'Email is required';
    else if (!/\S+@\S+\.\S+/.test(email)) err.email = 'Enter a valid email';
    if (!pass) err.pass = 'Password is required';
    if (Object.keys(err).length) { setErrs(err); return; }
    setLoading(true);
    setTimeout(() => { setLoading(false); onLogin({ email }); }, 900);
  };

  return (
    <div className="auth-bg">
      <div className="auth-card">
        <div className="auth-logo">Heri<span className="gem">◆</span>THREADS</div>
        <div className="auth-role">Admin Portal</div>
        <h1 className="auth-title">Welcome back</h1>
        <p className="auth-sub">Sign in to manage the cultural archive</p>
        <form onSubmit={submit} noValidate>
          <div className="form-group">
            <label className="form-label">Email Address</label>
            <input className={`form-input${errs.email?' error':''}`} type="email" value={email} onChange={e=>{setEmail(e.target.value);setErrs(v=>({...v,email:''}));}} placeholder="admin@ifugao.gov.ph" autoComplete="email" />
            {errs.email && <p className="form-error">{errs.email}</p>}
          </div>
          <div className="form-group">
            <label className="form-label">Password</label>
            <input className={`form-input${errs.pass?' error':''}`} type="password" value={pass} onChange={e=>{setPass(e.target.value);setErrs(v=>({...v,pass:''}));}} placeholder="••••••••" autoComplete="current-password" />
            {errs.pass && <p className="form-error">{errs.pass}</p>}
          </div>
          <button className="btn btn-primary btn-full" type="submit" disabled={loading} style={{ marginTop:'.5rem', opacity:loading?.7:1 }}>
            {loading ? 'Signing in…' : 'Sign In'}
          </button>
        </form>
        <div style={{ marginTop:'1.25rem', display:'flex', justifyContent:'space-between', alignItems:'center' }}>
          <button className="auth-link" onClick={()=>setView('forgot')}>Forgot password?</button>
          <button className="auth-link" onClick={()=>setView('register')}>Create account</button>
        </div>
        <p style={{ marginTop:'1.5rem', fontSize:'.72rem', color:'var(--gray-lt)', textAlign:'center' }}>Demo: any email + password to sign in</p>
      </div>
    </div>
  );
}

function RegisterPage({ setView }) {
  const [form, setForm] = useState({ name:'', email:'', pass:'', confirm:'' });
  const [errs, setErrs] = useState({});
  const set = (k,v) => { setForm(f=>({...f,[k]:v})); setErrs(e=>({...e,[k]:''})); };
  const submit = (e) => {
    e.preventDefault();
    const err = {};
    if (!form.name.trim()) err.name = 'Full name is required';
    if (!form.email.trim()) err.email = 'Email is required';
    if (form.pass.length < 8) err.pass = 'Password must be at least 8 characters';
    if (form.pass !== form.confirm) err.confirm = 'Passwords do not match';
    if (Object.keys(err).length) { setErrs(err); return; }
    setView('login');
  };
  return (
    <div className="auth-bg">
      <div className="auth-card">
        <div className="auth-logo">Heri<span className="gem">◆</span>THREADS</div>
        <div className="auth-role">Admin Portal</div>
        <h1 className="auth-title">Create Account</h1>
        <p className="auth-sub">Register for administrative access</p>
        <form onSubmit={submit} noValidate>
          <div className="form-group">
            <label className="form-label">Full Name</label>
            <input className={`form-input${errs.name?' error':''}`} value={form.name} onChange={e=>set('name',e.target.value)} placeholder="Juan dela Cruz" />
            {errs.name && <p className="form-error">{errs.name}</p>}
          </div>
          <div className="form-group">
            <label className="form-label">Email Address</label>
            <input className={`form-input${errs.email?' error':''}`} type="email" value={form.email} onChange={e=>set('email',e.target.value)} placeholder="juan@ifugao.gov.ph" />
            {errs.email && <p className="form-error">{errs.email}</p>}
          </div>
          <div className="form-row">
            <div className="form-group">
              <label className="form-label">Password</label>
              <input className={`form-input${errs.pass?' error':''}`} type="password" value={form.pass} onChange={e=>set('pass',e.target.value)} placeholder="Min. 8 characters" />
              {errs.pass && <p className="form-error">{errs.pass}</p>}
            </div>
            <div className="form-group">
              <label className="form-label">Confirm Password</label>
              <input className={`form-input${errs.confirm?' error':''}`} type="password" value={form.confirm} onChange={e=>set('confirm',e.target.value)} placeholder="Repeat password" />
              {errs.confirm && <p className="form-error">{errs.confirm}</p>}
            </div>
          </div>
          <button className="btn btn-primary btn-full" type="submit" style={{ marginTop:'.25rem' }}>Create Account</button>
        </form>
        <div style={{ marginTop:'1.25rem', textAlign:'center' }}>
          <button className="auth-link" onClick={()=>setView('login')}>← Back to sign in</button>
        </div>
      </div>
    </div>
  );
}

function ForgotPage({ setView }) {
  const [email, setEmail] = useState('');
  const [sent, setSent] = useState(false);
  const [err, setErr] = useState('');
  const submit = (e) => {
    e.preventDefault();
    if (!email.trim()) { setErr('Email is required'); return; }
    setSent(true);
  };
  return (
    <div className="auth-bg">
      <div className="auth-card">
        <div className="auth-logo">Heri<span className="gem">◆</span>THREADS</div>
        <div className="auth-role">Admin Portal</div>
        <h1 className="auth-title">Reset Password</h1>
        <p className="auth-sub">Enter your email to receive a reset link</p>
        {sent ? (
          <div style={{ background:'#F0FDF4', border:'1.5px solid #86EFAC', borderRadius:'.625rem', padding:'1.25rem', textAlign:'center', marginBottom:'1rem' }}>
            <div style={{ fontSize:'1.5rem', marginBottom:'.375rem' }}>✉️</div>
            <p style={{ fontSize:'.875rem', color:'#166534', fontWeight:600 }}>Reset link sent!</p>
            <p style={{ fontSize:'.8rem', color:'#166534', marginTop:'.25rem' }}>Check your inbox at {email}</p>
          </div>
        ) : (
          <form onSubmit={submit} noValidate>
            <div className="form-group">
              <label className="form-label">Email Address</label>
              <input className={`form-input${err?' error':''}`} type="email" value={email} onChange={e=>{setEmail(e.target.value);setErr('');}} placeholder="your@email.com" />
              {err && <p className="form-error">{err}</p>}
            </div>
            <button className="btn btn-primary btn-full" type="submit">Send Reset Link</button>
          </form>
        )}
        <div style={{ marginTop:'1.25rem', textAlign:'center' }}>
          <button className="auth-link" onClick={()=>setView('login')}>← Back to sign in</button>
        </div>
      </div>
    </div>
  );
}

/* ── PAGE TITLES ─────────────────────────────────────────────────── */
const PAGE_META = {
  dashboard: { title:'Dashboard',     action: null },
  dances:    { title:'Manage Dances', action: null },
  attires:   { title:'Manage Attires',action: null },
};

/* ── APP ROOT ─────────────────────────────────────────────────────── */
function App() {
  const [authView, setAuthView] = useState('login');
  const [user, setUser] = useState(null);
  const [page, setPage] = useState('dashboard');
  const [dances, setDances] = useState(initDances);
  const [attires, setAttires] = useState(initAttires);
  const [toast, setToast] = useState(null);

  const showToast = (msg, type='success') => setToast({ msg, type, key: Date.now() });

  if (!user) {
    if (authView === 'register') return <RegisterPage setView={setAuthView} />;
    if (authView === 'forgot')   return <ForgotPage setView={setAuthView} />;
    return <LoginPage onLogin={u => { setUser(u); showToast('Welcome back, Administrator!','success'); }} setView={setAuthView} />;
  }

  const meta = PAGE_META[page] || PAGE_META.dashboard;

  return (
    <div className="shell">
      <Sidebar page={page} setPage={setPage} onLogout={() => { setUser(null); setAuthView('login'); }} />
      <div className="content">
        <div className="topbar">
          <h1 className="topbar-title">{meta.title}</h1>
          <div className="topbar-actions">
            {page === 'dances'  && <button className="btn btn-primary btn-sm" onClick={()=>document.dispatchEvent(new CustomEvent('add-dance'))}>+ Add Dance</button>}
            {page === 'attires' && <button className="btn btn-primary btn-sm" onClick={()=>document.dispatchEvent(new CustomEvent('add-attire'))}>+ Add Attire</button>}
          </div>
        </div>
        <div className="main">
          {page === 'dashboard' && <Dashboard dances={dances} attires={attires} setPage={setPage} />}
          {page === 'dances'    && <DancesTable dances={dances} setDances={setDances} showToast={showToast} />}
          {page === 'attires'   && <AttiresTable attires={attires} setAttires={setAttires} showToast={showToast} />}
        </div>
      </div>
      {toast && <Toast key={toast.key} msg={toast.msg} type={toast.type} onDone={() => setToast(null)} />}
    </div>
  );
}

ReactDOM.createRoot(document.getElementById('root')).render(<App />);
