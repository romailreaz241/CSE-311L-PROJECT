/* chatbot.js — kept in JavaScript because the chatbot UI needs live browser interaction. */
const CHATBOT = location.pathname.includes('/front_end_connection/') ? '../php/chatbot.php' : 'php/chatbot.php';
let _chatHistory = [];

function injectChat() {
  if (document.getElementById('ms-chat')) return;
  document.body.insertAdjacentHTML('beforeend', `
    <div id="ms-chat"
      style="position:fixed;bottom:84px;right:20px;width:320px;height:440px;
             background:var(--bg-2);border:1px solid var(--glass-border-l);
             border-radius:var(--r-xl);box-shadow:var(--shadow-lg);z-index:800;
             display:flex;flex-direction:column;opacity:0;pointer-events:none;
             transform:scale(.88) translateY(12px);transition:all .3s cubic-bezier(.34,1.56,.64,1);
             overflow:hidden">
      <div style="background:linear-gradient(135deg,#1a96ae,#1a6dca);padding:14px 16px;
                  display:flex;justify-content:space-between;align-items:center">
        <span style="font-weight:700;font-size:.9rem;color:#fff">🤖 MediBot</span>
        <button onclick="toggleChat()"
          style="background:rgba(255,255,255,.15);border:none;color:#fff;
                 border-radius:50%;width:26px;height:26px;cursor:pointer;font-size:1rem;line-height:1">×</button>
      </div>
      <div id="chat-msgs"
        style="flex:1;overflow-y:auto;padding:12px;display:flex;flex-direction:column;gap:8px;
               scrollbar-width:thin;scrollbar-color:var(--bg-4) transparent"></div>
      <div style="padding:9px;border-top:1px solid var(--glass-border);background:var(--bg-1);display:flex;gap:7px">
        <input id="chat-input" placeholder="Ask about tests…"
          style="flex:1;background:var(--bg-3);border:1px solid var(--glass-border-l);
                 border-radius:var(--r);padding:8px 12px;font-size:.82rem;color:var(--text);
                 outline:none;min-width:0;font-family:inherit"
          onkeyup="if(event.key==='Enter') sendChat()">
        <button onclick="sendChat()"
          style="background:linear-gradient(135deg,var(--accent),#1a6dca);color:#fff;border:none;
                 border-radius:var(--r);padding:8px 13px;font-weight:700;font-size:.78rem;
                 cursor:pointer;font-family:inherit;white-space:nowrap">Send</button>
      </div>
    </div>
    <button id="chat-toggle"
      onclick="toggleChat()"
      style="position:fixed;bottom:20px;right:20px;width:50px;height:50px;
             background:linear-gradient(135deg,var(--accent),#1a6dca);border:none;
             border-radius:50%;cursor:pointer;box-shadow:0 4px 18px rgba(41,196,217,.4);
             font-size:1.2rem;z-index:799;transition:all .3s;display:flex;
             align-items:center;justify-content:center">🤖</button>
    <style>
      #ms-chat.open{opacity:1!important;transform:scale(1) translateY(0)!important;pointer-events:auto!important}
      #chat-toggle.hide{opacity:0;pointer-events:none}
      .cm{padding:8px 12px;border-radius:10px;max-width:84%;font-size:.8rem;line-height:1.5;word-wrap:break-word}
      .cm.u{background:linear-gradient(135deg,var(--accent),#1a6dca);color:#fff;align-self:flex-end;border-radius:10px 10px 2px 10px}
      .cm.b{background:var(--bg-3);color:var(--text);align-self:flex-start;border-radius:10px 10px 10px 2px}
      .dots span{display:inline-block;width:6px;height:6px;background:var(--accent);border-radius:50%;margin:0 2px;animation:db .6s infinite}
      .dots span:nth-child(2){animation-delay:.1s}.dots span:nth-child(3){animation-delay:.2s}
      @keyframes db{0%,100%{opacity:.3;transform:translateY(0)}50%{opacity:1;transform:translateY(-5px)}}
    </style>`);
  addBotMsg('👋 Hi! I\'m <strong>MediBot</strong>. Ask me anything about our medical tests.');
}

function toggleChat() {
  const c = document.getElementById('ms-chat');
  const t = document.getElementById('chat-toggle');
  if (!c) return;
  const open = c.classList.toggle('open');
  t?.classList.toggle('hide', open);
  if (open) document.getElementById('chat-input')?.focus();
}
function sendChat() {
  const inp = document.getElementById('chat-input');
  const msg = (inp?.value || '').trim();
  if (!msg) return;
  inp.value = '';
  sendChatMsg(msg);
}
function askBot(name) {
  if (!document.getElementById('ms-chat')?.classList.contains('open')) toggleChat();
  sendChatMsg('Tell me about the ' + name + ' test — what it does, how to prepare, and what to expect.');
}
async function sendChatMsg(msg) {
  addUserMsg(msg);
  const typing = addTyping();
  try {
    const res = await fetch(CHATBOT, {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({message:msg, history:_chatHistory.slice(-6)})
    });
    const data = await res.json();
    typing.remove();
    const reply = data.reply || data.error || 'Sorry, I could not process that.';
    addBotMsg(reply);
    _chatHistory.push({role:'user',content:msg});
    _chatHistory.push({role:'assistant',content:reply});
  } catch {
    typing.remove();
    addBotMsg('Sorry, trouble connecting. Please try again.');
  }
}
function addUserMsg(t) { appendMsg('u', null, t); }
function addBotMsg(h) {
  const el = appendMsg('b');
  el.innerHTML = h.replace(/\*\*(.+?)\*\*/g,'<strong>$1</strong>').replace(/\n/g,'<br>');
}
function addTyping() {
  const el = appendMsg('b');
  el.innerHTML = '<div class="dots"><span></span><span></span><span></span></div>';
  return el;
}
function appendMsg(cls, el, text) {
  const msgs = document.getElementById('chat-msgs');
  if (!msgs) return document.createElement('div');
  const d = el || document.createElement('div');
  d.className = 'cm ' + cls;
  if (text) d.textContent = text;
  msgs.appendChild(d);
  msgs.scrollTop = msgs.scrollHeight;
  return d;
}
document.addEventListener('DOMContentLoaded', injectChat);
